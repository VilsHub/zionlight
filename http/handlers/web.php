<?php
use vilshub\http\Request;

//Set blocks
if(Request::$uri == $root){

    $block      = $app->getDisplayBlock("home", "mainPublic.php");
    $baseDir    = $app->getLoadBase("home");
    $app->router->listen($block, $baseDir);

}else if(Route::block($maintenance, Request::$uri)){

    $block      = $app->getDisplayBlock("maintenance", "maintenance.php");
    $baseDir    = $app->getLoadBase("maintenance");
    $app->router->listen($block, $baseDir);

}else if(Route::block($error, Request::$uri)){

    $block      = $app->getDisplayBlock("error", "error.php");
    $baseDir    = $app->getLoadBase("error");
    $app->router->listen($block, $baseDir);

}else{//any other route

    $block      = $app->getDisplayBlock("home", "mainPublic.php");
    $baseDir    = $app->getLoadBase("home");
    $app->router->listen($block, $baseDir);
    
}

//Error handler
$app->router->error();
?>