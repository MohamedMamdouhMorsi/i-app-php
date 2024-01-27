<?php

class GetToken {

    public $token = 'FALSE';

function __construct($key)
{

    if(isset($_SESSION[$key])){
        $this->token       = $_SESSION[$key];
    }else if(isset($_COOKIE[$key])){
        $_SESSION[$key]    = $_COOKIE[$key];
        $this->token       = $_SESSION[$key];
    }

}


	function __toString()
	
	{
		$lastData = $this->token ;
		return (string) $lastData ;
	}
}