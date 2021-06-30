<h3>Purchase Summary</h3>
<?php foreach($this->items as $item):?>
 <div class="cart-preview-item">
   <div class="cart-preview-item-img"><img src="<?=PROOT . $item->url?>" alt="<?=$item->name?>" /></div>
   <div class="cart-preview-item-info">
     <p>
       <?=$item->name?>
       <?php if(!empty($item->option)): ?>
         <span> (<?=$item->option?>)</span>
      <?php endif; ?>
     </p>
     <p><?=$item->qty?> x €<?=$item->price?></p>
     <p>Shipping: €<?=$item->shipping?></p>
   </div>
 </div>
<?php endforeach; ?>
<div class="d-flex justify-content-between">
    <div class="text-bold">Total:</div>
    <div class="text-bold">€<?=number_format($this->grandTotal, 2)?></div>

</div>
