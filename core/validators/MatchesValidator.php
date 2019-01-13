<?php
class MatchesValidator extends CustomValidator{

  public function runValidation(){
    return ($this->_model->{$this->field} == $this->rule);
  }
}
