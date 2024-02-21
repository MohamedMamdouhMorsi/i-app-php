<?php

class selectColumn{
    public $opText = "";
    function __construct($op) {
        if(sizeof($op) > 0){
            for ($i = 0; $i < sizeof($op); $i++) {
                if(isset( $op[$i])){
                    $this->opText .= " " . $op[$i];
                    if (isset($op[$i + 1])) {
                        $this->opText .= ", ";
                    }
                }
               
            }
        }
     
    }
    function __toString()
    {
        return (string) $this->opText;
    }
}