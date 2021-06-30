<?php $this->setSiteTitle('Completed Orders')?>
<?php $this->start('body')?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th>#</th>
      <th>Created At</th>
      <th>Name</th>
      <th>Success</th>
      <th>Reason</th>
      <th>Charge ID</th>
      <th>Cart ID</th>
      <th>Card Brand</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
      <?php foreach($this->transactions as $transactions): ?>
        <tr>
      <td><?=$transactions->id?></td>
      <td><?=$transactions->created_at?></td>
      <td><?=$transactions->name?></td>
      <td><?=$transactions->success?></td>
      <td><?=$transactions->reason?></td>
      <td><?=$transactions->charge_id?></td>
      <td><?=$transactions->cart_id?></td>
      <td><?=$transactions->card_brand?></td>
      <td>
        <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminsales/restore/<?=$transactions->id?>"><i class="fas fa-recycle"></i>Restore</a>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php $this->end()?>
