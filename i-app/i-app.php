<?php

class i_app{
public  $data = "No data QVC";

function __construct($_dir)
{
    $this->data = new system($_dir);
  
}

function __toString()
{
    return (string) $this->data;
}

}
