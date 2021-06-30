<?php
namespace App\Models;
use Core\{Model,Session,Cookie,DB,H};
use App\Models\{CartItems, Users};

class Carts extends Model {

  public $id,$created_at,$updated_at,$purchased=0,$deleted=0;
  protected static $_table = 'carts';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findCurrentCartOrCreateNew(){
    if(!Cookie::exists(CART_COOKIE_NAME)){
      $cart = new Carts();
      $cart->save();
    }else{
      $cart_id = Cookie::get(CART_COOKIE_NAME);
      $cart = self::findById((int)$cart_id);
    }
    Cookie::set(CART_COOKIE_NAME, $cart->id, CART_COOKIE_EXPIRY);
    return $cart;
  }

  public static function findAllItemsByCartId($cart_id){
    $sql = " SELECT items.*, p.name, p.price, p.shipping, p.inventory, p.user_id, pi.url, brands.brandname as brand, options.name as options
    FROM cart_items as items
    join products as p on p.id = items.product_id
    join product_images as pi on p.id = pi.product_id
    left join options on items.option_id = options.id
    left join brands on brands.id = p.brand_id
    where items.cart_id = ? AND pi.sort = 0 and pi.deleted = 0 and items.deleted = 0
    GROUP BY items.id
    ";
    $db = DB::getInstance();
    return $db->query($sql, [(int)$cart_id],CartItems::class)->results();
  }

  public static function purchaseCart($cart_id){
    $cart = self::findById($cart_id);
    $cart->purchased = 1;
    $cart->save();
    Cookie::delete(CART_COOKIE_NAME);
    Session::deleteSessionArray(['tx','ch','mollie','orderId','chargeId','name','charge','issuer','shipping_zip','shipping_state','shipping_city','shipping_address2','shipping_address1']);
    self::purchaseInventoryUpdate($cart);
    return $cart;
  }

  public static function purchaseInventoryUpdate($cart){
   $items = self::findAllItemsByCartId($cart->id);
   foreach($items as $item){
     $product = Products::findById($item->product_id);
     if(!empty($item->option_id)){
       $ref = ProductOptionRefs::findByProductId($item->product_id, $item->option_id);
       $ref->inventory = $ref->inventory - $item->qty;
       $ref->save();
     }
     $product->inventory = $product->inventory - $item->qty;
     $product->save();
   }
 }

  public static function itemCountCurrentCart(){
    if(!Cookie::exists(CART_COOKIE_NAME)){
      return 0;
    }
    $cart_id = Cookie::get(CART_COOKIE_NAME);
    $db = DB::getInstance();
    $sql = "SELECT SUM(qty) as qty FROM cart_items where cart_id = ? AND deleted = 0";
    $result = $db->query($sql,[(int)$cart_id])->first();
    return $result->qty;
  }
}
