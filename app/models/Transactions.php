<?php
namespace App\Models;
use Core\{Model,DB,H};
use Core\Validators\{RequiredValidator};

class Transactions extends Model{
  protected static $_table = 'transactions';
  protected static $_softDelete = true;

  public $id, $created_at, $updated_at, $cart_id, $gateway, $type, $amount, $success = 0;
  public $charge_id, $reason, $card_brand, $last4, $name, $shipping_address1, $shipping_address2;
  public $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $deleted = 0;

  public function beforeSave(){
    $this->timeStamps();
  }

  public function validateShipping(){
    $this->runValidation(new RequiredValidator($this,['field'=>'name', 'msg'=>'Name is required.']));
    $this->runValidation(new RequiredValidator($this,['field'=>'shipping_address1', 'msg'=>'Address is required.']));
    $this->runValidation(new RequiredValidator($this,['field'=>'shipping_city', 'msg'=>'City is required.']));
    $this->runValidation(new RequiredValidator($this,['field'=>'shipping_state', 'msg'=>'State is required.']));
    $this->runValidation(new RequiredValidator($this,['field'=>'shipping_zip', 'msg'=>'Zip is required.']));
  }

  public static function getDailySales($range = 'last-28'){
    $today = date("Y-m-d");
    $range = str_replace("last-","",$range);
    $fromDate = date("Y-m-d", strtotime("-".$range." days"));
    $db = DB::getInstance();
    $sql = "SELECT date(created_at) as created_at, SUM(amount) as amount
    from transactions
    where success = 1 and created_at between ? and ?
    group by DATE(created_at)";
    return $db->query($sql, [$fromDate,$today." 23:59:59"])->results();
  }

  public static function getSoldItems($source = 'brandname'){
    $db = DB::getInstance();
    if($source == 'brandname'){
      $sql = "SELECT brands.brandname, SUM(qty) as qty, carts.purchased
      from cart_items
      join carts on cart_items.cart_id = carts.id
      join products on cart_items.product_id = products.id
      join brands on products.brand_id = brands.id
      where carts.purchased = 1
      group by brands.id";
    }elseif($source == 'items'){
      $sql = "SELECT products.name, SUM(qty) as qty, carts.purchased
      from cart_items
      join carts on cart_items.cart_id = carts.id
      join products on cart_items.product_id = products.id
      join brands on products.brand_id = brands.id
      where carts.purchased = 1
      group by products.id";
    }
    return $db->query($sql)->results();
  }

  public static function getTransactions(){
    $db = DB::getInstance();
    $sql = "SELECT * From transactions where deleted = 0";
    return $db->query($sql)->results();
  }

  public static function getDeletedTransactions(){
    $db = DB::getInstance();
    $sql = "SELECT * From transactions where deleted = 1";
    return $db->query($sql)->results();
  }

  public static function findDeletedOrders($id){
    $db = DB::getInstance();
    $sql = "UPDATE transactions
    set deleted = 0
    where id = $id";
    return $db->query($sql)->results();
  }

  public static function findTransactionById($id){
    return self::findFirst([
      "conditions" => "id = ?",
      "bind" => [$id]
    ]);
  }

  public static function getYearTotals($year = 2020){
    $db = DB::getInstance();
    $sql = "SELECT created_at, SUM(amount) as amount
    from transactions
    where success = 1 and YEAR(created_at) = '{$year}'
    GROUP BY MONTH(created_at)
    ";
    return $db->query($sql)->results();
  }
}
