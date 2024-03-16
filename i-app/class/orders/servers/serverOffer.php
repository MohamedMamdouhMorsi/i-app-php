<?php
class serverOffer{

    function __construct($db,$userData,$msgData)
    {
        if(isset($msgData["dns"]) ){

            $DNS       = $msgData["dns"];

            $userId   = $userData["id"];
            $addOffer = $db->query([
                "query"=>[[
                    "a"=>"up",
                    "n"=>"usersSessions",
                    "d"=>[[5,$DNS]],
                    "q"=>[[[1,$userId,"eq"]]],
                    "l"=>"1"
                ]]
            ]);
     
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}