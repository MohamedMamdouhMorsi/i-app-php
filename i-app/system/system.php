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
            $i_app_middleWare = new middleWare($appData,$dir,$cleanedObject );
            $this->data = $i_app_middleWare->middleWareApp();
         
        }else{
            
            $this->data = $dir;
         
        }
    }
    function __toString()
    {
        return (string) $this->data;
    }
}