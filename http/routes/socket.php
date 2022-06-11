<?php
  return array(
    "publicLinks"         => [
      "/"                 => $config->displayDir."/home/".$config->plugsDir."/indexLinks.php",
      // "cbt"               => $config->displayDir."/home/".$config->plugsDir."/cbtLinks.php",
      // "login"             => $config->displayDir."/home/".$config->plugsDir."/LoginLinks.php",
    ],
    "publicMiddleware"         => [
      // "cbt"               => $config->displayDir."/cbt/".$config->plugsDir."/cbtMiddleware.php",
    ],
    "login"               => [
      // "login"             => $config->displayDir."/login/".$config->plugsDir."/LoginLinks.php",
    ],
    "dashboardLinks"      => [
      // "add-questions"     => $config->displayDir."/dashboard/".$config->plugsDir."/addQuestionsLinks.php",
      // "dashboard"         => $config->displayDir."/dashboard/".$config->plugsDir."/dashboardLinks.php",
    ],
    "dashboardMiddleware" => [
      // "*"                 => $config->displayDir."/dashboard/".$config->plugsDir."/globalMiddleware.php",
      // "add-questions"     => $config->displayDir."/dashboard/".$config->plugsDir."/addQuestMiddleware.php",
    ],
    "maintenaceLinks"     => [
      "maintenance|task"  => $config->displayDir."/maintenance/".$config->plugsDir."/task.php"
    ]
  )
?>