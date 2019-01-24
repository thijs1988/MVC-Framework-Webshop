<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\Users;
  use Core\H;

  class HomeController extends Controller {

    public function indexAction() {
      $user = Users::currentUser();
      $user->username = 'curtis';
      $user->save();
      $this->view->render('home/index');
    }
  }
