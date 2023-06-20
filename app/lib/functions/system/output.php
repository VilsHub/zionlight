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
function pretty_print($countable, $kill=false){
  Kint::dump($countable);
  if($kill) die;
}
function pp($array, $kill=false){
  pretty_print($array, $kill);
}
function dd($mixData){
  pretty_print($mixData, true);
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
$level=1;
function dump($countable, &$level=1){
  $n = count($countable);

  if($n > 0){
    
    foreach ($countable as $key => $value) {
      if (is_array($value)){
        $level++;
        $tab = tab($level);
        echo '<pre>'; 
        echo $tab.$key.": [ ";
        dump($value, $level);

        echo " ]";
        echo '</pre>';
        
      }else{
        $tab = tab($level);
        echo '<pre>'; 
        echo $tab.$key.": ".$value."=$level";
        echo '</pre>';
      }
     $level--;
     $level = $level <= 0? 1: $level;
    }

   
  }

}

function tab($n){
  $val = "";
  for ($i=0; $i < $n ; $i++) { 
    $val .= "&emsp;&emsp;";
  }

  return $val;
}
?>