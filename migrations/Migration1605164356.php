<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1605164356 extends Migration {
    public function up() {
    $table = "options";
    $this->createTable($table);
    $this->addColumn($table,'name','varchar',['size'=>155]);
    $this->addSoftDelete($table);

    $table = "product_option_refs";
    $this->createTable($table);
    $this->addTimeStamps($table);
    $this->addColumn($table,'product_id','int');
    $this->addColumn($table,'option_id','int');
    $this->addColumn($table,'inventory','int');
    $this->addIndex($table,'product_id');
    $this->addIndex($table,'option_id');
    $this->addIndex($table,'inventory');

    $table = "products";
    $this->addColumn($table,'has_options','tinyint',['after'=>'featured']);
    $this->addColumn($table,'inventory','int',['after'=>'featured']);
    $this->addIndex($table,'has_options');
    $this->addIndex($table,'inventory');

    $table = "cart_items";
    $this->addColumn($table,'option_id','int',['after'=>'qty']);
    $this->addIndex($table,'option_id');
    }
  }
