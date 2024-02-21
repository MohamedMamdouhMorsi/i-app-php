<?php 

class checkUser {
    public $userData = [];
    function __construct($dbConnection , $msgData){
        if(isset($msgData["phonenumber"])){
            $phoneNumber_ = $msgData["phonenumber"];
          
            return $this->phoneNumber($phoneNumber_,$dbConnection );
        }else if(isset($msgData["deviceToken"])){
            $deviceToken_ = $msgData["deviceToken"];

            return $this->deviceToken($deviceToken_,$dbConnection );
        }else if(isset($msgData["email"])){
            $email_ = $msgData["email"];

            return $this->email($email_,$dbConnection );
        }else if(isset($msgData["username"])){
            $username_ = $msgData["username"];

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
                                                    "$deviceId" ,
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

                $userData_ = $dbConnection->query([

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
                if(sizeof($userData_) > 0){
                    $this->userData = $userData_[0];
                    $this->userData["userToken"]     = $sessionData["userToken"];
                    $this->userData["deviceToken"]   = $sessionData["deviceToken"];
                    $this->userData["scureToken"]    = $sessionData["scureToken"];
                    $this->userData["connectToken"]  = $sessionData["connectToken"];
                    
                    
                    return  $this->userData;
                }else{
                    return false;
                }

        }else{

            return false;
        
        }



    }
    
    public function phoneNumber($phonenumber ,$dbConnection){


                $this->userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "phonenumber",
                                                    "$phonenumber" ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);

                $res        = [];

                if(sizeof($this->userData) > 0){
                    $res["res"] = true;
                }else{
                    $res["res"] = false;
                }
                
                echo json_encode($res);
                exit();
 
    }

    
     function email($email ,$dbConnection){


                $this->userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "email",
                                                    "$email" ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);

                $res        = [];

                if(sizeof($this->userData) > 0){
                    $res["res"] = true;
                }else{
                    $res["res"] = false;
                }
                
                echo json_encode($res);
                exit();
    }

     function username($username ,$dbConnection){


                $this->userData = $dbConnection->query([

                    "query"=> [
                                [
                                    "n"=> "users",
                                    "a"=> "get",
                                    "l"=> "1",
                                    "q"=>[
                                            [
                                                [
                                                    "username",
                                                    "$username" ,
                                                    "eq"
                                                ]
                                            ]
                                    ]
                            ]
                        ]
                ]);

               
                $res        = [];

                if(sizeof($this->userData) > 0){
                    $res["res"] = true;
                }else{
                    $res["res"] = false;
                }
                
                echo json_encode($res);
                exit();
    }

}