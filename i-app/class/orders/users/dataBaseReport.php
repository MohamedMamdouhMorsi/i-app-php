<?php
class dataBaseReport{

    function __construct($db)
    {

            $insertUser = $db->query([
                "query"=>[
                    "a"=>"check",
                    "n"=>"users" ]
            ]);

            

            $res        = [];

            if(sizeof($insertUser) > 0){
                $res["res"] = true;
            }else{
                $res["res"] = false;
            }
            
            echo json_encode($res);
            exit();
        


    }
}