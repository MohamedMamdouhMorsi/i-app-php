<?php


class CreateCss {
    public $i_app_string = "";
    
    function __construct($str)
    {
           
    }
    
    function __toString()
    {
        return (string) $this->i_app_string;
    }
}

?>