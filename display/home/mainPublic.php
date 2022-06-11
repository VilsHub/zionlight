<?php //$app->router->plugToSocket("publicMiddleware"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
      require($app->config->fragmentsDir."/head.php");
      $app->router->plugToSocket("publicLinks");
    ?>
  </head>
  <body>
    <div class="page" id="page">
      <?php require($app->config->fragmentsDir."/header.php");?>
        
      <?php $app->router->showContent;?>
        
      <?php require_once($app->config->fragmentsDir."/footer.php")?>
    </div>
  </body>
</html>