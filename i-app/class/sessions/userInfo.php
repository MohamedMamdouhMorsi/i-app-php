<?php 

class userInfo {
    public $userData = ['id' => 0];
    function __construct($dbConnection)
    {

        
        $timestamp_  = time();
        
        $sessionData = new sessionInfo();
        $deviceInfo  = $sessionData->getDeviceInfo();

        $fingerPrint = $deviceInfo['fingerPrint'];

        $deviceId    = new isSession("deviceId");
        $timestamp   = new isSession("timestamp");
        $userId      = new isSession("userId");
        
        // Check if required cookie values are set 

        if ($userId != 'FALSE' && $deviceId  != 'FALSE' && $timestamp  != 'FALSE' ) {

            $authSt       = "$fingerPrint-$timestamp";

            $cureDeviceId = new CreatAUTH($authSt);

            
            // Validate device ID
            $stcureDeviceId = "$cureDeviceId";
            if ($deviceId == $stcureDeviceId) {

                    // Check User Data in DB
                   

                    $checkUserData = new checkUser($dbConnection,["deviceToken"=>$stcureDeviceId]);
              
                  

                    if(!$checkUserData){

                        new destroySession("Try To Login again #1");
                    }else{
                        $this->userData = $checkUserData->userData;
                     
                    }

                }else{

                      new destroySession("Try To Login again #2".$deviceId."_".$stcureDeviceId);
                  
                }

        }
    
    }
    function __destruct()
    {
        return $this->userData;
    }
    
}