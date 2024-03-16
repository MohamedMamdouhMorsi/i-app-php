<?php

class TEST {
    public $text = "Data : ";
    
    function __construct($error)
    {
        $url = $_SERVER['REQUEST_URI'];
        if($url == "/test"){
            $this->text .=  $error."<br>";

        }
    }
    function add($error){
        $this->text .=  $error."<br>";
    }
    function ex(){
        $url = $_SERVER['REQUEST_URI'];
        if($url == "/test"){
    
        exit();
        }else{
            return true;
        }
    }
    function __destruct()
    {
        $url = $_SERVER['REQUEST_URI'];
        if($url == "/test"){
     echo $this->text;   
        }else{
            return true;
        }
    }
}