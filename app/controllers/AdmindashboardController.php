<?php
namespace App\Controllers;
use Core\{View,controller,H};
use App\Models\{Transactions};

class AdmindashboardController extends Controller {
  public function __construct($controller, $action){
    parent::__construct($controller, $action);
    $this->view->setLayout('admin');
  }

  public function indexAction(){
    $this->view->render('admindashboard/index');
  }

  public function getDailySalesAction(){
    $range = $this->request->get('range');
    $transactions = Transactions::getDailySales($range);
    $labels = [];
    $data = [];
    foreach($transactions as $tx){
      $labels[] = $tx->created_at;
      $data[] = $tx->amount;
    }
    $resp = ['data'=>$data, 'labels'=>$labels];
    $this->jsonResponse($resp);
  }

  public function getSoldItemsAction(){
    $source = $this->request->get('source');
    $soldItems = Transactions::getSoldItems($source);
    $labels = [];
    $data = [];
    if($source == 'brandname'){
      foreach ($soldItems as $item){
        $labels[] = $item->brandname;
        $data[] = $item->qty;
      }
    }elseif($source == 'items'){
      foreach ($soldItems as $item){
        $labels[] = $item->name;
        $data[] = $item->qty;
      }
    }

    $resp = ['data'=>$data, 'labels'=>$labels];
    $this->jsonResponse($resp);
  }
}
