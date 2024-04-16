<?php 

class IappGet{
    public  $getArray ;
    function __construct($apiUrl)
    {
        $url = $_SERVER['REQUEST_URI'];
        $isAttack = $this->checkForSqlInjection($url);
    
        if(!$isAttack){
            $parsedUrl = parse_url($url); 

            if(isset($parsedUrl['path']) &&  isset($parsedUrl['query'])){
                $Url = $parsedUrl['path'];
                $queryString =$parsedUrl['query'] ;

                if($Url == $apiUrl){
                          
                        // Initialize an empty array for the query parameters
                        $this->getArray       = array();

                        // Decode the query string if it's encoded
                        $decodedQuery   = urldecode($queryString);

                        // Explode the query string into key-value pairs
                        $pairs = explode('&', $decodedQuery);
                       
                        if(sizeof($pairs) > 0){
                            
                            for($i = 0 ; $i < sizeof($pairs); $i++){
                                    
                                $pair     = $pairs[$i];
                             
                                $keyValue = explode('=', $pair);
                                    
                                    if(isset($keyValue[0]) && isset($keyValue[1])){
                                       $key             = $keyValue[0];
                                       $value           = $keyValue[1];
                                       $this->getArray[$key]  = $value;
                                    }
                            }

                            
                      
                        }
                }
            }
        }else{
            echo "FY ;)";
            exit();
        }
    }
    public function checkForSqlInjection($getData) {
        // SQL injection pattern
        $sqlInjectionPattern = '/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE)?|INSERT(INTO)?|MERGE|SELECT|UPDATE)\b/i';
    
        // Check if $getData is an array
        if (is_array($getData)) {
            // Iterate through each element of the array
            foreach ($getData as $value) {
                // Check if $value is a string and matches the SQL injection pattern
                if (is_string($value) && preg_match($sqlInjectionPattern, $value, $matches)) {
                    // Potential SQL injection detected, return the matched attack keyword
                    return $matches[0];
                }
            }
        } 
        // Check if $getData is a string and matches the SQL injection pattern
        elseif (is_string($getData) && preg_match($sqlInjectionPattern, $getData, $matches)) {
            // Potential SQL injection detected, return the matched attack keyword
            return $matches[0];
        }
    
        // No potential SQL injection detected, return false
        return false;
    }
    function __destruct()
    {
        return $this->getArray ;
    }
}