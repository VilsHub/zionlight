<?php
use vilshub\router\Router;

//Load required files
require_once(__DIR__."/../app/lib/vendor/autoload.php");

//register error handler
ErrorHandler::listenForErrors();

//$Load config files
$config       = require_once(__DIR__."/../config/app.php");
$routes       = require_once(__DIR__."/../http/routes/content.php");
$socketFiles  = require_once(__DIR__."/../http/routes/socket.php");


//Instantiate App

$app          = new App(new Loader, new Router($routes, $socketFiles, $config), $config);

//Configure application router
$app->router->defaultBaseFile  = "index.php";
$app->router->error404File     = $config->contentsDir."/error/".$config->contentsFolder["static"]."/root/404.php";
$app->router->error404URL      = "/error/404";
$app->router->maintenanceURL   = "/maintenance";
$app->router->maintenanceMode  = false;
$app->router->dynamicRoute     = true;
$app->router->maskExtension    = ".java";

$app->boot();

if($app->router->route("web")){
  require_once(__DIR__."/../http/handlers/web.php");
}else{
  require_once(__DIR__."/../http/handlers/api.php");
}
?>
