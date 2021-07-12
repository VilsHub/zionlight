<?php
use vilshub\http\Request;

Request::post("/api/sample", "Campaign@subscribe");

Request::post("/api/product/{^[a-zA-Z]+$}", function($productId){

});

Request::get("/api/content/{^[a-zA-Z]+$}/{^[a-zA-Z]+$}", function($page, $serviceType){

});

Request::get("/api/welcome/register", "SessionReg@confirmedSession");
?>