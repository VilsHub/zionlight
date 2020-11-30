<?php
  class errorHandler
  {
    public static function listenForErrors(){
      
      function createTrail($trail){
        return "<div style='box-sizing: border-box; width:98%; padding:10px; margin:0 auto; border:solid 1px gray; background-color:black; color:white'>{$trail}</div>";
      }
      function getErrorDetails(){
        $output ="";
        $n = 0;
        $reversedTrail = array_reverse(debug_backtrace());
        $output .= "Executed  at :  ";
        foreach ($reversedTrail as $error){
          if(isset($error["file"])){
              $break = $n == 0?"<br/> <br/>  Trail:":"";
              $output .= $error["file"]." at line: ".$error["line"].$break;
              $output .= "<br/>";
              $n++;
          }          
        }
        echo createTrail($output);
      }
      function listen($errorNumber, $errorMessage, $file, $lineNumber, $eContext){
        echo $errorMessage;
        getErrorDetails();
        die();
      }
     set_error_handler('listen', E_USER_NOTICE);
    }
 
  }
?>
