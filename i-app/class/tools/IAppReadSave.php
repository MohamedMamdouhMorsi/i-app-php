
<?php

use function PHPSTORM_META\type;

class IAppReadSave {

    public $fileData = "No File Data";
    public $fileName = "File Name";
    public $funcKey  = 0;

    public function __construct($str,$fileName_,$userSrcDir) {

        $fileNameV = str_replace('.app', '', $fileName_);

        if ($userSrcDir) {

            
            $fileNameV   = str_replace($userSrcDir, '', $fileNameV);

        }

        $this->fileName =  $fileNameV;

        $strA           = $this->removeComments($str);
        $strB           = $this->convertStrToOb($strA);

        $this->fileData = $strB;

     
    }
    
    private function removeComments($str) {
        return preg_replace('/\/\*[\s\S]*?\*\/|\/\/.*/', '', $str);
    }

    private function funcHandel($str) {
        $ar = explode('fn:', $str);
        if (count($ar) > 1) {
            $ls = '';
            for ($a = 0; $a < count($ar); $a++) {
                if ($a < 1) {
                    $ls = $ar[0];
                }
                $op = 0;
                $cl = 0;
                $vv = '';
                $aft = '';
                $done = false;
                $cr = str_split($ar[$a]);
                
                if ($a > 0 && $ar[$a] !== '') {
                    foreach ($cr as $c) {

                        if (!$done) {

                            if ($c === '{') {
                                $op++;
                            }

                            if ($c === '}') {
                                $cl++;
                            }

                        }

                        if (!$done) {

                            if ($op === $cl) {
                                $done = true;
                            }
                            $vv  .= $c;

                        } else if ($done) {

                            $aft .= $c;
                        }
                    }
                }

                $saveFunc = $this->updateQueryRender($vv,"fun");
                $enc = base64_encode(   $saveFunc );
                $ls .= " fndc: '$enc' $aft";
            }
            return $ls;
        } else {
            return $str;
        }
    }

    private function escapeKeysSym($str) {
        $strArr = str_split($str);
        $start = false;
        $type = '';
        $out = '';
        foreach ($strArr as $cureValue) {
            if ($cureValue == '"') {
                if ($start == true && $type == $cureValue) {
                    $type = '';
                    $start = false;
                } else {
                    $type = '"';
                    $start = true;
                }
            }
            if ($cureValue == "'") {
                if ($start == true && $type == $cureValue) {
                    $type = '';
                    $start = false;
                } else {
                    $type = "'";
                    $start = true;
                }
            }
            if ($start && $cureValue == ':') {
                $out .= 'aaa@aaa';
            } else {
                $out .= $cureValue;
            }
        }
        return $out;
    }

    private function convertStrToOb($str) {

        

        $str = $this->escapeKeysSym($str);
        $str = $this->funcHandel($str);
        $str = preg_replace('/(\r\n|\n|\r)/', '', $str); // remove newlines
        $str = preg_replace('/\/\*[\s\S]*?\*\/|\/\/.*/', '', $str);
        $str = trim($str); // convert to string and remove leading / trailing whitespace
        $str = preg_replace('/(\"\w+\"\s*:\s*[^,\{\[\]]+)\s*(\}|,|\])/', '$1,$2', $str);
        $str = preg_replace('/\s+/', ' ', $str); // replace multiple spaces with single space
        $str = str_replace("\t", ' ', $str); // replace tabs with spaces
        $str = str_replace('\\"', '"', $str); // remove escaped quotes
        $str = preg_replace("/(['\"])?([a-z0-9A-Z_]+)(['\"])?:/", '"$2": ', $str); // add quotes around property names
        $str = str_replace("'", '"', $str); // replace single quotes with double quotes
        $str = preg_replace('/,\s*}/', '}', $str); // remove trailing commas

        // Add missing commas

        $str = str_replace('" "', '" , "', $str); // missing comma
        $str = str_replace('] "', '] , "', $str); // missing comma
        $str = str_replace('} "', '} , "', $str); // missing comma
        $str = str_replace('} {', '} , {', $str); // missing comma
        $str = str_replace('" {', '" , {', $str); // missing comma
        $str = str_replace('""', '" , "', $str); // missing comma
        $str = str_replace(']"', '] , "', $str); // missing comma
        $str = str_replace('}"', '} , "', $str); // missing comma
        $str = str_replace('}{', '} , {', $str); // missing comma
        $str = str_replace('"{', '" , {', $str); // missing comma

        $str = preg_replace('/([a-z0-9A-Z_]+) "/', '$1 , "', $str); // delete last comma comma
        $str = $this->updateQueryRender($str,"obj");
        return $str;
        
    }

