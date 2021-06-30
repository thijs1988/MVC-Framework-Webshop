<?php $this->setSiteTitle('Profit From Orders') ?>
<?php $this->start('body') ?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Price</th>
      <th>Procurement</th>
      <th>Qty Sold</th>
      <th>Procurement Total</th>
      <th>Total Sold</th>
      <th>profit</th>
    </tr>
  </thead>
  <tbody>
      <?php foreach($this->products as $product): ?>
        <tr>
      <td><?=$product->id?></td>
      <td><?=$product->name?></td>
      <td><span style="color:rgb(48, 179, 74);">€<?=$product->price?></span></td>
      <td><span style="color:rgb(255, 42, 0);">€<?=$product->procurement?></span></td>
      <td><?=$product->qty?></td>
      <td><span style="color:rgb(255, 42, 0);">€<?=$product->qty*$product->procurement?></span></td>
      <td><span style="color:rgb(48, 179, 74);">€<?=$product->qty*$product->price?></span></td>
      <td><span style="color:rgb(48, 179, 74);">€<?=$product->qty*$product->price-$product->qty*$product->procurement?></span></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td><strong>Totals</strong></td>
      <td></td>
      <td></td>
      <td><span style="color:rgb(255, 42, 0);"><strong>€<?=$this->totals[0]?></strong></span></td>
      <td><strong><?=$this->totals[4]?></strong></td>
      <td><span style="color:rgb(255, 42, 0);"><strong>€<?=$this->totals[1]?></strong></span></td>
      <td><span style="color:rgb(48, 179, 74);"><strong>€<?=$this->totals[2]?></strong></span></td>
      <td><span style="color:rgb(48, 179, 74);"><strong>€<?=$this->totals[3]?></strong></span></td>
    </tr>
  </tbody>
</table>
<?php $this->end() ?>
