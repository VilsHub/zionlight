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
function redirect($url){
    //Validate argument
    $msg1 = "Invalid argument value, ".Style::color("redirect(x)", "black")." function argument must be a string";
    Validator::validateString($url,  $msg1);
    header("Location: {$url}");
}

function httpTerminate($code, $message=null){
  $defaultMessages = [
    "403" => "Error 403: Access denied",
    "404" => "Error 404: The target resource is not found",
    "415" => "Error 415: The sent resource is not supported"
  ];
  switch ($code) {
    case '403':
      header("HTTP/1.0 403 Access denied");
      break;
    case '404':
      header("HTTP/1.0 404 File not found");
      break;
    case '415':
      header("HTTP/1.0 415 Unsupported Media Type");
      break;
    default:
      # code...
      break;
  }
  if (array_key_exists($code, $defaultMessages)){
    $message = $message ?? $defaultMessages[$code];
  }else{
    $message = "HTTP error: something went wrong";
  }
  
  die($message);
}
?>