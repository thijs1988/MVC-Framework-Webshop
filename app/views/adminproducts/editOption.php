<?php
use Core\FH;
?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-6 bg-light p-3">
    <h3 class="text-center"><?=$this->header?></h3><hr>
    <form class="form" action="" method="post">
      <?= FH::csrfInput() ?>
      <?= FH::inputBlock('text','Option Name','name',$this->option->name,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->errors) ?>
      <div class="d-flex justify-content-end">
        <a href="<?=PROOT?>adminproducts/options" class="btn btn-secondary mr-1">Cancel</a>
        <?= FH::submitTag('Save',['class'=>'btn btn-primary']) ?>
      </div>
    </form>
  </div>
</div>
<?php $this->end(); ?>
