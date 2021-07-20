<?php
//Load required files
require_once(__DIR__."/../app/lib/vendor/autoload.php");
ErrorHandler::listenForErrors();

//$Config variable name should not be change to avoid system failure
$config = require_once(__DIR__."/../config/app.php");
$routes = require_once(__DIR__."/../http/routes/content.php");
$socketFiles = require_once(__DIR__."/../http/routes/socket.php");


//Others settings
Session::start($config->appName);
CSRF::generateSessionToken("CSRF_Token");


//Configure router

use vilshub\router\Router;

$router = new Router();
$router->defaultBaseFile  = "index.php";
$router->error404File     = $config->displayDir."/contents/error/root/404.php";
$router->maintenanceURL   = "/maintenance";
$router->maintenanceMode  = false;
$router->dynamicRoute     = true;
$router->maskExtension    = ".php";
$router->config           = $config;
$router->routes           = $routes;
$router->socketFiles      = $socketFiles;

if($router->route("web")){
  require_once(__DIR__."/../http/handlers/web.php");
}else{
  require_once(__DIR__."/../http/handlers/api.php");
}
?>
