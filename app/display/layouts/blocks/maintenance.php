<?php  $router->listen($config->displayDir."/contents/maintenance");?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
	<?php
      require($config["displayDir"]."/layouts/fragments/maintenance/head.php");
    ?>
  </head>
  <body>
    <div id="countDown" hidden="hidden">1612134000</div>
    <div class="page">
      <?php $router->showContent;?>
    </div>
  </body>
</html>
