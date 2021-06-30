<?php
namespace App\Lib\Gateways;
use App\Lib\Gateways\AbstractGateway;
use Braintree\Gateway;
use Core\{H};
use App\Models\{Transactions, Carts};

class BraintreeGateway extends AbstractGateway{

  public static $gateway = 'braintree';

  public function getView(){
    return 'card_forms/braintree';
  }

  public function getToken(){
      $gw = $this->getGateway();
      $token = $gw->clientToken()->generate();
      return $token;
    }

    public function processForm($post){
      $ch = $this->charge($post);
      $this->handleChargeResp($ch);
      $tx = $this->createTransaction($ch);
      if($this->chargeSuccess){
        Carts::purchaseCart($this->cart_id);
      }
      return ['success'=>$this->chargeSuccess,'msg'=>$this->msgToUser,'tx'=>$tx,'charge_id'=>$ch->id];
    }

    public function charge($data){
      $gw = $this->getGateway();
      $result = $gw->transaction()->sale([
        'amount' => $this->grandTotal,
        'paymentMethodNonce' => $data['payment_method_nonce'],
        'shipping' => $this->parseShippingAddress($data),
        'options' => ['submitForSettlement'=>true]
        //'customFields' => ['cart_id'=>$this->cart_id]
      ]);
      return $result;
    }

    public function handleChargeResp($ch){
      $this->chargeSuccess = $ch->success;
      $this->msgToUser = $ch->transaction->processorResponseText;
    }

    public function createTransaction($ch){
      $tx = new Transactions();
      $tx->cart_id = $this->cart_id;
      $tx->gateway = static::$gateway;
      $tx->type = $ch->transaction->paymentInstrumentType;
      $tx->amount = $this->grandTotal;
      $tx->success = ($this->chargeSuccess)? 1 : 0;
      $tx->charge_id = $ch->transaction->id;
      $tx->reason = $ch->transaction->gatewayRejectionReason;
      $tx->card_brand = $ch->transaction->creditCard['cardType'];
      $tx->last4 = $ch->transaction->creditCard['last4'];
      $tx->name = $ch->transaction->shipping['company'];
      $tx->shipping_address1 = $ch->transaction->shipping['streetAddress'];
      $tx->shipping_address2 = $ch->transaction->shipping['extendedAddress'];
      $tx->shipping_city = $ch->transaction->shipping['locality'];
      $tx->shipping_state = $ch->transaction->shipping['region'];
      $tx->shipping_zip = $ch->transaction->shipping['postalCode'];
      $tx->shipping_country = $ch->transaction->shipping['countryName'];
      $tx->save();
      return $tx;
    }

    protected function parseShippingAddress($data){
      return [
        'company' => $data['name'],
        'streetAddress' => $data['shipping_address1'],
        'extendedAddress' => $data['shipping_address2'],
        'locality' => $data['shipping_city'],
        'region' => $data['shipping_state'],
        'postalCode' => $data['shipping_zip']
      ];
    }

    protected function getGateway(){
      $gw = New Gateway([
        'environment' => BRAINTREE_ENV,
        'merchantId' => BRAINTREE_MERCHANT_ID,
        'publicKey' => BRAINTREE_PUBLIC,
        'privateKey' => BRAINTREE_PRIVATE
      ]);
      return $gw;
    }



}
