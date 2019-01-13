<?php

class Session {

  public static function exists($name) {
    return (isset($_SESSION[$name])) ? true : false;
  }

  public static function get($name) {
    return $_SESSION[$name];
  }

  public static function set($name, $value) {
    return $_SESSION[$name] = $value;
  }

  public static function delete($name) {
    if(self::exists($name)) {
      unset($_SESSION[$name]);
    }
  }

  public static function uagent_no_version() {
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $regx = '/\/[a-zA-Z0-9.]+/';
    $newString = preg_replace($regx, '', $uagent);
    return $newString;
  }

/**
 * Creates a session message that can be displayed on next page extension_loaded
 * @method newMsg
 * @param  string $type can be info, warning, danger or success
 * @param  string $msg  The message you want to display.
 */
  public static function newMsg($type='info',$msg){
    $sessionName = 'alert-'.$type;
    self::set($sessionName,$msg);
  }

  public static function displayMsg(){
    $alerts = ['alert-info','alert-warning','alert-danger','alert-success'];
    $html = '';
    foreach($alerts as $alert){
      if(self::exists($alert)){
        $html .= '<div class="alert '.$alert.' alert-dismissible" role="alert">';
        $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        $html .= self::get($alert);
        $html .= '</div>';
        self::delete($alert);
      }
    }
    return $html;
  }
}
