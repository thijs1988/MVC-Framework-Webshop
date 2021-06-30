<?php $this->start('body')?>
<div class="card bg-light col-md-6 offset-md-3">
  <div class="card-header row align-items-center">
    <div class="col"><h2>Product Options</h2></div>
    <div class="ml-2 col text-right">
      <a class="btn btn-primary" href="<?=PROOT?>adminproducts/editOption/new">Add New Option</a>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-striped table-sm">
      <thead>
        <th>ID</th>
        <th>Name</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach($this->options as $option): ?>
          <tr data-id="<?=$option->id?>">
            <td><?=$option->id ?></td>
            <td><?=$option->name ?></td>
            <td class="text-right">
              <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminproducts/editOption/<?=$option->id?>"><i class="fas fa-edit"></i></a>
              <a class="btn btn-sm btn-danger" href="#" onclick="deleteOption('<?=$option->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<script>
  function deleteOption(id){
    if(window.confirm("Are you sure you want to delete this option. It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminproducts/deleteOption',
        method : "POST",
        data : {id : id},
        success: function(resp){
          var msgType = (resp.success)? 'success' : 'danger';
          if(resp.success){
            jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          }
          alertMsg(resp.msg, msgType);
        }
      });
    }
  }

</script>
<?php $this->end(); ?>
