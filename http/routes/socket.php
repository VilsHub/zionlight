<?php
  return array(
    "publicLinks"         => [
      "/"                 => $config->plugsDir."/index/indexLinks.php",
      "cbt"               => $config->plugsDir."/index/cbtLinks.php",
      "login"             => $config->plugsDir."/index/LoginLinks.php",
    ],
    "publicMiddleware"         => [
      "cbt"               => $config->plugsDir."/index/cbtMiddleware.php",
    ],
    "login"               => [
      "login"             => $config->plugsDir."/login/LoginLinks.php",
    ],
    "dashboardLinks"      => [
      "add-questions"     => $config->plugsDir."/dashboard/addQuestionsLinks.php",
      "dashboard"         => $config->plugsDir."/dashboard/dashboardLinks.php",
    ],
    "dashboardMiddleware" => [
      "*"                 => $config->plugsDir."/dashboard/globalMiddleware.php",
      "add-questions"     => $config->plugsDir."/dashboard/addQuestMiddleware.php",
    ],
    "maintenaceLinks"     => [
      "maintenance|task"  => $config->plugsDir."/maintenance/task.php"
    ]
  )
?>