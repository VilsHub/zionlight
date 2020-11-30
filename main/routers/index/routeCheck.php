<?php
    //Callbacks
    $router->callBack = "root";
    function root($robj){
        
        $robj->data = ["title"=> "Hellohshhs",
                        "address" => "No 200",
                    ];
        return true;
    }
    $router->listen($displayDir."/contents/home"); //display/contents/home => is the root base display files
?>
