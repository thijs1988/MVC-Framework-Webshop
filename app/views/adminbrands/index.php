<?php $this->start('body');?>
<div class="card bg-light col-md-6 offset-md-3">
  <div class="card-header row align-items-center">
    <div class="col"><h2>Brands</h2></div>
    <div class="ml-2 col text-right">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBrandForm">
        Add New Brand
      </button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover table-striped table-sm" id="brandsTable">
      <thead>
        <th>ID</th>
        <th>Brand Name</th>
        <th></th>
      </thead>
      <tbody>
        <?php foreach($this->brands as $brand): ?>
          <tr data-id="<?=$brand->id?>">
            <td><?=$brand->id?></td>
            <td><?=$brand->brandname ?></td>
            <td class="text-right">
              <button class="btn btn-sm btn-secondary mr-1" onclick="editBrand('<?=$brand->id?>')"><i class="fas fa-edit"></i></button>
              <a class="btn btn-sm btn-danger" href="#" onclick="deleteBrand('<?=$brand->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->partial('adminbrands','form'); ?>


<script>

  function editBrand(id){
    jQuery.ajax({
      url : '<?=PROOT?>adminbrands/getBrandById',
      method : "POST",
      data : {id:id},
      success : function(resp){
        if(resp.success){
          jQuery('#name').val(resp.brand.brandname);
          jQuery('#brand_id').val(resp.brand.id);
          jQuery('#addBrandForm').modal('show');
        } else {
          jQuery('#name').val('');
          jQuery('#brand_id').val('new');
        }
      }
    })
    console.log(id);
  }

  function deleteBrand(id){
    if(confirm("Are you sure you want to delete this brand?")){
      jQuery.ajax({
        'url': '<?=PROOT?>adminbrands/delete',
        'method' : "POST",
        'data' : {id:id},
        'success' : function(resp){
          if(resp.success){
            alertMsg("Brand Deleted",'success');
            jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          } else {
            alertMsg("Something went wrong",'warning');
          }
        }
      });
    }
  }
</script>
<?php $this->end(); ?>
