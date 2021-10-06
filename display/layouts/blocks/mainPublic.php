<?php //$app->router->plugToSocket("publicMiddleware"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
      require($app->config->fragmentsDir."/index/head.php");
      $app->router->plugToSocket("publicLinks");
    ?>
  </head>
  <body>
    <div class="page" id="page">
      <?php require($app->config->fragmentsDir."/index/header.php");?>
        
      <?php $app->router->showContent;?>
        
      <?php require_once($app->config->fragmentsDir."/index/footer.php")?>
    </div>
  </body>
</html>