<?php
class logUser{

    function __construct($db,$msgData)
    {

        if(isset($msgData["data"])){
           
            $msgData_ = $msgData["data"];

            if(isset($msgData_["username"]) && isset($msgData_["password"])){

                $username        = $msgData_["username"];
                $passwordSt      = $msgData_["password"];
                $password        = new CreatAUTH($passwordSt);
                
            

            $userExist = $db->query([
                "query"=>[ [
                                "n"=> "users",
                                "a"=> "get",
                                "l"=> "1",
                                "q"=>[
                                        [
                                            [
                                                "username",
                                                $username ,
                                                "eq"
                                            ]
                                        ]
                                ]
                                ]]
                            ]);

            if(sizeof($userExist) > 0){
                
                $userDB      = $userExist[0];
                $userId      = $userDB["id"];
              
                $userpasswod = $db->query([
                    "query"=>[[
                                    "n"=> "usersPasswords",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "userId",
                                                    $userId ,
                                                    "eq"
                                                ],[
                                                    "password",
                                                    "$password",
                                                    "eq"
                                                ]
                                            ]
                                        ]
                                ]]
                        ]);
                     
                if(sizeof($userpasswod) > 0){
                  
                    $sessionInfo   = new sessionInfo();
                    $deviceInfo    = $sessionInfo->getDeviceInfo();
                    $fingerPrint   = $deviceInfo["fingerPrint"];
                    $timestamp     = time();
                    $authDeviceSt  = "$fingerPrint-$timestamp";
                    $authUserSt    = "$fingerPrint-$timestamp-$password";
                    $authUsername  = "$username-$timestamp";

                    $cureDeviceId  = new CreatAUTH($authDeviceSt);
                    $cureUserId    = new CreatAUTH($authUserSt);
                    $userToken     = new CreatAUTH($authUsername);
                    $cureDeviceId  = "$cureDeviceId";
                    $cureUserId    = "$cureUserId";
                    $userToken     = "$userToken";                   
                    $userSessions  = $db->query([
                                    "query"=>[ [
                                        "n"=> "usersSessions",
                                        "a"=> "get",
                                        "l"=> "1",
                                        "q"=>[
                                                [
                                                    [
                                                        "userId",
                                                        $userId ,
                                                        "eq"
                                                    ]
                                                ]
                                            ]
                                    ]]
                            ]);
                
                        if(sizeof($userSessions) > 0){
                            $updateCureSession = $db->query([
                                                    "query"=>[ 
                                                                [
                                                                    "n"=> "usersSessions",
                                                                    "a"=> "up",
                                                                    "l"=> "1",
                                                                    "d"=>[ [3,$cureDeviceId], [4,$cureUserId], [5,'FALSE'] ],
                                                                    "q"=>[
                                                                            [
                                                                                [
                                                                                    "userId",
                                                                                    $userId ,
                                                                                    "eq"
                                                                                ]
                                                                            ]
                                                                        ]
                                                                ] 
                                                            ]
                                                    ]);
                       
                        $setDeviceId     = new setSession("deviceId", $cureDeviceId);
                        $setUserId       = new setSession("userId", $cureUserId);
                        $setTimestamp    = new setSession("timestamp", $timestamp);

                        $res        = [];
                        $res["res"] = true;
                        echo json_encode($res);
                        exit();
                        
                        }else{

                         


                       $setDeviceId     = new setSession("deviceId",$cureDeviceId);
                       $setUserId       = new setSession("userId",$cureUserId);
                       $setTimestamp    = new setSession("timestamp",$timestamp);
                       $insertNewSession = $db->query([
                                                    "query"=>[[
                                                        "n"=> "usersSessions",
                                                        "a"=> "in",
                                                        "l"=> "1",
                                                        "d"=>[$userId,$userToken,$cureDeviceId,$cureUserId,'FALSE']
                                                    ]]
                                            ]);

                        $res        = [];
                        $res["res"] = true;
                        echo json_encode($res);
                        exit();
                        }


                }else{
                    $res        = [];
                    $res["res"] = false;
                    echo json_encode($res);
                    exit();     
                }


            }else{

                $res        = [];
                $res["res"] = false;
                echo json_encode($res);
                exit();
            }
        }
            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}