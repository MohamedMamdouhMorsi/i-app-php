<?php
class addUser{

    function __construct($db,$msgData)
    {
        if(isset($msgData["username"]) && isset($msgData["firstname"]) && isset($msgData["lastname"])){

            $username       = $msgData["username"];
            $firstname      = $msgData["firstname"];
            $lastname       = $msgData["lastname"];
            $email          = $msgData["email"];
            $phonenumber    = $msgData["phonenumber"];
            $accountType    = $msgData["accountType"];

   
            $insertUser = $db->query([
                "query"=>[[
                    "a"=>"in",
                    "n"=>"answers",
                    "d"=>[$username,$firstname , $lastname,$email ,$phonenumber , $accountType,'1']
                ]]
            ]);
            
            $res        = [];
            $res["res"] = $insertUser;
            echo json_encode($res);
            exit();
        }else{
            echo json_encode($msgData);
            exit(); 
        }


    }
}