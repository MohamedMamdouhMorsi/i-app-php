<?php 
class  updateTranslate{
    function __construct( $dir, $i_app)
    {
       
        if(isset($_POST["lang"]) && isset($_POST["data"]) ){
            $langName   = $_POST["lang"];
            $langData_  = $_POST["data"];
            $langData   = json_encode($langData_);
            if(
                isset($i_app["dir"]) && 
                isset($i_app["dir"]["txt"])
            ){
                
                /////
                $fileName = $langName.".json";
                $fileDir  = $dir."/".$fileName;

                $handl = fopen($fileDir,"w");
                fwrite($handl, $langData);
                fclose($handl);

                $res        = [];
                $res["res"] = true;
                echo json_encode($res);
                exit();
            }
    }
    }
}