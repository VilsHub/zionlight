<?php
use vilshub\Helpers\Message;

function pluralizer($word, $plural, $count){
    // => "s:3" means strip out last 3 character form target word and replace with the letter s
    // => "s:-1" means dont strip out any character form target word but append 's' to it
    $info = explode(":", $plural);
    if($count > 1){//has plural
        if(count($info) > 1){//has suffix, so use suffix
            $strippedWord = ($info[1] != -1)? substr($word, 0, -$info[1]):$word;
            return $strippedWord.$info[0];
        }else{//no suffix use word
            return $info[0];
        }
    }else{//no plural
        return $word;
    }
}
function articlize(){
    
}
?>

