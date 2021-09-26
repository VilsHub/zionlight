<?php
/**********************************************************************/ 
/****** No array keys should be changes to avoid system failure *******/ 
/**********************************************************************/ 

$root           = dirname($_SERVER["DOCUMENT_ROOT"]);
$connectionInfo = require_once("connect.php");
$mainDir        = "app";
$libDir         = $mainDir."/lib/classes/application";
$setupDir       = $root."setup";

$div = $root == ""?"":"/";

$appMainDir     = $root.$div.$mainDir;
$appLibDir      = $root.$div.$libDir;
$displayDir     = $appMainDir."/display";
$XHRContentDir  = $displayDir."/XHRContent";
$modelsDir      = $appLibDir."/models";
$controllersDir = $appLibDir."/controllers";
$queriesDir     = $appLibDir."/queries";
$middlewaresDir = $appLibDir."/middlewares";
$servicesDir    = $appLibDir."/services";
$schemaDir      = $setupDir."/schemas";
$dataDir        = $setupDir."/data";

//assets links
$assetLinks     = [ 
    "libraryDomain" => "http://library.vilshub.com"
];

return (object)  [
    // Directories
    "mainDir"           => $mainDir,
    "appRootDir"        => $root,
    "appMainDir"        => $appMainDir,
    "displayDir"        => $displayDir,
    "XHRContentDir"     => $XHRContentDir,
    "modelsDir"         => $modelsDir,
    "controllersDir"    => $controllersDir,
    "queriesBankDir"    => $queriesDir,
    "middlewaresDir"    => $middlewaresDir,
    "servicesDir"       => $servicesDir,
    "schemaDir"         => $schemaDir,
    "dataDir"           => $dataDir,

    // Suffixes
    "modelFileSuffix"   => "Model",
    "queryFileSuffix"   => "Queries",
    "serviceFileSuffix" => "Service",
    "dataFileSuffix"    => "Table",

    //App attributes
    "appName"           => "", //Your App name, used for creating unique session name

    //AssetLinks
    "assetLinks"        => (object) $assetLinks,

    //API ID
    "apiId"             => "api", //To identify xhr request

    //Session Expiry
    "sessionExpiry"     => (60*60)*12, // in seconds 

    //Engine
    "database"          => "mysql",

    //connection   
    "pdo"               => $connectionInfo["pdo"],
    "db"                => $connectionInfo["db"],
    "xDB"               => $connectionInfo["xDB"]
]

?>