<?php
  namespace Core;
  use Core\Application;

  class Controller extends Application {
    protected $_controller, $_action;
    public $view, $request;

    public function __construct($controller, $action) {
      parent::__construct();
      $this->_controller = $controller;
      $this->_action = $action;
      $this->request = new Input();
      $this->view = new View();
      $this->onConstruct();
    }

    /**
     * Called when a Controller object is constructed
     * @method onConstruct
     */
    public function onConstruct(){}

  }
