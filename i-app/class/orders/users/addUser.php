<?php
class addUser{

    function __construct($db,$msgData)
    {
        if(isset($msgData["data"])){

            $msgData_ = $msgData["data"];

        if(isset($msgData_["username"]) && isset($msgData_["firstname"]) && isset($msgData_["lastname"])){
           
            $username       = $msgData_["username"];
            $firstname      = $msgData_["firstname"];
            $lastname       = $msgData_["lastname"];
            $email          = $msgData_["email"];
            $phonenumber    = $msgData_["phonenumber"];
            $accountType    = $msgData_["accountType"];
            $passwordSt    = $msgData_["password"];
            $password        = new CreatAUTH($passwordSt);
   
            $insertUser = $db->query([
                "query"=>[[
                    "a"=>"in",
                    "n"=>"users",
                    "d"=>[$username,$firstname , $lastname,$email ,$phonenumber , $accountType,'1']
                ]]
            ]);
            
            $insertPass = $db->query([
                "query"=>[[
                    "a"=>"in",
                    "n"=>"usersPasswords",
                    "d"=>[$insertUser,$password]
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

    }else{
        echo json_encode($msgData);
        exit(); 
    }
    }
}