<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1605165334 extends Migration {
    public function up() {
      $sql = "UPDATE products SET `has_options` = 0, `inventory` = 0";
      $this->query($sql);
    }
  }
