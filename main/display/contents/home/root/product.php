<?php $router->strictMode(); ?>
<?php
    if($router->id != null){
        if($router->id != 2000){
            $router->showError(404);
        }
    }
?>
<div id="contentCon">
    view product page of id => <?=$router->id ?>
        <?php print_r($router->params)?>
</div>