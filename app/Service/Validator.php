<?php
Namespace Service;
class Validator {
  public static function ValidateCSRF($token){
    if(\F3::get('SESSION.tokenvalidated'))
      return false;

    \F3::set('SESSION.tokenvalidated', true);
    return ($token == md5(\F3::get('SESSION.token')));
  }
  public static function GetCSRF(){
    if(!\F3::get('SESSION.tokenvalidated'))
      return  md5(\F3::get('SESSION.token'));
    $x = new \Session();
    \F3::set('SESSION.token', $x->csrf());
    \F3::set('SESSION.tokenvalidated', false);
    return md5($x->csrf());
  }

}
