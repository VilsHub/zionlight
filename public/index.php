<?php
require("../lib/vendor/autoload.php");
require("../config/assetlink.php");
require("../config/routes.php");
require("../config/app.php");
errorHandler::listenForErrors();

use vilshub\router\router;
$router = new router();
$router->defaultBaseFile = "index.php";
$router->error404URL = "/error/404";
$router->error404File = "404.php";
$router->maintenanceURL = "/maintenance";
$router->maintenanceMode = false;
$router->routesMap = [
  $root       => "/root",
  $error      => "/root",
  $userCreate => "/utest",
  $userS      => "/userHome",
  $user       => "/utest",
  $userTest   => "/utest",
  $userTest2  => "/utest",
  $admin      => "/root"
];
session::start();

function getPattern($id){
  $parsedID = str_replace("/", "\/", $id);
  return "/".$parsedID."[a-zA-Z0-9\?\.\-\_\/]{0,}$/";
}
$URI = $_SERVER["REQUEST_URI"];

if($URI == $root){
  $router->baseRoute = $root;
  require($displayDir."/layouts/blocks/mainPublic.php");
}else if(preg_match(getPattern($maintenance), $URI)){
  require($displayDir."/layouts/blocks/maintenance.php"); 
}else if(preg_match(getPattern($error), $URI)){
  require($displayDir."/layouts/blocks/error.php"); 
}else{
  $router->baseRoute = $root;
  require($displayDir."/layouts/blocks/mainPublic.php"); 
}
?>
