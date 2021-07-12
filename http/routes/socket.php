<?php
  return array(
    "publicLinks"     => [
      "/"             =>$config->displayDir."/layouts/plugs/index/indexLinks.php",
      "users"         =>$config->displayDir."/layouts/plugs/index/usersLink.php",
      "product"       =>$config->displayDir."/layouts/plugs/index/productLinks.php"
    ],
    "maintenaceLinks"        =>[
      "maintenance|task"     => $config->displayDir."/layouts/plugs/maintenance/task.php"
    ]
  )
?>