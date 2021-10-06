<?php
use vilshub\router\Router;
use vilshub\helpers\Message;
use vilshub\helpers\Get;
use vilshub\helpers\Style;
use vilshub\helpers\textProcessor;
use vilshub\validator\Validator;

class App {
    function __construct(Loader $loader, Router $router, $config){
        $this->loader = $loader;
        $this->config = $config;
        $this->router = $router;
    }

    public function boot(){
        Session::start();
        CSRF::generateSessionToken($this->config->CSRFName);
    }
    public function setPageTitle($value){
        $msg =  " Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("setPageTitle(x)", "black")." method argument must be a string";
        Validator::validateString($value, Message::write("error", $msg));
        echo "<script type = 'text/javascript'>document.querySelector('title').innerText = '{$value}' </script>";
      }
}
?>