<?php
/**********************************************************************/ 
/****** No array keys should be changes to avoid system failure *******/ 
/**********************************************************************/ 

$root           = dirname($_SERVER["DOCUMENT_ROOT"]);
$connectionInfo = require_once(__DIR__."/connect.php");
$mainDir        = "/app";
$libDir         = $mainDir."/lib/classes/application";



$appMainDir     = $root.$mainDir;
$appLibDir      = $root.$libDir;
$displayDir     = $appMainDir."/display";
$XHRContentDir  = $displayDir."/XHRContent";
$modelsDir      = $appLibDir."/models";
$controllersDir = $appLibDir."/controllers";
$queriesDir     = $appLibDir."/queries";
$middleWaresDir = $appLibDir."/middleWares";


//assets links
$assetLinks     = [ 
    "libraryDomain" => "http://library.vilshub.com"
];

return (object) array (
    // Directories
    "mainDir"           => $mainDir,
    "appRootDir"        => $root,
    "appMainDir"        => $appMainDir,
    "displayDir"        => $displayDir,
    "XHRContentDir"     => $XHRContentDir,
    "modelsDir"         => $modelsDir,
    "controllersDir"    => $controllersDir,
    "queriesDir"        => $queriesDir,
    "middleWaresDir"    => $middleWaresDir,

    // Suffixes
    "modelFileSuffix"   => "Model",
    "queryFileSuffix"   => "Queries",

    //App attributes
    "appName"           => "VilsHub", //Your App name, used for creating unique session name

    //AssetLinks
    "assetLinks"        => (object) $assetLinks,

    //API ID
    "apiId"             => "api", //To identify xhr request

    //Session Expiry
    "sessionExpiry"     => 60*60, // in seconds

    //Engine
    "database"          => "mysql",

    //connection   
    "pdo"               => $connectionInfo["pdo"],
    "db"                => $connectionInfo["db"],
)

?>