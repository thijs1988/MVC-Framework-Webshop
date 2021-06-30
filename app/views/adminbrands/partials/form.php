<?php
  use Core\FH;
  use Core\H;
?>
<div class="modal fade" id="addBrandForm" tabindex="-1" role="dialog" aria-labelledby="addBrandFormLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBrandFormLabel">Add Brand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="brandForm">
          <input type="hidden" id="brand_id" name="brand_id" value="new" />
          <?=FH::inputBlock('text','Brand Name','name',$this->brand->brandname,['class'=>'form-control'],['class'=>'form-group'],$this->formErrors);?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveBrand()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
  jQuery('#addBrandForm').on('hidden.bs.modal',function(){
    console.log("hallo");
    jQuery('#name').val('');
    jQuery('#brand_id').val('new');
  });

  function saveBrand(){
    var formData = jQuery('#brandForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminbrands/save',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Brand Successfully Saved",'success');
          jQuery('#addBrandForm').modal('hide');
          var row = jQuery('tr[data-id="'+resp.brand.id+'"]');
          var newRow = '<tr data-id="'+resp.brand.id+'"><td>'+resp.brand.id+'</td><td>'+resp.brand.name+'</td><td></td></tr>'
          if(row.length === 0){
            jQuery('#brandsTable tbody').prepend(newRow);
          } else {
            jQuery('tr[data-id="'+resp.brand.id+'"] td:nth-child(2)').text(resp.brand.name)
          }
          console.log(row);
        }
      }
    });
  }
</script>
