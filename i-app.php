<?php

class i_app{

protected $data = "Hello Wolrd";

function __construct()
{
    $this->data =  test();
}

function __toString()
{
    return $this->data;
}

}