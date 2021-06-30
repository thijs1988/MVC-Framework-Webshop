<?php use Core\{FH,H}; ?>
<?php $this->setSiteTitle("Edit ".$this->product->name); ?>
<?php $this->start('head'); ?>
<script src="<?= PROOT?>vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
     tinymce.init({
       selector: '#body',
       branding: false
     });
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

  <style>
    .select2-container{
      display: block;
    }
  </style>

<script>
    function calcInventory(){
      var total = 0;
      var options = document.querySelectorAll('input.option_inventory');
      for(var i=0; i< options.length; i++){
        var option = options[i];
        total += parseInt(option.value,10);
      }
      document.getElementById('inventory').value = total;
    }

    $(document).ready(function(){
      calcInventory();

      //initialize select2
      $('.multi-options-select').select2({
        ajax : {
          url: '<?=PROOT?>adminproducts/getOptionsForForm',
          dataType: 'json',
          processResults : function(resp){
            return {results: resp.items}
          }
        }
      });

      //select option
      $('.multi-options-select').on('select2:select',function(e){
        var wrap = document.getElementById('optionsInventoryWrapper');
        var inputWrap = document.createElement('div');
        inputWrap.setAttribute('class','form-group');
        inputWrap.setAttribute('data-id',e.params.data.id);

        var label = document.createElement('label');
        label.setAttribute('class','control-label');
        label.setAttribute('for','inventory_'+e.params.data.id);
        var labelText = document.createTextNode(e.params.data.text +" Inventory");
        label.appendChild(labelText);
        inputWrap.appendChild(label);

        var input = document.createElement('input');
        input.setAttribute('class','form-control option_inventory');
        input.setAttribute('type','number');
        input.setAttribute('id','inventory_'+e.params.data.id);
        input.setAttribute('name','inventory_'+e.params.data.id);
        input.setAttribute('value','0');
        input.setAttribute('onblur','calcInventory()');
        inputWrap.appendChild(input);
        wrap.appendChild(inputWrap);
      });

      //unselect option
      $('.multi-options-select').on('select2:unselect',function(e){
        var wrap = document.querySelector('div[data-id="'+e.params.data.id+'"]');
        wrap.remove();
        calcInventory();
      });
    });
  </script>

<?php $this->end(); ?>
<?php $this->start('body') ?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-8 bg-light p-3">
    <h1 class="text-center"><?=$this->header?></h1>
    <form action="" method="POST" enctype="multipart/form-data">
      <?= FH::csrfInput();?>

      <div class="row">
      <?= FH::hiddenInput('images_sorted', '');?>
      <?= FH::inputBlock('text','Name','name',$this->product->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Price','price',$this->product->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','List Price','list',$this->product->list,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Shipping','shipping',$this->product->shipping,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Procurement','procurement',$this->product->procurement,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
      <?= FH::selectBlock('Brand','brand_id',$this->product->brand_id,$this->brands, ['class'=>'form-control input-sm'],['class'=>'form-group col-md-3'],$this->displayErrors) ?>
      <?php $invInputClass = ['class'=>'form-control input-sm'];
      if($this->product->hasOptions()){
          $invInputClass['readonly'] = 'readonly';
        }
      ?>
      <?=FH::inputBlock('number','Inventory','inventory',$this->product->inventory,$invInputClass,['class'=>'form-group col-md-2'],$this->displayErrors)?>
      </div>

      <div class="row">
        <?php
         foreach($this->menuItems as $menuItem): ?>
         <div class="form-check">
           <input class="form-check-input" name="cat_id[]" type="checkbox" value="<?=$menuItem->id?>" id="flexCheckDefault">
           <label class="form-check-label" for="flexCheckDefault"><?=$menuItem->category?></label>
         </div>
        <?php endforeach; ?>
      </div>

      <div class="row">
        <?= FH::textareaBlock('Body','body',$this->product->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
        <?= FH::checkboxBlock('Featured','featured',$value='on',$this->product->isChecked(),[], ['class'=>'form-group col-md-12'],$this->displayErrors)?>
        <?= FH::checkboxBlock('Has Options','has_options',$value='on',$this->product->hasOptions(),[],['class'=>'form-group col-md-12'],$this->displayErrors) ?>

      </div>

      <div class="row mb-3  <?= ($this->product->hasOptions())? 'd-flex': 'd-none'?>" id="optionsWrapper">
        <div class="col-6 form-group">
          <label class="control-label">Options</label>
          <select class="multi-options-select form-control" name="options[]" multiple="multiple">
            <?php foreach($this->options as $option):?>
              <option value="<?=$option->id?>" selected="selected"><?=$option->name?></option>
            <?php endforeach;?>
          </select>
      </div>

      <div class="col-6" id="optionsInventoryWrapper">
          <?php foreach($this->options as $option){
            echo FH::inputBlock('number',$option->name." Inventory",'inventory_'.$option->id,$option->inventory,['class'=>'form-control option_inventory','onblur'=>"calcInventory()"],['class'=>'form-group','data-id'=>$option->id]);
          } ?>
        </div>
      </div>

      <?php $this->partial('adminproducts', 'editImages') ?>

      <div class="row">
        <?= FH::inputBlock('file',"Upload ProductImages:",'productImages[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      </div>

      <div class="row">
        <div class="col-md-12 text-right">
          <a href="<?=PROOT?>adminproducts" class="btn btn-large btn-secondary">Cancel</a>
          <?= FH::submitTag('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('has_options').addEventListener('change',function(evt){
    var wrapper = document.getElementById('optionsWrapper');
    var inventory = document.getElementById('inventory');
    if(evt.target.checked){
      wrapper.classList.add('d-flex');
      wrapper.classList.remove('d-none');
      inventory.setAttribute('readonly',"readonly");
    } else {
      wrapper.classList.add('d-none');
      wrapper.classList.remove('d-flex');
      inventory.removeAttribute('readonly');
    }
  });
</script>
<?php $this->end() ?>
