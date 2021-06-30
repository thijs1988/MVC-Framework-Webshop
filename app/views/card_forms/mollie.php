<?php
use Core\{FH,H,Session};
?>

<?php $this->setSiteTitle('Checkout'); ?>
<?php $this->start('body') ?>
<div class="row">
  <div class="col-md-8">
    <h3 class="offset-md-1">Purchase Detials</h3>

<form action="<?=PROOT?>cart/checkout/<?=$this->cartId?>" method="post" id="payment-form">
  <?php//FH::csrfInput()?>
  <input type="hidden" name="step" value="2"/>
  <?= FH::hiddenInput('name', $this->tx->name)?>
  <?= FH::hiddenInput('shipping_address1', $this->tx->shipping_address1)?>
  <?= FH::hiddenInput('shipping_address2', $this->tx->shipping_address2)?>
  <?= FH::hiddenInput('shipping_city', $this->tx->shipping_city)?>
  <?= FH::hiddenInput('shipping_state', $this->tx->shipping_state)?>
  <?= FH::hiddenInput('shipping_zip', $this->tx->shipping_zip)?>
  <div class="form-group col-md-12">
    <?php
    Session::set('name', $this->tx->name);
    Session::set('shipping_address1', $this->tx->shipping_address1);
    Session::set('shipping_address2', $this->tx->shipping_address2);
    Session::set('shipping_city', $this->tx->shipping_city);
    Session::set('shipping_state', $this->tx->shipping_state);
    Session::set('shipping_zip', $this->tx->shipping_zip);
    ?>
  <ul class="list-group list-group-flush offset-md-1">
  <li class="list-group-item">Subtotal: €<?=$this->subTotal?></li>
  <li class="list-group-item">Shipping: €<?=$this->shippingTotal?></li>
  <li class="list-group-item">Total: <strong>€<?=number_format($this->grandTotal,2)?></strong></li>
</ul>

  </div>
  <div class="col-md-12">
    <button class="btn btn-lg btn-primary offset-md-1">Submit Payment</button>
  </div>
</form>
</div>
<div class="col-md-4"><?php $this->partial('cart', 'product_preview')?></div>
</div>


<?php $this->end()?>
