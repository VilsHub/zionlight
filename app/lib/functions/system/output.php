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
function pretty_print($array){
    //Validate argument
    $msg1 = "Invalid argument value, ".Style::color("prettyPrint(x)", "black")." function argument must be an array";
    Validator::validateArray($array, Message::write("error", $msg1));
    print_r($array);
    die;
}
function dd($value){
  var_dump($value);
  die;
}
function json_encode_with_csrf($array, $token){
  $array["csrf"] = $token;
  return json_encode($array);
}

function dump_if($value, $content){
  /*@value The value set by Session::set('xid', value)*/ 
  if(Session::get("xid") == $value){
    echo $content;
  }
}
?>