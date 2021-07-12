<?php
/**
 *
 */

use vilshub\helpers\Message;
use vilshub\helpers\Style;
use vilshub\validator\Validator;


/**
  *
  */
class Loader
{
    function __construct(){
        global $config;
        $this->appConfig = $config;
    }
    public function loadModel($target){
        $target  = rtrim($target, ".php");
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadModel(x)", "black")." method argument must be a string";
        Validator::validateString($target, Message::write("error", $msg1));
        
        

        $className  = $target.$this->appConfig->modelFileSuffix;
        $targetFile = $this->appConfig->modelsDir."/".$className.".php";

        $msg2 = "The specified model file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        $classNameVariable  = "className";
        
        require_once($targetFile);
        return new $$classNameVariable();
    }
    public function loadQueryBank($target){
        $target  = rtrim($target, ".php");
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadQueryBank(x)", "black")." method argument must be a string";
        Validator::validateString($target, Message::write("error", $msg1));

        $className  = $target.$this->appConfig->queryFileSuffix;
        $targetFile = $this->appConfig->queriesDir."/".$className.".php";
        

        $msg2 = "The specified query file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        $classNameVariable  = "className";
        require_once($targetFile);
        return new $$classNameVariable();
    }
    public function loadPage($targetPage, $data=null){
        if ($data != null) extract($data);
        $targetPage  = rtrim($targetPage, ".php");
        $content = require_once($this->appConfig->XHRContentDir.$targetPage.".php");
        return rtrim($content, "1");
    }
}