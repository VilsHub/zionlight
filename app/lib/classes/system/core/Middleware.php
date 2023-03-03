<?php
class Middleware
{
    public $loader = null;
    function __construct(Loader $loader){
        $this->loader = $loader;
    }
}
?>