<?php
class serverAnswer{

    function __construct($db,$userData,$msgData )
    {
        if(isset($msgData["oid"]) && isset($msgData["dns"]) && isset($msgData["owner"])){

            $OId       = $msgData["oid"];
            $DNS       = $msgData["dns"];
            $owner     = $msgData["owner"];
            $token     = $userData["deviceToken"];

            $insertAnswer = $db->query([
                "query"=>[[
                    "a"=>"in",
                    "n"=>"answers",
                    "d"=>[$OId,$owner, $token,$DNS]
                ]]
            ]);
     
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}