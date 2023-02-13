<?php
/**
 * array key    = route definition
 * array value  = display base directory for route
 * 
 * All dynamic segment value could be recieved as data
 * The segment to be used as the display file should be indicated with a ":", (example "/:sales/branch/{[0-9a-zA-Z]+}") if not specified, the defaultBaseFile will be used, this applies to only dynamic route
 */

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