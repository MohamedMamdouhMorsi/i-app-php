<?php 

class checkUser {
    public $userData = [];
    public $userConnection = [];
    public $permissions = [];
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
        }else if(isset($msgData["getConnectionOffer"])){
            $username_ = $msgData["getConnectionOffer"];

            return $this->getConnectionOffer($dbConnection,$username_ );
        }else if(isset($msgData["getPermissions"])){
            $username_ = $msgData["getPermissions"];

            return $this->getPermissions($dbConnection,$username_ );
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
                    
                    
                    
                    return  $this->userData;
                }else{
                    return false;
                }

        }else{

            return false;
        
        }



    }
    public function getConnectionOffer($dbConnection , $userDataToken){
        $dataSt =  json_encode($userDataToken);
        $datJson = json_decode($dataSt,true);
    
        if(isset($datJson["id"])){
        $userId = $datJson["id"];

        $onlineTwinsAr =  $dbConnection->query([
            "query"=>[
                        [
                            "n"=>"usersSessions",
                            "a"=>"get",
                            "l"=> "0",
                            "q"=>[
                                    [
                                        [
                                            "userId",
                                            "$userId" ,
                                            "uneq"
                                        ],[
                                            "connectToken",
                                            "FALSE" ,
                                            "uneq"
                                        ]
                                    ]
                            ]
                        ]
                    ]
                ]); 
             
        $connect = [];
        if(sizeof($onlineTwinsAr ) > 0) {
            $connectUserId =$onlineTwinsAr[0]["userId"];
            $connect["ID"] = $onlineTwinsAr[0]["userId"];
            $connect["UT"] = $onlineTwinsAr[0]["userToken"];
            $connect["DT"] = $onlineTwinsAr[0]["deviceToken"];
            $connect["CT"] = $onlineTwinsAr[0]["connectToken"];

            $deleteConnectionAnswers =  $dbConnection->query([
                "query"=>[
                            [
                                "n"=>"answers",
                                "a"=>"del",
                                "l"=> "0",
                                "q"=>[
                                        [
                                            [
                                                2,
                                                $connectUserId ,
                                                "eq"
                                            ]
                                        ]
                                ]
                            ]
                        ]
                    ]); 
            $deleteConnectionOffer =  $dbConnection->query([
                        "query"=>[
                                    [
                                        "n"=>"usersSessions",
                                        "a"=>"up",
                                        "d"=>[[5,"FALSE"]],
                                        "l"=> "1",
                                        "q"=>[
                                                [
                                                    [
                                                       1,
                                                        $connectUserId ,
                                                        "eq"
                                                    ]
                                                ]
                                        ]
                                    ]
                                ]
                            ]); 
  
     $this->userConnection =  $connect;
            return $this->userConnection;
        }else{
            return ["nodata"=>true];
        }
    }
     
    }
    function getPermissions($dbConnection,$userDataToken){
        $dataSt =  json_encode($userDataToken);
        $datJson = json_decode($dataSt,true);
   
        if(isset($datJson["userType"])){
        $typeId = $datJson["userType"];
       
        $permissions_ =  $dbConnection->query([
            "query"=>[
                        [
                            "n"=>"appsPermissions",
                            "a"=>"getJ",
                            "l"=> "0",
                            "s"=>["A"],
                            "q"=>[
                                    [
                                        [
                                            2,
                                            $typeId ,
                                            "eq"
                                        ]
                                    ]
                                ],
                            "j"=>[
                                    [
                                        "n"=>"permissions",
                                        "q"=>[
                                            [
                                                [
                                                    1,[
                                                        "t"=>"q",
                                                        "d"=>"permissionId"
                                                    ],
                                                    "eq"
                                                ]
                                            ]
                                                ],
                                        "s"=>["A"],
                                        "l"=>"1"
                                    ], [
                                            "n"=>"usersApps",
                                            "q"=>[
                                                [
                                                    [
                                                        1,[
                                                            "t"=>"q",
                                                            "d"=>"appId"
                                                        ],
                                                        "eq"
                                                    ]
                                                ]
                                                    ],
                                            "s"=>["appName"],
                                            "l"=>"1"
                                    ]
                            ]
                        ]
                    ]
                ]); 
                
                $this->permissions = $permissions_ ;
               
                return $this->permissions ;
                
            }else{
                return [];
            }
    }

    function phoneNumber($phonenumber ,$dbConnection){


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