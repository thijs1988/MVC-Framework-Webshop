<?php
namespace App\Controllers;
use Core\{H,Controller,Session,Router};
use App\Models\{Products,ProductImages,Users,Brands,Options,ProductOptionRefs,Menu};
use App\Lib\Utilities\Uploads;


class AdminproductsController extends Controller {

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){
    $products = Products::findByUserId($this->currentUser->id);
    $totals = Products::getSumProducts($products);
    $this->view->totals = $totals;
    $this->view->products = $products;
    $this->view->render('adminproducts/index');
  }

  public function deleteAction(){
    $resp = ['success' => false, 'msg'=>'something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $product = Products::findByIdAndUserId($id, $this->currentUser->id);
      if($product){
        ProductImages::deleteImages($id);
        $product->delete();
        $resp = ['success' => true, 'msg' => 'Product Deleted.', 'model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
  }

  public function toggleFeaturedAction(){
    $resp = ['success' => false, 'msg'=>'something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $product = Products::findByIdAndUserId($id, $this->currentUser->id);
      if($product){
        $product->featured = ($product->featured == 1)? 0 : 1;
        $product->save();
        $msg = ($product->featured == 1)? "Product now featured" : "Product no longer featured";
        $resp = ['success' => true, 'msg' => $msg, 'model_id' => $id, 'featured' => $product->featured];
      }
    }
    $this->jsonResponse($resp);
  }

  public function editAction($id){
    $this->view->menuItems = Menu::findMenuItems();
    $user = Users::currentUser();
    $product = ($id == 'new')? new Products() : Products::findByIdAndUserId((int) $id, $user->id);
    if(!$product){
      Session::addMsg('danger', 'You do not have permission to edit this product.');
      Router::redirect('adminproducts');
    }
    $images = ProductImages::findByProductId($product->id);
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $options = $_POST['options'];
      unset($_REQUEST['options']);
      $files = $_FILES['productImages'];
      $isFiles = $files['tmp_name'][0] != '';
      if($isFiles){
        //$productImage = new ProductImages();
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages', trim($msg));
        }
      }
      $values = $_POST['cat_id'];
      $v = "";
      foreach($values as $value){
      htmlentities($value, ENT_QUOTES, 'UTF-8');
      $v .= $value.",";
      }
      $product->assign($this->request->get(), Products::blacklist);
      $product->cat_id = $v;
      $product->featured = ($this->request->get('featured') == 'on')? 1 : 0;
      $product->has_options = ($this->request->get('has_options') == 'on')? 1 : 0;
      $product->user_id = $this->currentUser->id;
      if($id == 'new'){$product->save();}
      if($product->validationPassed()){
        if($isFiles){
        //upload images
        ProductImages::uploadProductImages($product->id,$uploads);
        }
        $sortOrder = json_decode($_POST['images_sorted']);
        ProductImages::updateSortByProductId($product->id, $sortOrder);
        $inventory = 0;
        //save options
        if($product->hasOptions()){
          foreach($options as $option_id){
            $ref = ProductOptionRefs::findOrCreate($product->id,$option_id);
            $ref->inventory = $this->request->get("inventory_".$option_id);
            $ref->save();
            $inventory += $ref->inventory;
          }
        } else{
          $inventory = $this->request->get('inventory');
        }
        $product->inventory = $inventory;
        $product->save();
        //redirect
        Session::addMsg('success','Product Updated!');
        Router::redirect('adminproducts');
      }
    }
    $this->view->options = Options::getOptionsByProductId($product->id);
    $this->view->header = ($id == 'new')? "Add New Product" : "Edit " . $product->name;
    $this->view->brands = Brands::getOptionsForForm($user->id);
    $this->view->images = $images;
    $this->view->product = $product;
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminproducts/edit');
  }

  function deleteImageAction(){
    $resp = ['success' => false];
    if($this->request->isPost()){
      $user = Users::currentUser();
      $id = $this->request->get('image_id');
      $image = ProductImages::findById($id);
      $product = Products::findByIdAndUserId($image->product_id,$user->id);
      if($product && $image){
      ProductImages::deleteById($image->id);
      $resp = ['success'=>true, 'model_id'=>$image->id];
      }
    }
    $this->jsonResponse($resp);
  }

  function optionsAction(){
   $this->view->options = Options::find([
     'order' => 'name'
   ]);
   $this->view->render('adminproducts/options');
 }

 function editOptionAction($id){
   $option = ($id == 'new')? new Options(): Options::findById((int)$id);
   if($this->request->isPost()){
     $this->request->csrfCheck();
     $option->name = $this->request->get('name');
     if($option->save()){
       Session::addMsg('success','Option Saved!');
       Router::redirect('adminproducts/options');
     }
   }
   $this->view->option = $option;
   $this->view->errors = $option->getErrorMessages();
   $this->view->header = ($id == 'new')? "Add Product Option" : "Edit Product Option";
   $this->view->render('adminproducts/editOption');
 }

 function deleteOptionAction(){
   $id = $this->request->get('id');
   $option = Options::findById((int)$id);
   $resp = ['success'=>false,'msg'=>'Something went wrong...'];
   if($option){
     $option->delete();
     $resp['success'] = true;
     $resp['msg'] = 'Option Deleted';
     $resp['model_id'] = $id;
   }
   $this->jsonResponse($resp);
 }

 function getOptionsForFormAction(){
   $options = Options::find([
     'conditions' => 'name LIKE ?',
     'bind' => ['%'.$this->request->get('q').'%']
   ]);
   $items = [];
   foreach($options as $option){
     $items[] = ['id'=>$option->id, 'text'=>$option->name];
   }
   $resp = ['items'=>$items];
   $this->jsonResponse($resp);
 }
}
