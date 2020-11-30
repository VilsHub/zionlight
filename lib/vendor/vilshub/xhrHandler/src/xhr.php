<?php
  namespace vilshub\xhrHandler;
  use vilshub\helpers\message;
  use \Exception;
  use vilshub\helpers\get;
  use vilshub\helpers\style;
  use vilshub\helpers\textProcessor;
  use vilshub\dataParser\parse;
  /**
   *
   */
   /**
    *
    */
   class xhr
   {
    function __construct(){
    }

    public static function postData($inputName){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
          if(isset($_POST[$inputName])){
            return $_POST[$inputName];
          }elseif(isset($_FILES[$inputName])){
            return $_FILES[$inputName];
          }else{
            return null;
          }
      }
    }

    public static function getData($inputName = null){
      if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(count($_GET) > 0){ //has data
          if(isset($_GET[$inputName])){
            return $_GET[$inputName];
          }else{
            return null;
          }
        }else{// has no data
          return $_SERVER;
        }
          
      }
    }

    public static function requestMethod($method = null){
      if($method == null){
        return $_SERVER["REQUEST_METHOD"];
      }else{
        return $_SERVER["REQUEST_METHOD"] == strtoupper($method);
      }      
    }
    public static function requestURI($URI = null){
      if($URI == null){
        return $_SERVER["REQUEST_URI"];
      }else{
        return $_SERVER["REQUEST_URI"] == $URI;
      }      
    }
   }
?>