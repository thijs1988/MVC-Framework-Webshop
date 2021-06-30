<?php
namespace App\Controllers;
use Core\{View,Controller,H,Router};
use App\Models\{Users,Transactions,Carts,CartItems,Harddelete, Products};

class AdminsalesController extends Controller{

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){
    $transactions = Transactions::getTransactions();
    $this->view->transactions = $transactions;
    $this->view->render('adminsales/index');
  }

  public function detailsAction($id){
    $transaction = Transactions::findTransactionById($id);
    $items = Carts::findAllItemsByCartId($transaction->cart_id);
    $cartItems = CartItems::findCartItemsByCartId((int)$transaction->cart_id);
    $this->view->cartItems = $cartItems;
    $this->view->items = $items;
    $this->view->transaction = $transaction;
    $this->view->render('adminsales/details');
  }

  public function completedAction(){
    $transactions = Transactions::getDeletedTransactions();
    $this->view->transactions = $transactions;
    $this->view->render('adminsales/completed');
  }

  public function restoreAction($id){
    Transactions::findDeletedOrders($id);
    Router::redirect('adminsales/index');
  }

  public function completeAction($id){
    $tx = Transactions::findTransactionById($id);
    $tx->delete();
    Router::redirect('adminsales/completed');
  }

  public function profitAction(){
    $products = Products::findSoldProducts();
    $totals = Products::getSumProducts($products);
    $this->view->totals = $totals;
    $this->view->products = $products;
    $this->view->render('adminsales/profit');
  }

  public function deleteAction(){
    $resp = ['success' => false, 'msg'=>'something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $tx = Harddelete::findTransactionById($id);
      if($tx){
        Harddelete::hardDeleteItems($tx);
        $resp = ['success' => true, 'msg' => 'Product Deleted.', 'model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
    $this->view->render('adminsales/index');
  }
  public function yeartotalsAction(){
    $this->view->render('adminsales/yeartotals');
  }

  public function getYearTotalsAction(){
    $year = $this->request->get('year');
    $yearTotals = Transactions::getYearTotals($year);
    $labels = [];
    $data = [];
    foreach($yearTotals as $yearTotal){

      $labels[] = date('F, Y', strtotime($yearTotal->created_at));
      $data[] = $yearTotal->amount;
    }
    $resp = ['data'=>$data, 'labels'=>$labels];
    $this->jsonResponse($resp);
  }
}
