<?php
class SessionReg extends Controller
{
    function __construct(){
        parent::__construct();
        $this->session::start($this->appConfig->appName);
    }
    public function confirmedSession(){
        $_SESSION['welcome'] = "splashed";
        echo 1;
    }
}
?>