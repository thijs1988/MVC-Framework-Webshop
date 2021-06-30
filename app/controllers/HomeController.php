<?php
  namespace App\Controllers;
  use Core\{Controller,H};
  use App\Models\{Products,Brands,Users,Menu};

  class HomeController extends Controller {

    public function indexAction() {
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
      $results = Products::getProducts($options,$cat = '');
      $products = $results['results'];
      $total = $results['total'];
      $this->view->page = $page;
      $this->view->totalPages = ceil($total / $limit);
      $this->view->hasFilters = Products::hasFilters($options);
      $this->view->products = $products;
      $this->view->min_price = $min_price;
      $this->view->max_price = $max_price;
      $this->view->brand = $brand;
      $this->view->search = $search;
      $this->view->brandOptions = Brands::getOptionsForForm();
      $this->view->render('home/index');
    }


  }
