<?php
use vilshub\http\Request;

//Set blocks
if(Request::$uri == $root){
    
    $block = $config->displayDir."/home/mainPublic.php";
    $baseDir = $config->displayDir."/home/".$config->contentsFolder->load;
    $app->router->listen($block, $baseDir);

}else if(Route::block($maintenance, Request::$uri)){

    $block = $config->displayDir."/maintenance/maintenance.php";
    $baseDir = $config->displayDir."/maintenance/".$config->contentsFolder->load;
    $app->router->listen($block, $baseDir);

}else if(Route::block($error, Request::$uri)){

    $block = $config->displayDir."/error/error.php";
    $baseDir = $config->displayDir."/error/".$config->contentsFolder->load;
    $app->router->listen($block, $baseDir);

}else{//any other route

    $block = $config->displayDir."/home/mainPublic.php";
    $baseDir = $config->displayDir."/home/".$config->contentsFolder->load;
    $app->router->listen($block, $baseDir);
    
}

//Error handler
$app->router->error();
?>