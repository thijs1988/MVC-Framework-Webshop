<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\Users;
  use Core\H;
  use Core\Migration;

  class HomeController extends Controller {

    public function indexAction() {
      $mig = new Migration();
      $mig->createTable("test");
      $this->view->render('home/index');
    }
  }
