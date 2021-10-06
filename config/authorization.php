<?php
//Set roles
$roles          = [
    "admin", 
    "super-user"
];

//set rights
$permissions   = [
    "install-os"        => [$roles[0]],
    "format-system"     => [$roles[1]]
];
return $permissions;
?>