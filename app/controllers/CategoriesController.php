<?php
namespace App\Controllers;
use Core\{Controller,H,Router};
use App\Models\{Products,Brands,Users,Menu,Categories, Ratings};

class CategoriesController extends Controller {

  public function indexAction($id){
    $ratings = new Ratings();
    $search = $this->request->get('search');
    $brand = $this->request->get('brand');
    $min_price = $this->request->get('min_price');
    $max_price = $this->request->get('max_price');
    $page = (!empty($this->request->get('page')))? $this->request->get('page') : 1;
    $limit = 4;
    $offset = ($page - 1) * $limit;
    $options = [
      'search'=>$search, 'min_price'=>$min_price, 'max_price'=>$max_price,
      'brand'=>$brand, 'limit'=>4, 'offset'=>$offset
    ];
    $results = Products::getProducts($options, $id);
    $products = $results['results'];
    $total = $results['total'];
    $average = Ratings::getAverageRating();
    $this->view->ratings = $ratings;
    $this->view->displayErrors = $ratings->getErrorMessages();
    $this->view->average = $average;
    $this->view->page = $page;
    $this->view->totalPages = ceil($total / $limit);
    $this->view->hasFilters = Products::hasFilters($options);
    $this->view->products = $products;
    $this->view->min_price = $min_price;
    $this->view->max_price = $max_price;
    $this->view->brand = $brand;
    $this->view->search = $search;
    $this->view->brandOptions = Brands::getOptionsForForm();
    $this->view->products = $products;
    $this->view->render('categories/index');
  }

  public function saveAction(){
  if($this->request->isPost()){
    $product_id = $this->request->get('product_id');
    $rating = $this->request->get('ratings');
    if ($rating != null){
    $resp = Ratings::saveReviews($product_id,$rating,$name=null,$review=null);
    $message = "Saved! Thank you for your rating..";
    }else{
    $message= "Please rate by clicking on the stars..";
      }
    }
    $this->jsonResponse($message);
  }

  public function reviewsAction(){
    if($this->request->isPost()){
      $id = (int)$this->request->get('id');
      $product = Products::findProductById($id);
      $average = Ratings::getAverageRating();
      $reviews = Ratings::getReviewsById($id);
      $av = 0;
      foreach ($average as $avg) {
        if ($product->id == $avg->product_id){
          $av = $avg->total / $avg->amount;
        }
      }
      $av = round($av,0, PHP_ROUND_HALF_UP);
      $resp = ['success'=>false];
        if ($product){
          $resp['success'] = true;
          $resp['reviews'] = $reviews;
          $resp['product'] = $product->id;
          $resp['average'] = $av;
        }
        $this->jsonResponse($resp);
    }
  }

  public function savereviewAction(){
    if($this->request->isPost()){
      $name = $this->request->get('name');
      $review = $this->request->get('review');
      $product_id = $this->request->get('product_id');
      $rating = $this->request->get('rating');
      $resp = Ratings::saveReviews($product_id, $rating, $name, $review);
      $this->jsonResponse($resp);
    }
  }
}
