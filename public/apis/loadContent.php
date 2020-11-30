<?php
require("../../lib/classes/controllers/contentLoader.php");
use vilshub\xhrHandler\xhr;
$uri = xhr::requestURI();

if($uri == "/content/index/homepage"){
    contentLoader::getHomepage();
}else if($uri == "/content/services/programming"){
    contentLoader::getServicesContent("programming");
}elseif($uri == "/content/services/systemadmin"){
    contentLoader::getServicesContent("systemAdmin");
}

?>