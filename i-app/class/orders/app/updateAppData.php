<?php 
class updateAppData{
    function __construct($path)
    {
        if(isset($_POST["data"])){
            $data = $_POST["data"];
            $dataSt = json_encode($data);
            $dataApp = new IAppFileMaker($dataSt);
            $handl = fopen($path,"w");
            fwrite($handl, $dataApp);
            fclose($handl);
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }else{
            $res        = [];
            $res["res"] = false;
            echo json_encode($res);
            exit();
        }
    }
}