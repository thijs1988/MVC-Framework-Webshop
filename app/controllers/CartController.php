<?php
  namespace App\Controllers;
  use Core\{Controller,H,Cookie,Session,Router};
  use App\Models\{Products, Carts, CartItems, Transactions};
  use App\Lib\Gateways\Gateway;
  use Stripe\{Stripe,Charge};

  class CartController extends Controller {

    public function indexAction() {
      $cart_id = (Cookie::exists(CART_COOKIE_NAME))? Cookie::get(CART_COOKIE_NAME) : false;
      $itemTotals = CartItems::getItemTotals($cart_id);
      $subTotal = $itemTotals[1];
      $shippingTotal = $itemTotals[0];
      $itemCount = $itemTotals[2];
      $items = $itemTotals[3]; 
      $this->view->subTotal = number_format($subTotal,2);
      $this->view->shippingTotal = number_format($shippingTotal,2);
      $this->view->grandTotal = number_format($subTotal + $shippingTotal, 2);
      $this->view->itemCount = $itemCount;
      $this->view->items = $items;
      $this->view->cartId = $cart_id;
      $this->view->render('cart/index');
    }

    public function addToCartAction($product_id){
      if($this->request->isPost()){
      $this->request->csrfCheck();
      $cart = Carts::findCurrentCartOrCreateNew();
      $item = CartItems::addProductToCart($cart->id, (int)$product_id,(int)$this->request->get('option_id'));
      $errors = $item->getErrorMessages();
      if(empty($errors)){
        $item->qty = $item->qty +1;
        $item->save();
      } else {
        Session::addMsg('danger', $errors['option_id']);
        Router::redirect('products/details/'.$product_id);
      }

      $this->view->render('cart/addToCart');
      }
    }

    public function changeQtyAction($direction, $item_id){
      $item = CartItems::findById((int) $item_id);
      if($direction == 'down'){
        $item->qty -= 1;
      }else{
        $item->qty += 1;
      }

      if($item->qty > 0){
        $item->save();
      }
      Session::addMsg('info', "Cart Updated");
      Router::redirect('cart');
    }

    public function removeItemAction($item_id){
      $item = CartItems::findById((int)$item_id);
      $item->delete();
      Session::addMsg('info', "Cart Updated");
      Router::redirect('cart');
    }

    public function checkoutAction($cart_id){
      $itemTotals = CartItems::getItemTotals($cart_id);
      $this->view->subTotal = number_format($itemTotals[1],2);
      $this->view->shippingTotal = number_format($itemTotals[0],2);
      $whitelist = ['name', 'shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_zip' ];
      $gw = Gateway::build();
      $gw->populateItems((int)$cart_id);
      $tx = new Transactions();
      if(GATEWAY == 'stripe' || GATEWAY == 'braintree'){
      if($this->request->isPost()){
        $whitelist = ['name', 'shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_zip' ];
        $this->request->csrfCheck();
        $tx->assign($this->request->get(), $whitelist, false);
        $tx->validateShipping();
        $step = $this->request->get('step');
        if($step == '2'){
        $resp = $gw->processForm($this->request->get());
        $tx = $resp['tx'];
        if($resp['success'] != true){
          $tx->addErrorMessage('card-element', $resp['msg']);
        }else{
          Router::redirect('cart/thankYou/'. $tx->id);
        }
      }
      }
      $this->view->gatewayToken = $gw->getToken();
      $this->view->formErrors = $tx->getErrorMessages();
      $this->view->tx = $tx;
      $this->view->grandTotal = $gw->grandTotal;
      $this->view->items = $gw->items;
      $this->view->cartId = $cart_id;
      if(!$this->request->isPost() || !$tx->validationPassed()){
        $this->view->render('cart/shipping_address_form');
      }else{
        $this->view->render($gw->getView());
      }
    }elseif(GATEWAY == 'mollie'){
      if($this->request->isPost()){
        $whitelist = ['name', 'shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_zip' ];
        $this->request->csrfCheck();
        $tx->assign($this->request->get(), $whitelist, false);
        $tx->validateShipping();
        $step = $this->request->get('step');
        if($step == '2'){
        $gw->processForm($this->request->get());
      }
      }

      $this->view->gatewayToken = $gw->getToken();
      $this->view->formErrors = $tx->getErrorMessages();
      $this->view->tx = $tx;
      $this->view->grandTotal = $gw->grandTotal;
      $this->view->items = $gw->items;
      $this->view->cartId = $cart_id;
      if(!$this->request->isPost() || !$tx->validationPassed()){
        $this->view->render('cart/shipping_address_form');
      }else{
        $this->view->render($gw->getView());
      }

    }
    }

    public function thankYouAction($tx_id){
      $tx = Transactions::findById((int)$tx_id);
      if(GATEWAY == 'mollie'){
      $gw = Gateway::build();
      $resp = $gw->processForm2();
      $tx = $resp['tx'];
      if($resp['success'] != true){
        $tx->delete();
        Session::addMsg('danger', 'something went wrong please make sure you put in the right details and try agian..');
        Router::redirect('cart');
      }
      }
      $this->view->tx = $tx;
      $this->view->render('cart/thankYou');
    }

    public function webhookAction(){
      $this->view->render('cart/webhook');
    }
  }
