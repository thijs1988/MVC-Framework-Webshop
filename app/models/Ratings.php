<?php
namespace App\Models;
use Core\{DB,Model,H,Router};
use Core\Validators\{RequiredValidator, UniqueValidator};

class Ratings extends Model{

  protected static $_table = 'rating_system';
  protected static $_softDelete = true;

  public $id,$ratings,$product_id,$deleted,$name,$review;

  public static function saveRatings($product_id,$rating){
    $ratings = new Ratings();
    $ratings->product_id = $product_id;
    $ratings->ratings = $rating;
    $ratings->deleted = 0;
    $ratings->name = null;
    $ratings->review = null;
    if($ratings->save()){
      $resp = ['success'=>true, 'rating'=>$ratings->data()];
    }else{
      $resp = ['success'=>false, 'errors'=>$ratings->getErrorMessages()];
    }
    return $resp;
  }


  public static function saveReviews($product_id,$rating,$name,$review){
    $ratings = new Ratings();
    $ratings->product_id = $product_id;
    $ratings->ratings = $rating;
    $ratings->deleted = 0;
    $ratings->name = $name;
    $ratings->review = $review;
    if($ratings->save()){
      $resp = ['success'=>true, 'rating'=>$ratings->data()];
    }else{
      $resp = "Something went wrong please try agian..";
    }
    return $resp;
  }

  public static function getAverageRating(){
    $db = DB::getInstance();
    $sql= "SELECT rating_system.*, COUNT(rating_system.product_id) as amount, SUM(rating_system.ratings) as total
    from rating_system
    GROUP BY product_id";
    return $db->query($sql)->results();
  }

  public static function getReviewsById($id){
    $db = DB::getInstance();
    $sql = "SELECT r.name, r.review
    from rating_system as r
    where r.product_id = $id and r.name != 'null' and r.review != 'null'";
    return $db->query($sql)->results();
  }

}
