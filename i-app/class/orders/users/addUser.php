<?php
class addUser{

    function __construct($db)
    {
        if(isset($_POST["username"]) && isset($_POST["firstname"]) && isset($_POST["lastname"])){

            $username       = $_POST["username"];
            $firstname      = $_POST["firstname"];
            $lastname       = $_POST["lastname"];
            $email          = $_POST["email"];
            $phonenumber    = $_POST["phonenumber"];
            $accountType    = $_POST["accountType"];

   
            $insertUser = $db->query([
                "query"=>[
                    "a"=>"in",
                    "n"=>"answers",
                    "d"=>[$username,$firstname , $lastname,$email ,$phonenumber , $accountType,'1']
                ]
            ]);
            $inseredtUserId = $insertUser; 
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}