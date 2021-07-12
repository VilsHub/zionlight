<?php
//array key = route definition
//array value = display base directory for route

$root               = "/";
$error              = "/error";
$maintenance        = "/maintenance";
$productPage        = "/product/{^[a-zA-Z]+$}";
$userId             = "/user/{[0-9]+}";
$pattern            = "/{[0-9a-zA-Z]+}";

return array(
    $root           => "/root",
    $error          => "/root",
    $maintenance    => "/root",
    $productPage    => "/root",
    $userId         => "/root",
    $pattern        => "/root"
)
?>