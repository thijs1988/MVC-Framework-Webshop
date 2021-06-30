<?php
  namespace App\Controllers;
  use Core\Controller;
  use Core\Router;
  use Core\Session;
  use Core\H;
  use App\Models\Products;

  class ProductsController extends Controller {

    public function detailsAction($product_id) {
      $product = Products::findById((int)$product_id);
      if(!$product){
        Session::addMsg('danger', "That product is not available.");
        Router::redirect('/home');
      }
      $options = $product->getOptions();
      $selectOptions = [''=>'- Choose an option -'];
      foreach($options as $option){
        $selectOptions[$option->id] = $option->name . ' ('.$option->inventory.'available)';
      }
      $this->view->selectOptions = $selectOptions;
      $this->view->product = $product;
      $this->view->images = $product->getImages();
      $this->view->render('products/details');
    }
  }
