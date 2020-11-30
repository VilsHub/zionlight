<?php
require("../../__lib_/vendor/autoload.php");
use vilshub\xhrHandler\xhr;
session::start();
class sessionReg
{
    public static function confirmedSession($url){
        if(xhr::requestMethod("get") && xhr::requestURI($url)){
            $_SESSION['welcome'] = "splashed";
            echo 1;
        };
    }
}
?>