<?php
use App\Models\{Menu};
use Core\{H,FH};
?>
<?php $this->setSiteTitle('Create Menu');?>
<?php $this->start('body');?>

<div class="container">
  <div class="row">
    <div class="col-md-6">
        <form class="card-header row border" action="<?=PROOT?>menu/add" method="post">
          <?= FH::csrfInput();?>
          <legend>Add Category</legend>
     				<label for="parent">Parent</label>
     				<select class="form-control col-md-11" name="parent" id="parent">
              <option value="0"<?=(($this->menu->parent_id == 0)?' selected="selected"':''); ?>>Parent</option>
     					<?php foreach($this->menuItems as $items):?>
     						<option value="<?=$items->id;?>"<?=(($items->parent_id == $items->id)?' selected="selected"':''); ?>><?=$items->category?></option>
     					<?php endforeach; ?>
     				</select>
            <br><br>
          <?= FH::inputBlock('text','Category','category',$this->menu->category,['class'=>'form-control'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          <div class="row">
            <div class="col-md-12 text-right">
              <a href="<?=PROOT?>admindashboard" class="btn btn-large btn-secondary">Cancel</a>
              <?= FH::submitTag('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
            </div>
          </div>
        </form>
    </div>
    <div class="col-md-6">
      <div class="card-header row align-items-center border">
        <div class=""><h2>Menu Items</h2></div>
        <table class="table">
     			<thead class="table-dark">
     				<th>Category</th><th>Parent</th><th></th>
     			</thead>
     			<tbody>
           <?php $headMenus = Menu::findHeadMenuItems();
           foreach($headMenus as $headMenu):
              $subMenus = Menu::findSubMenuItems($headMenu->id);
             ?>
     				<tr class="table-secondary">
     					<td><?=$headMenu->category?></td>
     					<td>Parent</td>
     					<td>

     						<a href="<?=PROOT?>menu/edit/<?=$headMenu->id?>" class="btn btn-sm btn-secondary mr-1" data-toggle="tooltip" title="Edit"><span class="fas fa-edit"></span></a>
     						<a onclick="deleteMenu('<?=$headMenu->id?>');return false;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></a>
     					</td>
     				</tr>
            <?php
                foreach($subMenus as $subMenu):
                $thirdMenus = Menu::findThirdMenuItems($subMenu->id);
              ?>
     				<tr class="table-active">
     					<td><?=$subMenu->category?></td>
     					<td>Subcategory</td>
     					<td>

     						<a href="<?=PROOT?>menu/edit/<?=$subMenu->id?>" class="btn btn-sm btn-secondary mr-1" data-toggle="tooltip" title="Edit"><span class="fas fa-edit"></span></a>
     						<a onclick="deleteMenu('<?=$subMenu->id?>');return false;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class="fas fa-trash-alt"></span></a>
     					</td>
     				</tr>
            <?php
                foreach($thirdMenus as $thirdMenu):
                ?>
     				<tr class="table-light">
     					<td><?=$thirdMenu->category?></td>
     					<td>Third Category</td>
     					<td>
     						<a href="<?=PROOT?>menu/edit/<?=$thirdMenu->id?>" class="btn btn-sm btn-secondary mr-1" data-toggle="tooltip" title="Edit"><span class="fas fa-edit"></span></a>
     						<a onclick="deleteMenu('<?=$thirdMenu->id?>');return false;" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"><span class="fas fa-trash-alt"></span></a>
     					</td>
     				</tr>
          <?php endforeach; ?>
          <?php endforeach;?>
        <?php endforeach;?>
     			</tbody>
     		</table>
      </div>
    </div>
  </div>
</div>

<script>
function deleteMenu(id){
  if(confirm("Are you sure you want to delete this brand?")){
    jQuery.ajax({
      'url': '<?=PROOT?>menu/delete',
      'method' : "POST",
      'data' : {id:id},
      'success' : function(resp){
        if(resp.success){
          alertMsg("Brand Deleted",'success');
          jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          window.location.href = '<?=PROOT?>menu/index';
        } else {
          alertMsg("Something went wrong",'warning');
        }
      }
    });
  }
}
</script>
<?php $this->end() ?>
