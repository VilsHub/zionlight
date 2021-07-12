<?php
use vilshub\http\Request;

//Set blocks
if(Request::$uri == $root){
    $block = $config->displayDir."/layouts/blocks/mainPublic.php";
    $baseDir = $config->displayDir."/contents/home";
    $router->listen($block, $baseDir);
}else if(Route::block($maintenance, Request::$uri)){
    $block = $config->displayDir."/layouts/blocks/maintenance.php";
    $baseDir = $config->displayDir."/contents/maintenance";
    $router->listen($block, $baseDir);
}else if(Route::block($error, Request::$uri)){
    $block = $config->displayDir."/layouts/blocks/error.php";
    $baseDir = $config->displayDir."/contents/error";
    $router->listen($block, $baseDir);
}else{//any other route
    $block = $config->displayDir."/layouts/blocks/mainPublic.php";
    $baseDir = $config->displayDir."/contents/home";
    $router->listen($block, $baseDir);
}

//Error handler
$router->error();
?>