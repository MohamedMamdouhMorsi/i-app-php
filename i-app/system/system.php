<?php

class system {
    public  $data = "No App Data";
    function __construct($dir)
    {
        $loadeAppFile = $dir."/i.app";
        
        if(file_exists($loadeAppFile)){
            $i_app_file = file_get_contents($loadeAppFile,true);
            $iAppReader = new IAppReader();
            $cleanedObject = $iAppReader->readIAppFile($i_app_file);
            $appData = json_decode($cleanedObject,true);
            $this->data = new middleWare($appData,$dir);

         
        }else{
            
            $this->data = $dir;
         
        }
    }
    function __toString()
    {
        return (string) $this->data;
    }
}