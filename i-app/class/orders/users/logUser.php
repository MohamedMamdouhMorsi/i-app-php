<?php
class logUser{

    function __construct($db,$userData)
    {
        if(isset($_POST["id"])){

            $username       = "";
            $password       = "";

            if(isset($_POST["username"]) && isset($_POST["password"])){

                $username        = $_POST["username"];
                $passwordSt      = $_POST["password"];
                $password        = creatAUTH($passwordSt);
                
            }

            $userExist = $db->query([
                                    [
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
                                ]
                            ]);

            if(sizeof($userExist) > 0){
                
                $userDB      = $userExist[0];
                $userId      = $userDB["id"];

                $userpasswod = $db->query([
                                [
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
                                                    $password,
                                                    "eq"
                                                ]
                                            ]
                                        ]
                                ]
                        ]);

                if(sizeof($userpasswod) > 0){

                    $sessionInfo   = new sessionInfo();
                    $deviceInfo    = $sessionInfo->getDeviceInfo();
                    $fingerPrint   = $deviceInfo["fingerPrint"];
                    $timestamp     = new DateTime("now");
                    $authDeviceSt  = "$fingerPrint-$timestamp";
                    $authUserSt    = "$fingerPrint-$timestamp-$password";
                    $authUsername  = "$username-$timestamp";

                    $cureDeviceId  = creatAUTH($authDeviceSt);
                    $cureUserId    = creatAUTH($authUserSt);
                    $userToken     = creatAUTH($authUsername);

                    $userSessions  = $db->query([
                        [
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
                        ]
                ]);

                        if(sizeof($userSessions) > 0){
                            $updateCureSession = $db->query([
                                [
                                    "n"=> "usersSessions",
                                    "a"=> "up",
                                    "l"=> "1",
                                    "d"=>[[3,$cureDeviceId],[4,$cureUserId],[5,'FALSE']],
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
                        ]);

                        $setDeviceId     = new setSession("deviceId",$cureDeviceId);
                        $setUserId       = new setSession("userId",$cureUserId);
                        $setTimestamp    = new setSession("timestamp",$timestamp);

                        $res        = [];
                        $res["res"] = true;
                        echo json_encode($res);
                        exit();
                        
                        }else{

                            $insertNewSession = $db->query([
                                [
                                    "n"=> "usersSessions",
                                    "a"=> "in",
                                    "l"=> "1",
                                    "d"=>[$userId,$userToken,$cureDeviceId,$cureUserId,'FALSE']
                                ]
                        ]);


                       $setDeviceId     = new setSession("deviceId",$cureDeviceId);
                       $setUserId       = new setSession("userId",$cureUserId);
                       $setTimestamp    = new setSession("timestamp",$timestamp);


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

            $res        = [];
            $res["res"] = true;
            echo json_encode($res);
            exit();
        }


    }
}