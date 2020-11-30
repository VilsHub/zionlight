<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
	<?php
      require($displayDir."/layouts/fragments/maintenance/head.php");
    ?>
  </head>
  <body>
    <div id="countDown" hidden="hidden">1577936800</div>
    <div class="page">
        <div class="maintenanceInfo">
          <h1>Maintenance in progress</h1>
          <p>Maintenance count down time</p>
          <div class="countDownCon">
              <div id="vCountDown">
                  <div class="mid">
                      <div id="yBlock" class="dateComp"></div>
                      <div id="mBlock" class="dateComp"></div>
                      <div id="dBlock" class="dateComp"></div>
                      <div id="hrBlock" class="dateComp"></div>
                      <div id="minBlock" class="dateComp"></div>
                      <div id="secBlock" class="dateComp"></div>
                  </div>
              </div>
          </div>
        </div>
        <canvas id="canvas"></canvas>
    </div>
  </body>
</html>
