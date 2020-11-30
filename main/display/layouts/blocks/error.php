<?php require($appMainDir."/routers/error/routeCheck.php");?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php 
      require($displayDir."/layouts/fragments/error/head.php");
      require($appMainDir."/routers/error/links.php");
    ?>
  </head>
  <body>
    <div class="page">        
      <?php require($appMainDir."/routers/error/content.php");?>
    </div>
  </body>
</html>
