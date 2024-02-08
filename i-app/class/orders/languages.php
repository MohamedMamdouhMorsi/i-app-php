<?php
class languages{
    function __construct()
    {
        $dirBasic = realpath(__DIR__ . '/../..');
        $dir      = $dirBasic."/asset/db/languages.json";
        $file     = file_get_contents($dir,true);
        echo $file;
        exit();
    }
}