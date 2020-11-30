<?php require($appMainDir."/routers/index/routeCheck.php");?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
      require($displayDir."/layouts/fragments/index/head.php");
      require($appMainDir."/routers/index/links.php");
    ?>
  </head>
  <body>
    <div class="page" id="page">
      <?php require($displayDir."/layouts/fragments/index/header.php");?>
        
      <?php require($appMainDir."/routers/index/content.php");?>
        
      <?php require_once($displayDir."/layouts/fragments/index/footer.php")?>
    </div>
  </body>
</html>