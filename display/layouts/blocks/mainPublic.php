<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
      require($router->config->displayDir."/layouts/fragments/index/head.php");
      $router->plugToSocket("publicLinks");
    ?>
  </head>
  <body>
    <div class="page" id="page">
      <?php require($router->config->displayDir."/layouts/fragments/index/header.php");?>
        
      <?php $router->showContent;?>
        
      <?php require_once($router->config->displayDir."/layouts/fragments/index/footer.php")?>
    </div>
  </body>
</html>