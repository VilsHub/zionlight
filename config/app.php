<?php
/**********************************************************************/ 
/****** No array keys should be changes to avoid system failure *******/ 
/**********************************************************************/ 

$root               = dirname(__DIR__);
$connectionInfo     = require_once("connect.php");
$isStatic           = count($connectionInfo) == 0;
$permissions        = require_once("authorization.php");
$mainDir            = "app";
$libDir             = $mainDir."/lib/classes/application";
$setupDir           = $root."setup";

$div                = $root == ""?"":"/";

$appMainDir         = $root.$div.$mainDir;
$appLibDir          = $root.$div.$libDir;

// Display Directories
$staticDirName      = "static";
$dynamicDirname     = "dynamic";
$displayDir         = $root."/display";

$contentsDir        = $displayDir."/contents";
$layoutsDir         = $displayDir."/layouts";
$plugsDir           = $displayDir."/plugs";

$blocksDir          = $layoutsDir."/blocks";
$fragmentsDir       = $layoutsDir."/fragments";

//Classses Directories
$modelsDir          = $appLibDir."/models";
$controllersDir     = $appLibDir."/controllers";
$queriesDir         = $appLibDir."/queries";
$middlewaresDir     = $appLibDir."/middlewares";
$servicesDir        = $appLibDir."/services";

//Data and Schema Directories
$schemaDir          = $setupDir."/schemas";
$dataDir            = $setupDir."/data";

//Vendor
$vendorDir          = $appMainDir."/lib/vendor";
$applicationDataDir = $appMainDir."/data";

//assets links
$assetLinks     = [ 
    "dev"   => [
        "vUx" => "http://library.vilshub.com/lib/vUX/4.0.0/vUX-4.0.0.beta.js",
    ],
    "live"  => [
        "vUx" => "/library/vUx/vUX-4.0.0.beta.js",
    ]
];

return (object) [
    // Directories
    "mainDir"           => $mainDir,
    "appRootDir"        => $root,
    "appMainDir"        => $appMainDir,
    "contentsDir"       => $contentsDir,
    "contentsFolder"    => (object) ["static"=>$staticDirName, "dynamic"=>$dynamicDirname],
    "plugsDir"          => $plugsDir,
    "blocksDir"         => $blocksDir,
    "fragmentsDir"      => $fragmentsDir,
    "modelsDir"         => $modelsDir,
    "controllersDir"    => $controllersDir,
    "queriesBankDir"    => $queriesDir,
    "middlewaresDir"    => $middlewaresDir,
    "servicesDir"       => $servicesDir,
    "schemaDir"         => $schemaDir,
    "dataDir"           => $dataDir,
    "vendorDir"         => $vendorDir,
    "applicationDataDir"=> $applicationDataDir,

    // Suffixes
    "modelFileSuffix"   => "Model",
    "queryFileSuffix"   => "Queries",
    "serviceFileSuffix" => "Service",
    "dataFileSuffix"    => "Table",

    //App attributes
    "appName"           => "YourAppName", //Your App name, used for creating unique session name

    //AssetLinks
    "assetLinks"        => (object) $assetLinks,

    //API ID
    "apiId"             => "api", //To identify xhr request
    "CSRFName"          => "CSRF_Token", //The name of your CSRF identifier

    //Session Expiry
    "sessionExpiry"     => (60*60)*12, // in seconds 

    //Engine
    "database"          => "mysql",

    //connection   
    "pdo"               => $isStatic? null : (object) $connectionInfo["pdo"],
    "db"                => $isStatic? null : (object) $connectionInfo["db"],
    "xDB"               => $isStatic? null : (object) $connectionInfo["xDB"],

    //permissions   
    "permissions"       => $permissions,
];
?>
