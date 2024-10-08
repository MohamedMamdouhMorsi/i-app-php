<?php 
class IappPost{
    public $postArray ;

    function __construct($apiUrl)
    {
        if($_SERVER['REQUEST_URI'] == $apiUrl && $_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $isAttack = $this->checkForSqlInjection($_POST);
    
            if(!$isAttack){
                if(isset($_POST["msg"])){
                    $msg = $_POST["msg"];
                    $msgSt = base64_decode($msg);
                    $msgIsSave = $this->checkForSqlInjection($msgSt);
                    $msgObj = json_decode($msgSt,true);
                    

                    if(!$msgIsSave){
                        $this->postArray = $msgObj;
                    }else{
                        echo "msg is not save : ". $msgSt;
                        exit();
                    }
                }
                
            }else{
                echo "Error ";
                exit();
            }
           
        }
    }



    public function checkForSqlInjection($postData) {
        // SQL injection pattern
        $sqlInjectionPattern = '/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE)?|INSERT(INTO)?|MERGE|SELECT|UPDATE)\b/i';
    
        // Check if $postData is an array
        if (is_array($postData)) {
            // Iterate through each element of the array
            foreach ($postData as $key => $value) {
                // Check if $value is a string and matches the SQL injection pattern
                if (is_string($value) && preg_match($sqlInjectionPattern, $value, $matches)) {
                    // Potential SQL injection detected, return the matched attack keyword and key
                    return array('keyword' => $matches[0], 'key' => $key);
                }
            }
        }
    
        // No potential SQL injection detected, return false
        return false;
    }

    function __destruct()
    {
        return $this->postArray ;
    }
}