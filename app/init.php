<?php

use vilshub\http\Request;
use vilshub\router\Router;
use vilshub\helpers\Message;
use vilshub\helpers\Style;

//Load required files
require_once(dirname(__DIR__)."/app/lib/vendor/autoload.php");

//register error handler
ErrorHandler::listenForErrors();

//$Load config files
$config       = require_once(dirname(__DIR__)."/config/app.php");
$routes       = require_once(dirname(__DIR__)."/http/routes/content.php");
$socketFiles  = require_once(dirname(__DIR__)."/http/routes/socket.php");

//System applications
$applications = require_once(dirname(__DIR__)."/config/applications.php");


//Instantiate App

$app          = new App(new Loader, new Router($routes, $socketFiles, $config), $config);

//Configure application router
$app->router->defaultBaseFile  = "index.php";
$app->router->error404File     = $config->displayDir."/error/".$config->contentsFolder->load."/root/404.php";
$app->router->error404URL      = "/error/404";
$app->router->maintenanceURL   = "/maintenance";
$app->router->maintenanceMode  = false;
$app->router->dynamicRoute     = true;
$app->router->maskExtension    = ".java";

$app->boot();


if(Request::isForApplication($applications)){ //application
  $application  = $applications[Request::$id];
  $configInUse  = strtolower($application["configInUse"]);
  
  if($configInUse != "vendor" && $configInUse != "system") trigger_error(Message::write("error", "<b>applications['".Request::$id."']['configInUse']</b> value must be set to either ".Style::color("system", "green").Style::color(" or ", "red").Style::color("vendor", "green").Style::color(" in ", "red").Style::color("/config/applications.php", "blue")));
  
  $webHandler   = $application["config"][$configInUse]["webHandler"];
  $apiHandler   = $application["config"][$configInUse]["apiHandler"];
  $apiID        = $application["apiID"];

  if(Request::isFor("web", $apiID, 2)){
    require_once($webHandler);
  }else{
    require_once($apiHandler);
  }

}else{ //system
  if(Request::isFor("web", $config->apiId, 1)){
    require_once(dirname(__DIR__)."/http/handlers/web.php");
  }else{
    require_once(dirname(__DIR__)."/http/handlers/api.php");
  }
}
?>
