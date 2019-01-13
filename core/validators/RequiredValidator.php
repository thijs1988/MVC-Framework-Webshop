<?php
class RequiredValidator extends CustomValidator{
  public function runValidation(){
    $pass = (!empty($this->_model->{$this->field}));
    return $pass;
  }
}
