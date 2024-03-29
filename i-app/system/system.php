<?php

class system {
    public  $data = "No App Data";
    function __construct($dir)
    {
        $loadeAppFile = $dir."/i.app";
        $dbAppFile    = $dir."/db.app";
        
        if(file_exists($loadeAppFile)){

            $i_app_file    = file_get_contents($loadeAppFile,true);
            $cleanedObject = new IAppReader($i_app_file);
            $appData       = json_decode($cleanedObject,true);
            
            if(file_exists($dbAppFile)){
                
                $dbConnection  = new db($dbAppFile);
                if(!$dbConnection->chickDB()){
                    new dataBaseUpddate($dbConnection,$dir,$appData);
                }
              
                new middleWare($appData,$dir,$cleanedObject ,$dbConnection,$loadeAppFile);
            }else{

                $dbConnection  = false;
                new middleWare($appData,$dir,$cleanedObject ,$dbConnection,$loadeAppFile);
            
            }
        
        }else{

                echo "Please insert i.app file to your dir ".$dir;

                exit();
         
        }
    }
   
}