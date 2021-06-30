<?php
namespace App\Models;
use Core\{Model,Session,Cookie,H};
use Core\Validators\RequiredValidator;
use App\Models\{Carts,Products,ProductOptionRefs};

class CartItems extends Model {

  public $id,$created_at,$updated_at,$user_id,$cart_id,$product_id,$inventory,$option_id,$qty,$deleted=0;
  protected static $_table = 'cart_items';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findByProductIdOrCreate($cart_id,$product_id,$option_id){
    $item = self::findFirst([
      'conditions' => "cart_id = ? AND product_id = ? AND option_id = ?",
      'bind' => [$cart_id,$product_id,$option_id]
    ]);
    if(!$item){
      $item = new self();
      $item->cart_id = $cart_id;
      $item->product_id = $product_id;
      $item->option_id = $option_id;
      //$item->save();
    }
    return $item;
  }

  public static function getItemTotals($cart_id){
    $itemCount = 0;
    $subTotal = 0.00;
    $shippingTotal = 0.00;
    $items = Carts::findAllItemsByCartId((int)$cart_id);
    foreach($items as $item){
      $itemCount += $item->qty;
      $shippingTotal += ($item->qty * $item->shipping);
      $subTotal += ($item->qty * $item->price);
    }return array($shippingTotal,$subTotal,$itemCount,$items);
  }

  public static function addProductToCart($cart_id, $product_id, $option_id=null){
    $product = Products::findById((int)$product_id);
    if($product){
      $item = self::findByProductIdOrCreate($cart_id,$product_id,$option_id);
      // validate to maek sure there is a option selected if necessary
      if($item->qty >= $item->qtyAvailable($item->product_id)){
       $item->addErrorMessage('option_id','You have reached the maximum available');
     }
      if($product->hasOptions() && empty($option_id)){
        $item->addErrorMessage('option_id','You must choose an option.');
      }
    }
    return $item;
  }

  public static function qtyAvailable($product_id){
   $available = 0;
   $model = (!empty($item->option_id))? ProductOptionRefs::findByProductId($product_id,$item->option_id) : Products::findById($product_id);
   if($model){
     $available = $model->inventory;
   }
   return $available;
 }

  public static function findCartItemsByCartId($id){
    $cart_items = self::find([
      'conditions' => "cart_id = ?",
      'bind' => [$id]
    ]);
    return $cart_items;
  }
}
