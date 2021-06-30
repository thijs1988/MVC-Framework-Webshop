<?php
namespace App\Lib\Gateways;
use App\Lib\Gateways\MollieabstractGateway;
use App\Models\{Transactions, Carts, UserSessions};
use Mollie\{Initialize};
use Core\{H,Session,Router};

class MollieGateway extends MollieabstractGateway{

  public static $gateway = 'mollie';

  public function getView(){
    return 'card_forms/mollie';
  }

  public function processForm($post){
    require ROOT."/vendor/mollie/mollie-api-php/examples/initialize.php";
    $tx = $this->createTable();

    Session::set('tx', $tx);
    $ch = $this->charge();
    Session::set('ch', $ch);
  }

  public function processForm2(){
      $charge = Session::get('charge');
      $this->handleChargeResp($charge);
      $tx = $this->createTransaction($charge);
      if($this->chargeSuccess){
          Carts::purchaseCart($this->cart_id);
        }
        return ['success'=>$this->chargeSuccess,'msg'=>$this->msgToUser,'tx'=>$tx,'charge_id'=>$charge->id];
    }

  public function createTable(){
    $tx = new Transactions();
    $tx->cart_id = $this->cart_id;
    $tx->amount = $this->grandTotal;
    $tx->save();
    return $tx;
  }

  public function charge(){
    try {
      $mollie = new \Mollie\Api\MollieApiClient();
      Session::set('mollie', $mollie);
      $mollie->setApiKey(MOLLIE_TEST_KEY);

         if ($_SERVER["REQUEST_METHOD"] != "POST") {
             $method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);

             echo '<div class="form-group col-md-6">Select your bank: <select name="issuer">';

             foreach ($method->issuers() as $issuer) {
                 echo '<option value=' . htmlspecialchars($issuer->id) . '>' . htmlspecialchars($issuer->name) . '</option>';
             }

             echo '<option value="">or select later</option>';
             echo '</select><button>OK</button></div>';
             exit;
         }

         $orderId = time();
         Session::set('orderId', $orderId);
         $tx = Session::get('tx');

         $grandTotal = number_format($this->grandTotal, 2);
         $grandTotalString = str_replace(",", "", $grandTotal);

         $charge = $mollie->payments->create([
           "amount" => [
             "currency" => "EUR",
             "value" => "{$grandTotalString}"
           ],
           "description" => "{$orderId}",
           'redirectUrl' => "https://blue-robin-8.loca.lt/mvc-framework/cart/thankYou/{$tx->id}",
           'webhookUrl' => 'https://blue-robin-8.loca.lt/mvc-framework/cart/webhook',
           'method'      => \Mollie\Api\Types\PaymentMethod::IDEAL,
           "metadata" => [
               "order_id" => $orderId,
               "cart_id" => $this->cart_id,
           ],
           'issuer'      => !empty($_POST["issuer"]) ? $_POST["issuer"] : null
         ]);
         Session::set('cart_id', $this->cart_id);
         $issuer = $_POST['issuer'];
         Session::set('issuer', $issuer);
         Session::set('charge', $charge);

         database_write($orderId, $charge->status);
         Session::set('chargeId', $charge->id);

         header("Location: " . $charge->getCheckoutUrl(), true, 303);
         } catch (\Mollie\Api\Exceptions\ApiException $e) {
         echo "API call failed: " . htmlspecialchars($e->getMessage());
         }
  }

  public function handleChargeResp($charge){
    require ROOT."/vendor/mollie/mollie-api-php/examples/initialize.php";
    $mollie = Session::get('mollie');
    $orderId = Session::get('orderId');
    $chargeId = Session::get('chargeId');
    $this->cart_id = Session::get('cart_id');
    $mollie->setApiKey(MOLLIE_TEST_KEY);
    $payment = $mollie->payments->get($chargeId);
    $this->paymentDetails = $payment->details;
    $this->chargeSuccess = $payment->isPaid();
    $this->msgToUser = $payment->status;

  }

  // protected function parseShippingAddress($post){
  //    return [
  //      'line1' => $post['shipping_address1'],
  //       'line2' => $post['shipping_address2'],
  //       'city' => $post['shipping_city'],
  //       'state' => $post['shipping_state'],
  //       'postal_code' => $post['shipping_zip']
  //    ];
  //  }

   public function createTransaction(){
     $tx = Session::get('tx');
     if($this->chargeSuccess != true){
       $tx->success = ($this->chargeSuccess)? 1 : 0;
       $tx->save();
       return $tx;
     }
     $name = Session::get('name');
     $charge = Session::get('charge');
     $issuer = Session::get('issuer');
     $tx->name = $name;
     $tx->shipping_address1 = Session::get('shipping_address1');
     $tx->shipping_address2 = Session::get('shipping_address2');
     $tx->shipping_city = Session::get('shipping_city');
     $tx->shipping_state = Session::get('shipping_state');
     $tx->shipping_zip = Session::get('shipping_zip');
     $tx->country = $charge->countryCode;
     $tx->gateway = $charge->method;
     $tx->type = $charge->mode;
     $tx->success = ($this->chargeSuccess)? 1 : 0;
     $tx->charge_id = $charge->id;
     $tx->reason = $issuer;
     $tx->card_brand = $this->paymentDetails->consumerBic;
     $tx->save();
     return $tx;
   }

   public function getToken(){
     return false;
   }

}
