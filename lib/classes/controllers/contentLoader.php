<?php
require("../../__lib_/vendor/autoload.php");
use vilshub\xhrHandler\xhr;

class contentLoader
{
    public static function getHomepage(){
        if(xhr::requestMethod("get")){
            echo file_get_contents("../../__main_/display/XHRContent/home/main.php");
        };
    }
    public static function getServicesContent($serviceID){
        if($serviceID == "programming"){
            if(xhr::requestMethod("get")){
                echo file_get_contents("../../__main_/display/XHRContent/services/programming.php");
            };
        }else if($serviceID == "systemAdmin"){
            echo file_get_contents("../../__main_/display/XHRContent/services/systemadmin.php");
        }
        
    }
}
?>