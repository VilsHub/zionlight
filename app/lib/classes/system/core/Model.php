<?php
class Model
{
    function __construct(Loader $loader){
        global $config;
        $this->db = $config->db;
        $this->loader = $loader;
    }
}
?>
