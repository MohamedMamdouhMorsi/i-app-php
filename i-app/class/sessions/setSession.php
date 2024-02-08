<?php

class setSession{
    function __construct($name , $value)
    {
        $_SESSION[$name] = $value;
        //
        setcookie($name , $value, strtotime( '+30 days' ), "/", "", "", TRUE);
    }
    
    function __destruct()
    {
        return true;
    }
}