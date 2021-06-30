<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1605110940 extends Migration {
    public function up() {
      $table = "products";
     $this->addIndex($table,'name');
     $this->addIndex($table,'price');
    }
  }
