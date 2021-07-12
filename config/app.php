<?php
/**********************************************************************/ 
/****** No array keys should be changes to avoid system failure *******/ 
/**********************************************************************/ 


$mainDir        = "/app";
$libDir         = $mainDir."/lib/classes/application";

$root           = dirname($_SERVER["DOCUMENT_ROOT"]);
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

    // Prefixes
    "modelFileSuffix"   => "Model",
    "queryFileSuffix"   => "Queries",

    //App attributes
    "appName"           => "Default", //Your App name, used for creating unique session name

    //AssetLinks
    "assetLinks"        => (object) $assetLinks,

    //API ID
    "apiId"             => "api" //To identify xhr request
)

?>