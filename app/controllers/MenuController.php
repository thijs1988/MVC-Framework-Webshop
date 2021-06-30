<?php
namespace App\Controllers;
use Core\{Controller,H,Router};
use App\Models\Menu;

class MenuController extends Controller {

  public function indexAction(){
    $this->view->setLayout('admin');
    $menu = new Menu();
    $this->view->menu = $menu;
    $this->view->displayErrors = $menu->getErrorMessages();
    $this->view->menuItems = Menu::findMenuItems();
    $this->view->render('menu/index');
  }

  public function addAction(){
    $menu = new Menu();
    if($this->request->isPost()){
    $this->request->csrfCheck();
    $id = $this->request->get('parent');
    $newCategory = $this->request->get('category');
    Menu::saveMenuItem($id,$newCategory,$menu);
    }
  }

  public function editAction($id){
  $menu = Menu::getIdByParentId($id);
  $this->view->menu = $menu;
  $this->view->displayErrors = $menu->getErrorMessages();
  $this->view->menuItems = Menu::findMenuItems();
  if($this->request->isPost()){
      $this->request->csrfCheck();
      $id = $this->request->get('parent');
      $newCategory = $this->request->get('category');
      Menu::saveMenuItem($id,$newCategory,$menu);
    }
  $this->view->render('menu/edit');
  }

  public function deleteAction(){
    if($this->request->isPost()){
      $id = (int)$this->request->get('id');
      $menu = Menu::findMenuItemById($id);
      $resp = ['success'=>false];
        if ($menu){
          $menu->delete();
          $resp['success'] = true;
          $resp['model_id'] = $id;
        }
        $this->jsonResponse($resp);
    }
  }
}
