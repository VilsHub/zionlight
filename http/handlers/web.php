<?php
use vilshub\http\Request;

//Set blocks
if(Request::$uri == $root){

    $block = $config->blocksDir."/mainPublic.php";
    $baseDir = $config->contentsDir."/home/".$config->contentsFolder->static;
    $app->router->listen($block, $baseDir);

}else if(Route::block($maintenance, Request::$uri)){

    $block = $config->blocksDir."/maintenance.php";
    $baseDir = $config->contentsDir."/maintenance/".$config->contentsFolder->static;
    $app->router->listen($block, $baseDir);

}else if(Route::block($error, Request::$uri)){

    $block = $config->blocksDir."/error.php";
    $baseDir = $config->contentsDir."/error/".$config->contentsFolder->static;
    $app->router->listen($block, $baseDir);

}else{//any other route
    $block = $config->blocksDir."/mainPublic.php";
    $baseDir = $config->contentsDir."/home/".$config->contentsFolder->static;
    $app->router->listen($block, $baseDir);
}

//Error handler
$app->router->error();
?>