<?php
/**
 *
 */

use vilshub\helpers\Message;
use vilshub\helpers\Style;
use vilshub\validator\Validator;
use Dice\Dice;

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
        if(substr_count($target, ".php") > 0) $target  = rtrim($target, ".php");
        
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadModel(x)", "black")." method argument must be a string";
        Validator::validateString($target, Message::write("error", $msg1));        

        $className  = ucwords($target).$this->appConfig->modelFileSuffix;
        $targetFile = $this->appConfig->modelsDir."/".$className.".php";

        $msg2 = "The specified model file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
                
        require_once($targetFile);
        $dice = new Dice;
        $buildClass = $dice->create($className);
        return  $buildClass;
    }
    public function loadQueriesBank($target){
        if(substr_count($target, ".php") > 0) $target  = rtrim($target, ".php");
        
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadQueryBank(x)", "black")." method argument must be a string";
        Validator::validateString($target, Message::write("error", $msg1));

        $className  = ucwords($target).$this->appConfig->queryFileSuffix;
        $targetFile = $this->appConfig->queriesBankDir."/".$className.".php";
        

        $msg2 = "The specified query file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        require_once($targetFile);

        $dice = new Dice;
        $buildClass = $dice->create($className);
        return $buildClass;
    }
    public function loadPage($targetPage, $data=null){
        if ($data != null) extract($data);
        if(substr_count($targetPage, ".php") > 0) $targetPage  = rtrim($targetPage, ".php");
        
        $content = require_once($this->appConfig->XHRContentDir.$targetPage.".php");
        return rtrim($content, "1");
    }
    public function loadMiddleware($middlewareName){
        if(substr_count($middlewareName, ".php") > 0) $middlewareName  = rtrim($middlewareName, ".php");
        
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadMiddleware(x)", "black")." method argument must be a string";
        Validator::validateString($middlewareName, Message::write("error", $msg1));

        $className  = ucwords($middlewareName);
        $targetFile = $this->appConfig->middlewaresDir."/".$className.".php";
        

        $msg2 = "The specified middleware file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        require_once($targetFile);
        $dice = new Dice;
        $buildClass = $dice->create($className);
        return $buildClass;
    }
    public function loadService($serviceName){
        if(substr_count($serviceName, ".php") > 0) $serviceName  = rtrim($serviceName, ".php");
        
        //Validate argument
        $msg1 = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("loadMiddleware(x)", "black")." method argument must be a string";
        Validator::validateString($serviceName, Message::write("error", $msg1));

        $className  = ucwords($serviceName).$this->appConfig->serviceFileSuffix;
        $targetFile = $this->appConfig->servicesDir."/".$className.".php";
        

        $msg2 = "The specified service file:".Style::color($targetFile, "black").", does not exist";
        Validator::validateFile($targetFile, Message::write("error", $msg2));
        
        require_once($targetFile);
        $dice = new Dice;
        $buildClass = $dice->create($className);
        return $buildClass;
    }

}