    function updateQueryRender($fileContent,$per) {

        $posttxtArray = explode("_IQuery_",$fileContent);
        
        
        if(sizeof($posttxtArray) > 1){

            $newPost    = $posttxtArray[0];
        
            for($i = 1 ; $i < sizeof($posttxtArray);$i++){

                $model              = $posttxtArray[$i];
                $is_query           = $this->is_query_model($model);
                if($is_query){
                
                    $newPost           .= "_IQuery_".$this->getPostObj($model,$this->funcKey,$per);

                }else{

                    $newPost           .= "_IQuery_".$model;
                }
                $this->funcKey = $this->funcKey + 1;
            }
        
            return $newPost;
        }else{
            return $fileContent;
        }
    }

    function is_query_model($model){

        $openObject = false;
        $back       = false;

        for ($i = 0; $i < mb_strlen($model , 'UTF-8'); $i++) {

            $car = mb_substr($model , $i, 1, 'UTF-8');
           
            if($car == ":"){
       
                $openObject = true;
            }

            if($openObject && $car == "["){
               
                $back =  true;
                $i =  mb_strlen($model , 'UTF-8') + 1;
            }

            if($car !== "[" && $car !== '"' &&$car !== "'" && $car !== ' '&&  $car !== ':'){
                
              
                $i =  mb_strlen($model , 'UTF-8') + 1;
            }
            
        }
      
        return $back;
    }
    function replaceTemplateStrings($model) {
            $res = '';    
        for ($i = 0; $i < mb_strlen($model , 'UTF-8'); $i++) {

            $car = mb_substr($model , $i, 1, 'UTF-8');
            $next =  mb_substr($model , $i + 1, 1, 'UTF-8');
            $before =  mb_substr($model , $i - 1, 1, 'UTF-8');
            if($car == '"' && $next == '$'){
                $res .= '`'; 
            }else if($before == '}' && $car == '"'  ){
                $res .= '`'; 
            }else{
                $res .=  $car; 
            }
        }
         return $res;
    }
    function getPostObj($model,$key,$per){

        $before     = "";
        $after      = "";
        $resultObj  = "";
        $openFun    = 0;
        $closeFun   = 0;
        $print      = false;
        $printAfter = false;
        $select = false;
        $newObj     = $model;
      
      
          
            for ($i = 0; $i < mb_strlen($model , 'UTF-8'); $i++) {

                $car = mb_substr($model , $i, 1, 'UTF-8');
                
                if($car == "[" && ! $select){
                    $print = true;
                    $openFun++;
                }

                if($car == "]"){
                    $closeFun++;
                }

                if($print){
                    $resultObj .= $car;
                }else{
                    if(!$printAfter){
                    $before    .= $car;
                    }
                }

                if($printAfter){
                    $after    .= $car;
                }

                if($openFun > 0 && $openFun == $closeFun){
                $print       = false;
                $printAfter  = true;
                $select = true;
                }

            }

            if($per == "obj"){
                $newObj     = $this->convertQueryObj($resultObj,$key);
            }else{
              
                $newObj_     = $this->convertQueryFun($resultObj,$key);
                $newObj      = $this->replaceTemplateStrings($newObj_ );
             
            }
        
        $stringObj  = $before.$newObj.$after;
        return $stringObj;
        
            

    }
    function convertQueryObj($jsonString,$key) {
    
     
        $toJson    = json_decode(  $jsonString,true);
   
        $queryArray = [];
        if($toJson !== null){
        
        for($i = 0 ; $i < sizeof($toJson); $i++){
            
            $query   = $toJson[$i];
            $queryId = $this->fileName."_".$key."_".$i."_obj";
           
                if(isset($query["a"])){
                
                        $action = $query["a"];

                        if($action == "get"){
                            $QE = $this->getInput($query["q"]);
                            $newQ =["q"=>$QE , "id"=>$queryId];
                            array_push($queryArray ,$newQ);
                        }elseif ($action == "getJ"){
                            $QE  = $this->getInput($query["q"]);
                         
                            $QEJ = $this->getJInput($query["j"]);
                           
                            $newQ =["q"=>$QE ,"j"=> $QEJ,"id"=>$queryId];
                            array_push($queryArray ,$newQ);
                        }elseif  ($action == "in"){
                            $QD  = $query["d"];
                            $newQ =["d"=>$QD ,"id"=>$queryId];
                            array_push($queryArray ,$newQ);
                        }elseif  ($action == "up"){
                            $QE  = $this->getInput($query["q"]);
                            $QD  = $this->dataInput($query["d"]);
                            $newQ =["q"=>$QE ,"d"=> $QD,"id"=>$queryId];
                            array_push($queryArray ,$newQ);
                        }elseif  ($action == "del"){
                            $QE  = $this->getInput($query["q"]);
                            $newQ =["q"=>$QE ,"id"=>$queryId];
                            array_push($queryArray ,$newQ);
                        }
                }  
        }

        if(sizeof($queryArray) > 0){
        
            $backObject   =  json_encode($queryArray);
            
    
            return $backObject;
        }else{
            return $jsonString;

        }
        
        }else{
        
            return $jsonString;
        }
    }
    function isNumber($input) {
        // Using the unary plus operator to convert the input to a number
        // If the input is not a valid number, is_numeric() will return false
        return is_numeric($input);
    }
    function fixAndParseJSON($inputString) {
        // Replace variable names with double quoted strings
        $fixedString = preg_replace_callback('/(?<=[:,\[\s])\b[\w.]+\b(?=[,\]\s}])/', function($match) {
            if ($match[0] !== 'true' && $match[0] !== 'false') {
                if ($this->isNumber($match[0])) {
                    return $match[0];
                } else {
                    return '"${'.$match[0].'}"';
                }
            } else {
                return $match[0];
            }
        }, $inputString);
    
        // Parse the fixed string as JSON
        return $fixedString;
    }
    function convertQueryFun($jsonString,$key) {

        $jsonString = str_replace("'", '"', $jsonString); 
        $jsonString = preg_replace("/(['\"])?([a-z0-9A-Z_]+)(['\"])?:/", '"$2": ', $jsonString); // add quotes around property names
        $jsonString = $this->fixAndParseJSON($jsonString);
        
        $toJson     = json_decode(  $jsonString,true);
        
        $queryArray = [];

        if($toJson !== null){
               
                for($i = 0 ; $i < sizeof($toJson); $i++){
                    
                    $query   = $toJson[$i];
                    $queryId = $this->fileName."_".$key."_".$i."_fun";

                        if(isset($query["a"])){
                        
                                $action = $query["a"];

                                if($action == "get"){
                                    $QE = $this->getInput($query["q"]);
                                    $newQ =["q"=>$QE , "id"=>$queryId];
                                    array_push($queryArray ,$newQ);
                                }elseif ($action == "getJ"){
                                    $QE  = $this->getInput($query["q"]);
                                    $QEJ = $this->getJInput($query["j"]);
                                    $newQ =["q"=>$QE ,"j"=> $QEJ,"id"=>$queryId];
                                    array_push($queryArray ,$newQ);
                                }elseif  ($action == "in"){
                                    $QD  = $query["d"];
                                    $newQ =["d"=>$QD ,"id"=>$queryId];
                                    array_push($queryArray ,$newQ);
                                }elseif  ($action == "up"){
                                    $QE  = $this->getInput($query["q"]);
                                    $QD  = $this->dataInput($query["d"]);
                                    $newQ =["q"=>$QE ,"d"=> $QD,"id"=>$queryId];
                                    array_push($queryArray ,$newQ);
                                }elseif  ($action == "del"){
                                    $QE  = $this->getInput($query["q"]);
                                    $newQ =["q"=>$QE ,"id"=>$queryId];
                                    array_push($queryArray ,$newQ);
                                }
                        }  
                }

                if(sizeof($queryArray) > 0){
                
            
                    $backFile     = json_encode($queryArray);
            
                    return $backFile;
                }else{
                    return $jsonString;

                }
                
        }else{
        
            return $jsonString;
            
        }
    }

