<?php
class countries{
    function __construct()
    {
        $dirBasic = realpath(__DIR__ . '/../..');
        $dir      = $dirBasic."/asset/db/countries.json";
        $file     = file_get_contents($dir,true);
      
        echo '{"res":'.$file.'}';
        exit();
    }
}