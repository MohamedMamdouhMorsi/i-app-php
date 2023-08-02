<?php

class view {
    public $data = "FALSE";
    function __construct()
    {
        $this->data = test();
    }
    function __toString()
    {
        return $this->data;
    }
}