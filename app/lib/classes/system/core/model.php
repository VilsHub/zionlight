<?php
use vilshub\dbant\DBAnt;
class Model
{
    function __construct(){
        global $pdo;
        $this->db = new DBAnt($pdo);
        $this->loader = new Loader();
    }
}
?>