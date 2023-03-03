<?php
  use vilshub\helpers\Message;
  use vilshub\helpers\Get;
  use vilshub\helpers\Style;

  class ErrorHandler
  {
    public static function listenForErrors(){
      function listen($errorNumber, $errorMessage, $file, $lineNumber){
        ErrorHandler::throwError($errorNumber, $errorMessage, $file, $lineNumber);
      }
      set_error_handler('listen', E_USER_NOTICE);
    }
  
    public static function throwError($errorNumber, $errorMessage, $file, $lineNumber){
      function createTrail($trail){
        return "<div style='box-sizing: border-box; width:98%; padding:10px; margin:0 auto; border:solid 1px gray; background-color:black; color:white'>{$trail}</div>";
      }
      function getErrorDetails($custom=false){
        $output ="";
        $n = 0;
        $reversedTrail = array_reverse(debug_backtrace());
        if($custom){
          $output .= "EXECUTED IN FILE :<br/>";
        }else{
          $output .= "<br/>TRAIL: <br/>";
        }
    
        $color = "yellow";
        echo "<br/>";
        foreach ($reversedTrail as $error){
          if(isset($error["file"])){
            if($color == "yellow"){
              $color = "orange";
            }else{
              $color = "yellow";
            }
            if($n == 0){
              if($custom){
                $output .= "<span style = 'color:brown'>" .$error["file"]." at line: ".$error["line"]."</span>";
                $output .= "<br/><br/>TRAIL:";
              }else{
                $output .= "<span style = 'color:{$color}'>" .$error["file"]." at line: ".$error["line"]."</span>";
              }
            }else{
              $output .= "<span style = 'color:{$color}'>" .$error["file"]." at line: ".$error["line"]."</span>";
            }
            $output .= "<br/>";
            $n++;
          }          
        }
        echo createTrail($output);
      }

      // $custom = false;
      // $logMessage = "";
      // if(isset($eContext["th"])){
      //   $message    = "<span color='black'><b>Error :</b> </style>".$eContext["th"]->getMessage()."</span><br/><br/>";
      //   $message    .="<sapn color='black'><b>File : </b></span> Executed in file:  <br/>".$eContext["th"]->getFile(). " at line : ". $eContext["th"]->getLine();
      //   $logMessage = $eContext["th"]->getMessage();
      //   if (env("ENVIRONMENT") != "production") echo Message::write("error", $message);
      // }else{
        $custom     = true;
        $logMessage = $errorMessage;
        if (getAppEnv("ENVIRONMENT") != "production") echo Message::write("error", $errorMessage);
      //}
      error_log($logMessage);
      if (getAppEnv("ENVIRONMENT") != "production") getErrorDetails($custom);
      die();
    }
  }
?>
