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
class Session
{
    public static function start(){
        if(session_status() === PHP_SESSION_NONE){
            global $config;
            session_name("ZLight_".$config->appName);
            session_start(["cookie_lifetime" => $config->sessionExpiry]);
        }
       
    }
    
    public static function exist($id){
        if(isset($_SESSION[$id])){
            return true;
        }else{
            return false;
        }
    }

    public static function get($id){
        if(isset($_SESSION[$id])){
            return $_SESSION[$id];
        }else{
            return null;
        }
    }

    public static function set($name, $value){
        $_SESSION[$name] = $value;
    }

    public static function id(){
        return session_id();
    }

    public static function clean(){
        session_destroy();
    }
}