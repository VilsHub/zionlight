<?php //$app->router->plugToSocket("publicMiddleware"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
      $app->getFragment("home", "head.php");
      $app->router->plugToSocket("publicLinks");
    ?>
  </head>
  <body>
    <div class="page" id="page">
        <?php $app->getFragment("home", "header.php");?>
        
        <?php $app->router->showContent;?>
          
        <?php $app->getFragment("home", "footer.php");?>
    </div>
  </body>
</html>