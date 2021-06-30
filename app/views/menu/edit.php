<?php
use App\Models\{Menu};
use Core\{H,FH};
?>
<?php $this->setSiteTitle('Create Menu');?>
<?php $this->start('body');?>

<div class="container">
  <div class="row">
    <div class="col-md-6">
        <form class="card-header row border" action="<?=PROOT?>menu/edit/<?=$this->menu->id?>" method="post">
          <?= FH::csrfInput();?>
          <legend>Edit Category</legend>
     				<label for="parent">Parent</label>
     				<select class="form-control col-md-11" name="parent" id="parent">
              <option value="0"<?=(($this->menu->parent_id == 0)?' selected="selected"':''); ?>>Parent</option>
     					<?php foreach($this->menuItems as $items):?>
     						<option value="<?=$items->id;?>"<?=(($items->parent_id == $items->id)?' selected="selected"':''); ?>><?=$items->category?></option>
     					<?php endforeach; ?>
     				</select>
          <?= FH::inputBlock('text','Category','category',$this->menu->category,['class'=>'form-control'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>

          <div class="row">
            <div class="col-md-12 text-right">
              <a href="<?=PROOT?>admindashboard" class="btn btn-large btn-secondary">Cancel</a>
              <?= FH::submitTag('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
            </div>
          </div>
        </form>
    </div>
<?php $this->end(); ?>
