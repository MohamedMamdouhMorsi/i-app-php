<?php 

class checkUser {

    function __construct($dbConnection , $userData){
        if(isset($_POST["phonenumber"])){
            $phoneNumber_ = $_POST["phonenumber"];

            return $this->phoneNumber($phoneNumber_,$dbConnection );
        }else if(isset($_POST["deviceToken"])){
            $deviceToken_ = $_POST["deviceToken"];

            return $this->deviceToken($deviceToken_,$dbConnection );
        }else if(isset($_POST["email"])){
            $email_ = $_POST["email"];

            return $this->email($email_,$dbConnection );
        }else if(isset($_POST["username"])){
            $username_ = $_POST["username"];

            return $this->username($username_,$dbConnection );
        }
    }

    public function deviceToken($deviceId ,$dbConnection){

        $data =  $dbConnection->query([
                    "query"=>[
                                [
                                    "n"=>"usersSessions",
                                    "a"=>"get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "deviceToken",
                                                    $deviceId ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                                ]
                            ]
                        ]);

        if(sizeof($data) > 0){

                $sessionData = $data[0];
                $userId   = $sessionData['userId'];

                $userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "id",
                                                    $userId ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);
                
                $userData["userToken"]     = $sessionData["userToken"];
                $userData["deviceToken"]   = $sessionData["deviceToken"];
                $userData["scureToken"]    = $sessionData["scureToken"];
                $userData["connectToken"]  = $sessionData["connectToken"];

                return $userData;

        }else{

            return false;
        
        }



    }
    
    public function phoneNumber($phonenumber ,$dbConnection){


                $userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "phonenumber",
                                                    $phonenumber ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);

                if(sizeof($userData) > 0){
                    return true;

                } else{
                    return false;
                }
 
    }

    
    public function email($email ,$dbConnection){


                $userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "email",
                                                    $email ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);

                if(sizeof($userData) > 0){
                    return true;

                } else{
                    return false;
                }
 
    }

    public function username($username ,$dbConnection){


                $userData = $dbConnection->query([

                    "query"=> [
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
                        ]
                ]);

                if(sizeof($userData) > 0){
                    return true;

                } else{
                    return false;
                }
 
    }

}