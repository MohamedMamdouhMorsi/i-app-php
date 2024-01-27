<?php

class selectColumn{
    function __construct($op) {
        $opText = "";
        for ($i = 0; $i < count($op); $i++) {
            $opText .= " " . $op[$i];
            if ($op[$i + 1]) {
                $opText .= ", ";
            }
        }
        return $opText;
    }
}