<?php
class setUserOffline{

    function __construct($db)
    {
        if(isset($_POST["id"])){

            $userId       = $_POST["id"];

   
            $insertUser = $db->query([
                "query"=>[
                    "a"=>"up",
                    "n"=>"usersSessions",
                    "d"=>[[5,"FALSE"]],
                    "q"=>[[[1,$userId ,'eq']]],
                    "l"=>"1"
                ]
            ]);
           
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}