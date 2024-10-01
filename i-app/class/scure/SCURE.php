<?php 

class SCURE_IAPP {

    function __construct()
    {
        $url = $_SERVER['REQUEST_URI'] ;

        if($url == "/closeServer"){
            new CLOSE_SERVER();
        }
        new STOP_BOT();
        new GO_SSL();
    }
}