<?php
class currency{
    function __construct()
    {
        $dirBasic = realpath(__DIR__ . '/../..');
        $dir      = $dirBasic."/asset/db/currency.json";
        $file     = file_get_contents($dir,true);
      
        echo '{"res":'.$file.'}';
        exit();
    }
}