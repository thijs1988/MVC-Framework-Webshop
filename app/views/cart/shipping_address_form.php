<?php
use Core\FH;
?>

<?php $this->start('body') ?>
<div class="row">
  <div class="col-md-8">
    <h3>Purchase details</h3>
    <form action="<?=PROOT?>cart/checkout/<?=$this->cartId;?>"  method="post">
      <?=FH::csrfInput()?>
      <div class="row">
        <input type="hidden" name="step" value="1">
        <?=FH::inputBlock('input', 'Name', 'name', $this->tx->name, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'], $this->formErrors);?>
        <?=FH::inputBlock('input', 'Shipping Address', 'shipping_address1', $this->tx->shipping_address1, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'], $this->formErrors);?>
        <?=FH::inputBlock('input', 'Shipping Address (cont.)', 'shipping_address2', $this->tx->shipping_address2, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'], $this->formErrors);?>
        <?=FH::inputBlock('input', 'City', 'shipping_city', $this->tx->shipping_city, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-6'], $this->formErrors);?>
        <?=FH::inputBlock('input', 'State', 'shipping_state', $this->tx->shipping_state, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-3'], $this->formErrors);?>
        <?=FH::inputBlock('input', 'Zip', 'shipping_zip', $this->tx->shipping_zip, ['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-3'], $this->formErrors);?>
      </div>
      <button class="btn btn-lg btn-primary">Continue</button>
    </form>
  </div>
<div class="col-md-4"><?php $this->partial('cart', 'product_preview')?></div>
</div>

<?php $this->end();?>
