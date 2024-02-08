<?php
class getAnswer{

    function __construct($db,$userData)
    {

        $userId  = $userData["id"];
        $token   = $userData["deviceToken"];
        $answers = $db->query([
            "query"=>[
                "a"=>"get",
                "n"=>"answers",
                "q"=>[[["userId",$userId,"eq"]]],
                "l"=>"0"
            ]
        ]);
        $deleteAnswers = $db->query([
            "query"=>[
                "a"=>"del",
                "s"=>["A"],
                "n"=>"answers",
                "q"=>[[["ownerId",$token,"eq"]]],
                "l"=>"0"
            ]
        ]);
        
        $res        = [];
        $res["res"] = $answers;
        echo json_encode($res);
        exit();

    }
}