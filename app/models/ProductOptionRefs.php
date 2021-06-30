<?php
namespace App\Models;

use Core\Model;

class ProductOptionRefs extends Model{
  protected static $_table  = 'product_option_refs';
  public $id, $created_at, $updated_at, $product_id, $option_id, $inventory = 0;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findOrCreate($product_id,$option_id){
    $ref = self::findByProductId($product_id,$option_id);
    if(!$ref){
      $ref = new self();
      $ref->product_id = (int)$product_id;
      $ref->option_id = (int) $option_id;
    }
    return $ref;
  }

  public static function findByProductId($product_id,$option_id){
    return self::findFirst([
      'conditions' => "product_id = ? AND option_id = ?",
      'bind' => [$product_id,$option_id]
    ]);
  }
}
