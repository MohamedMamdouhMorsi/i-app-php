<?php
class serverAnswer{

    function __construct($db,$userData)
    {
        if(isset($_POST["oid"]) && isset($_POST["dns"]) && isset($_POST["owner"])){

            $OId       = $_POST["oid"];
            $DNS       = $_POST["dns"];
            $owner     = $_POST["owner"];
            $token   = $userData["deviceToken"];
            $insertAnswer = $db->query([
                "query"=>[
                    "a"=>"in",
                    "n"=>"answers",
                    "d"=>[$OId,$owner, $token,$DNS]
                ]
            ]);
     
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}