    function isJsVar($val) {
        if($val && is_string($val)){
        $valAr = str_split($val);
        $len = count($valAr) - 1;
            if ($valAr[0] == '$' && $valAr[1] == '{' && $valAr[$len] == '}') {
                return true;
            }else{
                return false;
            }
        }
    }
    
    function getInput($QE){
        $result = [];
        for( $or = 0 ; $or < sizeof($QE); $or++){
            $orOB = $QE[$or];
           
            for( $and = 0 ; $and < sizeof($orOB); $and++){
                
                    $andOB = $orOB[$and];
                   
                    $value = $andOB[1];
                   
                    if(is_array( $value  ) || is_object( $value  )){
                     
                        if(isset($value["t"]) !== "q"){
                            array_push($result,$value);
                        }
                    }else if($this->isJsVar($value)){
                        array_push($result,$value);
                    }
            }
        }
        return $result;
    }

    function getJInput($QEJ){
       
        $result = [];
        for( $ob = 0 ; $ob < sizeof($QEJ); $ob++){
            $QE = $QEJ[$ob]["q"];
            for( $or = 0 ; $or < sizeof($QE); $or++){
                $orOB = $QE[$or];
                for( $and = 0 ; $and < sizeof($orOB); $and++){
                        $andOB = $orOB[$and];
                        $value = $andOB[1];
                        if(is_object( $value  )){
                            if(isset($value["t"] )!== "q"){
                                array_push($result,$value);
                            }
                        }else if($this->isJsVar($value)){
                            array_push($result,$value);
                        }
                }
            }
        }
        return $result;
    }

    function dataInput($QE){
        $result = [];
       ///
       for($i = 0 ; $i < sizeof($QE); $i++){
            $input = $QE[$i][1];
            array_push($result,$input);
       }
        return $result;
    }

    public function __toString()
    {
        return (string) $this->fileData;
    }
}

   
?>
