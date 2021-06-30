<?php
namespace App\Models;
use Core\{DB,Model,H,Router};


class Menu extends Model{
  protected static $_table = 'menu';
  protected static $_softDelete = true;

  public $id,$category,$parent_id,$child_id,$link;

  public static function findMenuItems(){
   return self::find([
     'columns' => 'id, category, parent_id, link',
     'order' => 'id'
   ]);
  }

  public static function findHeadMenuItems(){
   return self::find([
     'conditions' => 'parent_id = ?',
     'bind' => [0]
   ]);
  }

  public static function findSubMenuItems($id){
   return self::find([
     'conditions' => 'parent_id = ?',
     'bind' => [$id]
   ]);
  }

  public static function findThirdMenuItems($id){
   return self::find([
     'conditions' => 'child_id = ?',
     'bind' => [$id]
   ]);
  }

  public static function getIdByParentId($id){
    if($id != 0){
    return self::findfirst([
      'conditions' => 'id = ?',
      'bind' => [$id]
    ]);
  }else{
    return $id;
  }
  }

  public static function findMenuItemById($id){
   return self::findFirst([
     'conditions' => 'id = ?',
     'bind' => [$id]
   ]);
  }

  public static function buildTree(array $elements, $parentId = 0) {

    $branch = array();

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = Menu::buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }
    return $branch;
    }

    public static function convertToObject($jason_array) {
    $object = (object)[];
    foreach ($jason_array as $key => $value) {
        if (is_array($value)) {
            $value = Menu::convertToObject($value);
        }
        $object->$key = $value;
    }
    return $object;
    }

    public static function createJsonAry(){

    $jason_array = array();
    $headMenus = Menu::findHeadMenuItems();
    foreach($headMenus as $headMenu):
      $subMenus = Menu::findSubMenuItems($headMenu->id);
      if(isset($headMenu->link)){
        $jason_array[] = array($headMenu->category => $headMenu->link);
      }
      //H::dnd($jason_array);
    foreach($subMenus as $subMenu):
     $jason_array[] = array($headMenu->category => array($subMenu->category));
      // if (!isset($subMenu->link)){
      // $jason_array[] = array($headMenu->category => $subMenu->category);
      // }
      if (isset($subMenu->link)){
        $jason_array[] = array($headMenu->category =>array($subMenu->category => $subMenu->link));
      }
      //H::dnd($jason_array);
       $thirdMenus = Menu::findThirdMenuItems($subMenu->id);
    foreach($thirdMenus as $thirdMenu):
      if (isset($thirdMenu->link)){
        $jason_array[] = array($headMenu->category => array($subMenu->category =>array($thirdMenu->category => $thirdMenu->link)));
      }
    endforeach;
    endforeach;
    endforeach;
  }

  public static function saveMenuItem($id,$newCategory,$menu){
    $menuAry = Menu::getIdByParentId($id);
    if($menuAry == 0){
      $menu->category = $newCategory;
      $menu->parent_id = $menuAry;
      $menu->child_id = null;
      $menu->deleted = 0;
      $menu->save();
    }elseif($menuAry != 0){
      if ($menuAry->parent_id == null){
        $menu->category = $newCategory;
        $menu->parent_id = $menuAry->id;
        $menu->child_id = null;
        $menu->deleted = 0;
        $menu->save();
      }elseif($menuAry->parent_id != null && $menuAry->parent_id != 0){
        $menu->category = $newCategory;
        $menu->parent_id = null;
        $menu->child_id = $menuAry->id;
        $menu->deleted = 0;
        $menu->save();
      }
    }
    $menu->link = "categories/index/$menu->id";
    $menu->save();
    Router::redirect('menu/index');
  }
}
