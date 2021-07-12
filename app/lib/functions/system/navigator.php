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
    Validator::validateString($url, Message::write("error", $msg1));
    header("Location: {$url}");
}
?>