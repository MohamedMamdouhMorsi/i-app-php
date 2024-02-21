<?php
class icons{
    function __construct()
    {
        $dirBasic = realpath(__DIR__ . '/../..');
        $dir      = $dirBasic."/asset/db/icons.json";
        $file     = file_get_contents($dir,true);
        $res=[];
        $rea["res"] = json_decode($file,true);
        echo json_encode($res);
        exit();
    }
}