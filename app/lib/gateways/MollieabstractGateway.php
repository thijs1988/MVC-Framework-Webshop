<?php
namespace App\Lib\Gateways;
use App\Models\Carts;

abstract class MollieabstractGateway{
  public $cart_id, $items, $itemCount=0, $subTotal=0, $shippingTotal=0, $grandTotal=0, $paymentDetails=[];
  public $chargeSuccess=false, $msgToUser='';

  public function populateItems($cart_id){
    $this->cart_id = $cart_id;
    $this->items = Carts::findAllItemsByCartId($cart_id);
    foreach($this->items as $item){
      $this->itemCount += $item->qty;
      $this->subTotal += ($item->price * $item->qty);
      $this->shippingTotal += ($item->shipping * $item->qty);
    }
    $this->grandTotal = $this->subTotal + $this->shippingTotal;
  }

  abstract public function getView();
  abstract public function processForm($post);
  abstract public function processForm2();
  abstract public function charge();
  abstract public function handleChargeResp($ch);
  abstract public function createTransaction();
  abstract public function getToken();


}
