<?php

use vilshub\http\Request;
use vilshub\router\Router;

//Load required files
require_once(dirname(__DIR__)."/app/lib/vendor/autoload.php");

//register error handler
ErrorHandler::listenForErrors();

//Load config files
$config       = require_once(dirname(__DIR__)."/config/app.php");
$routes       = require_once(dirname(__DIR__)."/http/routes/content.php");
$socketFiles  = require_once(dirname(__DIR__)."/http/routes/socket.php");

//set platform
$env          = "web";


//System applications
$systemAppsHandler = require_once(dirname(__DIR__)."/config/applications.php");


//Instantiate App
$app          = new App(new Loader, new Router($routes, $socketFiles, $config), $config);

//Configure application router
$app->router->defaultBaseFile  = "index.php";
$app->router->error404File     = $app->getDisplayFile("error", "/root/404.php");
$app->router->error404URL      = "/error/404";
$app->router->maintenanceURL   = "/maintenance";
$app->router->maintenanceMode  = false;
$app->router->dynamicRoute     = true;
$app->router->maskExtension    = ".java";

$app->boot();

if(Request::isForApplication($systemAppsHandler->ids)){ //application
  $systemApp    = $systemAppsHandler->{Request::$id};
  $webHandler   = $systemApp->routeFiles->webHandler;
  $apiHandler   = $systemApp->routeFiles->apiHandler;
  $apiID        = $systemApp->apiId;
  
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