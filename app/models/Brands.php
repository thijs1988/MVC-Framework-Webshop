<?php
namespace App\Models;
use Core\Model;
use Core\Validators\{RequiredValidator, UniqueValidator};
use Core\H;

class Brands extends Model{
  public $id, $created_at, $updated_at, $brandname, $deleted=0;
  protected static $_table = 'brands';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();

  }

  public function validator(){
    $this->runValidation(new RequiredValidator($this,['field'=>'brandname', 'msg'=>'Brand Name is required.']));
    $this->runValidation(new UniqueValidator($this,['field'=>['brandname', 'user_id', 'deleted'], 'msg'=>'Brand Name has to be unique.']));

  }

  public static function findByUserIdAndId($user_id,$id){
    return self::findFirst([
      'conditions' => "user_id = ? AND id = ?",
      'bind' => [$user_id,$id]
    ]);
  }

  public static function getOptionsForForm($user_id=''){
    $params = [
      'columns' => 'id, brandname',
      'order' => 'brandname'
    ];

    if(!empty($user_id)){
      $params['conditions'] = "user_id = ?";
      $params['bind'][] = $user_id;
    }
    $brands = self::find($params);
    $brandAry = [''=>'-Select Brand-'];
    foreach($brands as $brand){
      $brandAry[$brand->id] = $brand->brandname;
    }
    return $brandAry;
  }
}
