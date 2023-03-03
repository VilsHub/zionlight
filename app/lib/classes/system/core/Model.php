<?php
class Model
{
    public $db, $loader;
    function __construct(Loader $loader){
        global $app;
        $this->db       = $app->config->db;
        $this->loader   = $loader;
    }
}
?>