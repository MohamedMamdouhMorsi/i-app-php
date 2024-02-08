<?php
class icons{
    function __construct()
    {
        $dirBasic = realpath(__DIR__ . '/../..');
        $dir      = $dirBasic."/asset/db/icons.json";
        $file     = file_get_contents($dir,true);
        echo $file;
        exit();
    }
}