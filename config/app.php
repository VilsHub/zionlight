<?php
/**********************************************************************/ 
/****** No array keys should be changes to avoid system failure *******/ 
/**********************************************************************/ 

$root               = dirname(__DIR__);
$permissions        = require_once("authorization.php");
$envFile            = $root."/.env";
$mainDir            = $root."/app";
$libDir             = $mainDir."/lib";
$classDir           = $libDir ."/classes/application";
$setupDir           = $root."/setup";
$miscDir            = $mainDir."/misc";
$logsDir            = $root."/logs";
$publicDir          = $root."/public";

$appMainDir         = $mainDir;

//Display Directories
$displayDir         = $root."/display";
$contentsDir        = "contents";
$loadDirName        = $contentsDir."/load";
$xhrDirName         = $contentsDir."/xhr";
$plugsDir           = "plugs";
$fragmentsDir       = "fragments";

//Other Libraries Directories
$traitsDir          = $libDir ."/traits/application";
$functionsDir       = $libDir ."/function/application";

//Classses Directories
$modelsDir          = $classDir."/models";
$controllersDir     = $classDir."/controllers";
$queriesDir         = $classDir."/queries";
$middlewaresDir     = $classDir."/middlewares";
$servicesDir        = $classDir."/services";

//Data and Schema Directories
$schemaDir          = $setupDir."/schemas";
$dataDir            = $setupDir."/data";

//Vendor
$vendorDir          = $mainDir."/lib/vendor";

//System Application
$appsDataDir        = $mainDir."/data";
$appsPublicDir      = $mainDir."/public/apps/";

//assets links
$assetLinks         = [ 
    "dev"   => [
        "vUx" => "http://library.vilshub.com/lib/vUX/4.0.0/",
    ],
    "live"  => [
        "vUx" => "/library/vUx/vUX-4.0.0.beta.js",
    ]
];

//Misc Files
$miscFiles          = [
    "db_certificate"    => $miscDir."/certs/DigiCertGlobalRootCA.crt.pem",
    "phpini_production" => $miscDir."/php-ini/production.php",
    "phpini_development"=> $miscDir."/php-ini/development.php",
    "phpini_testing"    => $miscDir."/php-ini/testing.php",
];

//Log file
/**
 *  {value} => dynamic value, to be supplied on runtime for replacement
 *  
 * Example
 * {user}_logs.log => john_logs.log if user is john
 * 
 */
$logFile            = $logsDir."/logdata.log";

return (object) [
    //Env file
    "envFile"           => $envFile,

    // Directories
    "mainDir"           => $mainDir,
    "appRootDir"        => $root,
    "appMainDir"        => $mainDir,
    "displayDir"        => $displayDir , 
    "contentsFolder"    => (object) ["load"=>$loadDirName, "xhr"=>$xhrDirName],
    "plugsDir"          => $plugsDir,
    "fragmentsDir"      => $fragmentsDir,
    "modelsDir"         => $modelsDir,
    "controllersDir"    => $controllersDir,
    "queriesBankDir"    => $queriesDir,
    "middlewaresDir"    => $middlewaresDir,
    "servicesDir"       => $servicesDir,
    "traitsDir"         => $traitsDir,
    "functionsDir"      => $functionsDir,
    "schemaDir"         => $schemaDir,
    "dataDir"           => $dataDir,
    "vendorDir"         => $vendorDir,
    "appsDataDir"       => $appsDataDir,
    "appPublicDir"      => $appsPublicDir,
    "publicDir"         => $publicDir,

    // Suffixes
    "modelFileSuffix"   => "Model",
    "queryFileSuffix"   => "Queries",
    "serviceFileSuffix" => "Service",
    "dataFileSuffix"    => "Table",
    "traitFileSuffix"   => "Trait",

    //App attributes
    "appName"           => "YourAppName", //Your App name, used for creating unique session name

    //Misc Files
    "miscFiles"         => (object) $miscFiles,

    //Log file
    "logFile"           => $logFile,

    //AssetLinks
    "assetLinks"        => (object) $assetLinks,

    //API ID
    "apiId"             => "api", //To identify xhr request
    "CSRFName"          => "CSRF_Token", //The name of your CSRF identifier

    //Session Expiry
    "sessionExpiry"     => (60*60)*12, // in seconds 

    //permissions   
    "permissions"       => $permissions,
];
?>