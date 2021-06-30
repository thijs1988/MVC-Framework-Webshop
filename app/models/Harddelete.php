<?php
namespace App\Models;
use Core\{Model,DB,H};

class Harddelete extends Model{

  protected static $_table = 'transactions';
  protected static $_softDelete = true;

  public static function findTransactionById($id){
    return self::findFirst([
      "conditions" => "id = ?",
      "bind" => [$id]
    ]);
  }

  public static function hardDeleteItems($tx){
    $tx->delete();
    return;
  }
}
