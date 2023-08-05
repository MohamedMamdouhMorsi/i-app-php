<?php

class system {
    public  $data = "No App Data";
    function __construct($dir)
    {
        $loadeAppFile = $dir."/i.app";
        
        if(file_exists($loadeAppFile)){
            $i_app_file = file_get_contents($loadeAppFile,true);
          
            $cleanedObject = new IAppReader($i_app_file);
            $appData = json_decode($cleanedObject,true);
             new middleWare($appData,$dir,$cleanedObject );
             
        }else{
            echo "Please insert i.app file to your dir ".$dir;
          exit();
         
        }
    }
   
}