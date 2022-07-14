<?php
$systemApps = new SystemApplications();

// Applications that are dependent on http request, use 1st segment uri value as application ID
$systemApps->add("zdash");

//Configure your applications
// $systemApps->config("zdash", "apiId", "api");
// $systemApps->config("zdash", "nameSpace","vilshub/zdash");
// $systemApps->config("zdash", "configInUse", "vendor"); // vendor or system
// $systemApps->config("zdash", "routeFiles", [
//     "socket"        => "/http/routes/socket.php",
//     "content"       => "/http/routes/content.php",
//     "webHandler"    => "/http/handlers/web.php",
//     "apiHandler"    => "/http/handlers/api.php"
// ]);
// $systemApps->config("zdash", "displayMaps", [
//     "displayDir"    => "display", //relative to package root
//     "loadDirName"   => "contents/load", //relative to the display block directory under the displayDir
//     "xhrDirName"    => "contents/xhr", //relative to the displays block directory under the displayDir
//     "plugsDir"      => "plugs", //relative to the displays directory block under the displayDir
//     "fragmentsDir"  => "fragments" //relative to the displays directory block under the displayDir
// ]);


return $systemApps;
?>