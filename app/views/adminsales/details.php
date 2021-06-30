<?php
use Core\{H};
?>
<?php $this->setSiteTitle('Transaction Details') ?>
<?php $this->start('body') ?>
<h2 class="text-center">Cart Items: <span style="color:lightgrey"><?=$this->transaction->name?></span> </h2><hr>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Quantity</th>
      <th>User ID</th>
      <th>Price</th>
      <th>Shipping</th>
      <th>Inventory</th>
    </tr>
  </thead>
  <tbody>
      <?php foreach($this->items as $item):  ?>
      <tr>
      <td><?=$item->id?></td>
      <td><?=$item->name?></td>
      <td><?=$item->qty?></td>
      <td><?=$item->user_id?></td>
      <td><?=$item->price?></td>
      <td><?=$item->shipping?></td>
      <td><?=$item->inventory?></td>
    </tr>
    <?php endforeach;?>
</tbody>
</table>
<br>
<br>
<div class="col-md-4 offset-md-8">
  <h4>Shipping Address</h4>
  <p><strong>Name:</strong> <?=$this->transaction->name?></p>
  <p><strong>Address:</strong> <?=$this->transaction->shipping_address1?></p>
  <p><strong>City:</strong> <?=$this->transaction->shipping_city?></p>
  <p><strong>ZIP:</strong> <?=$this->transaction->shipping_zip?></p>
  <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminsales/complete/<?=$this->transaction->id?>"><i class="fas fa-check"></i>Complete</a>
  <a class="btn btn-sm btn-danger" href="#" onclick="deleteProduct('<?=$this->transaction->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
</div>

<script>
function deleteProduct(id){
  if(window.confirm("Are you sure you want to delete this order. It cannot be reversed..")){
   jQuery.ajax({
     url : '<?=PROOT?>adminsales/delete',
     method : "POST",
     data : {id : id},
     success : function(resp){
       var msgType = (resp.success)? 'success' : 'danger';
       if(resp.success){
         jQuery('tr[data-id="'+resp.model_id+'"]').remove();
         window.location.href = '<?=PROOT?>adminsales/index';
       }
       alertMsg(resp.msg, msgType);
     }
   });
}
}
</script>


<?php $this->end() ?>
