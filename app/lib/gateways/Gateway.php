<?php
namespace App\Lib\Gateways;
use App\Lib\Gateways\{StripeGateway};
use App\Lib\Gateways\{MollieGateway,MollieabstractGateway};

class Gateway {
  public static function build(){
    if(GATEWAY == 'stripe'){
      return new StripeGateway();
    }elseif(GATEWAY == 'braintree') {
      return new BraintreeGateway();
    }elseif (GATEWAY == 'mollie') {
      return new MollieGateway();
    }
  }
}
