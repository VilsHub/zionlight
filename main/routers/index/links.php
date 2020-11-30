<?php 
$includeFiles = [
    "/"             =>$displayDir."/layouts/fragments/index/indexLinks.php",
    "services"      =>$displayDir."/layouts/fragments/index/servicesLinks.php"
];
$router::includeBlockFragment($includeFiles);
?>