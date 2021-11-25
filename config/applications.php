<?php
$name                   = "vilshub/zdash";
$vendorSpace            = $config->vendorDir."/".$name;
$applicationDataSpace   = $config->applicationDataDir."/".$name;
$applications = [
    // Applications that are dependent on http request, use 1st segment uri value as application ID
    "zdash" => [
        "config"        => [
            "vendor"    => [
                "socket"        => $vendorSpace."/http/routes/socket.php",
                "content"       => $vendorSpace."/http/routes/content.php",
                "webHandler"    => $vendorSpace."/http/handlers/web.php",
                "apiHandler"    => $vendorSpace."/http/handlers/api.php",
                "display"       => $vendorSpace."/display",
            ],
            "system"    => [
                "socket"        => $applicationDataSpace."/http/routes/socket.php",
                "content"       => $applicationDataSpace."/http/routes/content.php",
                "webHandler"    => $applicationDataSpace."/http/handlers/web.php",
                "apiHandler"    => $applicationDataSpace."/http/handlers/api.php",
                "display"       => $applicationDataSpace."/display",
            ]
        ],
        "apiID"         => "api",
        "configInUse"   => "vendor"
    ]
];
return $applications;
?>