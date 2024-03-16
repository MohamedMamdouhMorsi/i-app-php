<?php
class logoutUser{

    function __construct($db,$userData)
    {
      
        if(isset($userData["id"])){

            $userId       = $userData["id"];
            if(isset($_SESSION["deviceId"]) ){
                $_SESSION["deviceId"] = "FALSE";
            }
            if(isset($_SESSION["userId"]) ){
                $_SESSION["userId"] = "FALSE";
            }
        
            if(isset($_COOKIE["deviceId"]) ){
            setcookie("deviceId", '', strtotime( '-5 days' ), '/');
            }
            if(isset($_COOKIE["userId"]) ){
                setcookie("userId", '', strtotime( '-5 days' ), '/');
                }
   
            $insertUser = $db->query([
                "query"=>[
                    
                        [
                    "a"=>"up",
                    "n"=>"usersSessions",
                    "d"=>[[3,"FALSE"],[5,"FALSE"]],
                    "q"=>[[[1,$userId ,'eq']]],
                    "l"=>"1"
                        ]
                ]
            ]);
           
       
        }
       

    }
    function __destruct()
    {
        return true;
    }
}