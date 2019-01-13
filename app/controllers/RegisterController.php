<?php

class RegisterController extends Controller {

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
    $this->load_model('Users');
    $this->view->setLayout('default');
  }

  public function loginAction() {
    $loginModel = new Login();
    // $loginModel->setCsrfCheck(true);
    if($this->request->isPost()) {
      $this->request->csrfCheck();
      // form validation
      $loginModel->assign($_POST);
      $loginModel->validator();
      if($loginModel->validationPassed()){
        $user = $this->UsersModel->findByUsername($_POST['username']);
        if($user && password_verify($loginModel->password, $user->password)) {
          $remember = (isset($_POST['remember_me']) && $loginModel->remember_me) ? true : false;
          $user->login($remember);
          Router::redirect('');
        }  else {
          $loginModel->addErrorMessage('username',"There is an error with your username or password.");
        }
      }
    }
    $this->view->login = $loginModel;
    $this->view->displayErrors = $loginModel->getErrorMessages();
    $this->view->render('register/login');
  }

  public function logoutAction() {
    if(Users::currentUser()) {
      Users::currentUser()->logout();
    }
    Router::redirect('register/login');
  }

  public function registerAction() {
    $newUser = new Users();
    $confirm = '';
    if($this->request->isPost()) {
      $this->request->csrfCheck();
      $newUser->assign($this->request->get());
      $newUser->setConfirm($this->request->get('confirm'));
      if($newUser->save()){
        Router::redirect('register/login');
      } else {
        $newUser->setConfirm('');
      }
    }
    $this->view->newUser = $newUser;
    $this->view->displayErrors = $newUser->getErrorMessages();
    $this->view->render('register/register');
  }
}
