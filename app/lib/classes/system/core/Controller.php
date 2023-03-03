<?php
use vilshub\validator\Validator;
use vilshub\http\Request;
use vilshub\helpers\Message;
use vilshub\helpers\Style;

class Controller
{
    public $appConfig, $loader, $request, $session, $validator;
    function __construct(Loader $loader, Request $request, Session $session, Validator $validator){
        global $config;
        $this->appConfig    = $config;
        $this->loader       = $loader;
        $this->request      = $request;
        $this->session      = $session;
        $this->validator    = $validator;
    }
}
?>