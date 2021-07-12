<?php
/**
 *
 */
  /**
  *
  */
use vilshub\helpers\Message;
use vilshub\helpers\Style;
use vilshub\validator\Validator;
class Cookie
{
    public static function set($name, $value, $options){
        setcookie($name, $value, $options);
    }

    public static function exist($name){
        if(isset($_COOKIE[$name])){
            return true;
        }else{
            return false;
        }
    }

    public static function get($name){
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }else{
            return null;
        }
    }
}