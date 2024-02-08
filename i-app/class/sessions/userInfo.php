<?php 

class userInfo {

    function __construct($dbConnection)
    {

        $userData = ['id' => 0];
        $timestamp_  = new DateTime('now');
        
        $sessionData = new sessionInfo();
        $deviceInfo  = $sessionData->getDeviceInfo();

        $fingerPrint = $deviceInfo['fingerPrint'];

        $deviceId    = new isSession("deviceId");
        $timestamp   = new isSession("timestamp");
        $userId      = new isSession("userId");
        
        // Check if required cookie values are set 

        if ($userId != 'FALSE' && $deviceId  != 'FALSE' && $timestamp  != 'FALSE' ) {

            $authSt       = "$fingerPrint-$timestamp";

            $cureDeviceId = createAuth($authSt);

            
            // Validate device ID

            if ($deviceId === $cureDeviceId) {

                    // Check User Data in DB

                    $checkUserData = new checkUser();

                    $userData      = $checkUserData->deviceToken($deviceId , $dbConnection);  

                    if(!$userData){

                        new destroySession("Try To Login again #1");
                    }else{
                        return $userData;
                    }

                }else{

                    // Check User Data in DB

                    $checkUserData = new checkUser();

                    $userData      = $checkUserData->deviceToken($deviceId , $dbConnection);  

                    if(!$userData){

                        new destroySession("Try To Login again #2");
                    
                    }else{

                        return $userData;
                    
                    }
                    
                }

        }else{
            return $userData;
        }
    
    }
    
}