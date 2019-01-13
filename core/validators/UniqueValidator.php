<?php
class UniqueValidator extends CustomValidator{

  public function runValidation(){
    $field = (is_array($this->field))? $this->field[0] : $this->field;
    $value = $this->_model->{$field};
    $conditions = ["{$field} = ?"];
    $binds = [$value];
    // the next check allows you to update a record
    if(!empty($this->_model->id)){
      $conditions[] = "id != ?";
      $binds[] = $this->_model->id;
    }
    // this allows you have multiple checks for unique.
    if(is_array($this->field)){
      array_unshift($this->field);
      foreach($this->field as $adds){
        $conditions[] = "{$adds} = ?";
        $binds[] = $this->_model->{$adds};
      }
    }
    $queryParams = [
      'conditions' => $conditions,
      'bind' => $binds
    ];

    $other = $this->_model->findFirst($queryParams);
    return (!$other);
  }
}
