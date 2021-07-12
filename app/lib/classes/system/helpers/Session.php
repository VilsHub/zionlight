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
    public static function start($prependName = null, $config = null){
        if(session_status() === PHP_SESSION_NONE){
            $msg1 = "Invalid argument value, ".Style::color(__CLASS__."::", "black").Style::color("start(x)", "black")." method argument must be a string";
            if($prependName != null) Validator::validateString($prependName, Message::write("error", $msg1));
            $sessionID = $prependName == null?"ZLight":"ZLight_".$prependName;
            session_name($sessionID);
            $config != null ?session_start($config):session_start();
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
}