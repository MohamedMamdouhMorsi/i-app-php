<?php


class destroySession{
  public $errorTxt = "No";
    function __construct($msg)
    {
        $this->errorTxt = $msg;
     
        if(isset($_SESSION["deviceId"]) ){
            $_SESSION = array();
            session_destroy();  
        }


        if(isset($_COOKIE["deviceId"]) ){
            setcookie("deviceId", '', strtotime( '-5 days' ), '/');
        }
    
        if(isset($_COOKIE["userId"]) ){
            setcookie("userId", '', strtotime( '-5 days' ), '/');
            }
        if(isset($_COOKIE["timestamp"]) ){
            setcookie("timestamp", '', strtotime( '-5 days' ), '/');
        }
        
    }

    function __destruct()
    {
     
        /*         
            header('Location: /https://google.com' );
            exit(); 
        */

    echo "Error : ".$this->errorTxt;
    exit();
    }
}
?>