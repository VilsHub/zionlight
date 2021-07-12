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
use vilshub\http\Request;
class CSRF
{
    private static function generateToken($type, $name=null){
        if($type == "session"){            
            if(!Session::exist($name)){
                $uniqueID = md5(uniqid());
                $expire   = time()+ 60*60*24*3;
                Session::set($name, $uniqueID);
                Cookie::set($name, $uniqueID, ["httponly" => true, "expires" => $expire, "path" => "/"]);
            }
        }else{//generate for node
            $uniqueID = md5(uniqid());
            Session::set($name, $uniqueID);
        }
    }
    private static function checkSessionToken($name){
        if(Cookie::exist($name)){
            if(Cookie::get($name) === Session::get($name)){
                return true;
            }else{
                return false;
            }
        }
    }
    private static function checkNodeToken($name){
        $tokenValue = "";
        switch (Request::$method) {
            case 'POST':
                if(isset($_POST[$name])) $tokenValue = $_POST[$name];
                break;
            case 'GET':
                if(isset($_GET[$name])) $tokenValue = $_GET[$name];
                break;
            default:
                # code...
                break;
        }
        if(Session::exist($name)){
            if($tokenValue === Session::get($name)){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function generateSessionToken($name){
        global $config;
       
        if(!Cookie::exist($name)){
           self::generateToken("session", $name);
        }else{
            //validate
            
            if(Cookie::get($name) !== Session::get($name)){
                self::generateToken("session", $name);
            }
        }
    }

    public static function generateNodeToken($name){
        self::generateToken("node", $name);
    }

    public static function validateToken($sessionTokenID, $nodeTokenID){
        if(self::checkSessionToken($sessionTokenID)){
            if(self::checkNodeToken($nodeTokenID)){
                self::generateNodeToken($nodeTokenID);
            }else{
                redirect("/error/419");
                exit;
            }
        }else{
            redirect("/error/419");
            exit;
        }
    }

    public static function getNodeToken($name){
        if(Session::exist($name)){
            return Session::get($name);
        }
    }

    public static function putNodeToken($name){
        if(Session::exist($name)){
            echo "<input name='{$name}' type='hidden' value='".self::getNodeToken($name)."'/>";
        }else{
            self::generateToken("node", $name);
            echo "<input name='{$name}' type='hidden' value='".self::getNodeToken($name)."'/>";
        }
    }
}