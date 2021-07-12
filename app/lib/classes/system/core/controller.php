<?php
use vilshub\validator\Validator;
use vilshub\http\Request;
use vilshub\helpers\Message;
use vilshub\helpers\Style;

class Controller
{
    function __construct(){
        global $config;
        
        $this->appConfig    = $config;
        $this->loader       = new Loader();
        $this->xhr          = new Request();
        $this->session      = new Session();
        $this->validator    = new Validator();
    }
    public function middleWare($middleWareName, $data=null){
   
        $target  = rtrim($middleWareName, ".php");
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("middleWare(x.)", "black")." method argument must be a string";
        Validator::validateString($target, Message::write("error", $msg1));
        
        $targetFile = $this->appConfig->middleWaresDir."/".$target.".php";

        $msg2 = "The specified middleware file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        $classNameVariable  = "target";
       
        require_once($targetFile);
        return new $$classNameVariable($data);
    }
}
?>