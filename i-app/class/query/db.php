<?php

class db {
    
        private $i_app = [] ;

        function __construct($dir) {

                
            $i_app_st       = file_get_contents($dir,true);
            $dbConfigSt     = new IAppReader($i_app_st);
            $dbConfigOb     = json_decode($dbConfigSt,true);
            
            
            if(isset($dbConfigOb['mysql']) && isset($dbConfigOb['mysql'][0]) ){

                $this->i_app    = $dbConfigOb['mysql'][0];
                
            }else{

                echo  "No config data";
                exit();
            
            }

        }

        function connect(){
            
            $host     = $this->i_app['host'];
            $user     = $this->i_app['user'];
            $password = $this->i_app['password'];
            $database = $this->i_app['database'];
        
            $c        = null ;
            $c        = mysqli_connect($host,$user,$password,$database);
        
                // Evaluate the connection

                if (mysqli_connect_errno()) {

                    echo mysqli_connect_error();
                    exit();

                }
            
                return $c;

        
        }
        function setIappTables($tables){
            $this->i_app['tables']       = $tables;
        }
        function getColumn($body,$tables){

                $queryOB = $body['query'];
                $selectColumn = [];
               
                foreach ($queryOB as $cureOB) {

                    $querySelect = ["A"]; 
                    
                    if(isset($cureOB['s'])){
                    
                        $querySelect = $cureOB['s'];
                    
                    }

                    $tableName   = $cureOB['n'];
                    $table       = $tables[$tableName];

                    if(sizeof($querySelect)){

                    
                            if($querySelect[0] == "A"){

                                foreach ($table as $OB) {
                                    array_push($selectColumn,$OB);
                                }

                            }else{

                                foreach ($querySelect as $OBD) {
                                    array_push($selectColumn,$OBD);
                                }
                                
                            }
                    }
                }
                return $selectColumn;
        }
        function getColumnJoin($body,$tables){
            $queryOB = $body['query'];
            $selectColumn = [];
           
            foreach ($queryOB as $cureOB) {

                $querySelect = ["A"]; 
                
                if(isset($cureOB['s'])){
                
                    $querySelect = $cureOB['s'];
                
                }

                $tableName   = $cureOB['n'];
                $table       = $tables[$tableName];

                if(sizeof($querySelect) > 0){

                
                        if($querySelect[0] == "A"){

                            foreach ($table as $OB) {
                                array_push($selectColumn,$OB);
                            }

                        }else{

                            foreach ($querySelect as $OBD) {
                                array_push($selectColumn,$OBD);
                            }
                            
                        }
                }

                if(isset($cureOB['j'])){
                        $queryOBJ  = $cureOB['j'];
                      
                        foreach ($queryOBJ as $cureOBJ) {
                            $querySelectJ = ["A"]; 
                            $tableNameJ   = $cureOBJ['n'];
                            $tableJ       = $tables[$tableNameJ];
                            if(isset($cureOBJ['s'])){
                            
                                $querySelectJ  = $cureOB['s'];
                            
                            }
                            if($querySelectJ[0] == "A"){
                                if(isset($cureOB['group']) && $cureOBJ['l'] == "0"){
                                    array_push($selectColumn,$tableNameJ);
                                }else{
                                    foreach ($tableJ as $OBJ) {
                                        array_push($selectColumn,$OBJ);
                                    }
                                }
                              
    
                            }else{
    
                                foreach ($querySelectJ as $OBDJ) {
                                    array_push($selectColumn,$OBDJ);
                                }
                                
                            }
                        }
                }
            }
            return $selectColumn;
        }
        function isUpToDate($userDate, $dbDate) {
            $process = true;
        
            foreach ($dbDate as $d => $AUTH) {
                $find = false;
        
                foreach ($userDate as $u => $userAUTH) {
                    if ($AUTH === $userAUTH) {
                        $find = true;
                    }
                }
        
                if (!$find) {
                    $process = false;
                }
            }
        
            return $process;
        }
        
        function makeUpToDate($dbDate) {
         
            $AUTHARRAY = [];

            foreach ($dbDate as $d => $dateItem) {
                $dbString = $dateItem['TABLE_NAME']."_".$dateItem['UPDATE_TIME'];
                $AUTH = new CreatAUTH($dbString); // Assuming creatAUTH is a function available in your PHP codebase.
                $AUTHARRAY[] = $AUTH;
            }

            return $AUTHARRAY;
        }

        function isGetQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'get' || $cureAction === 'getJ') {
                    return true;
                }
            }
        
            return false;
        }
        function isGetQueryJoin($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ( $cureAction === 'getJ') {
                    return true;
                }
            }
        
            return false;
        }
        function isInsertQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'in' ) {
                    return true;
                }
            }
        
            return false;
        }
        function isCreateQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'create' ) {
                    return true;
                }
            }
        
            return false;
        }
        function isDeleteQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'del' ) {
                    return true;
                }
            }
        
            return false;
        }

        function isUpdateQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'up' ) {
                    return true;
                }
            }
        
            return false;
        }
        function isCheckQuery($ob) {

            $query = $ob['query'];
        
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['a'];
        
                if ($cureAction === 'check' ) {
                    return true;
                }
            }
        
            return false;
        }
        function getTables($ob) {

            $query = $ob['query'];
            $back  = [];
            foreach ($query as $q => $queryItem) {

                $cureAction = $queryItem['j'];
                foreach ($cureAction as $c => $queryItem_) {

                    $cureTableName = $queryItem_['n'];
                    array_push($back,$cureTableName);
                }
            }
        
            return $back;
        }
        function ObjNoLimit($qureyObj) {
            $query = $qureyObj['query'];
            $back  = [];
            for($q =  0 ; $q < sizeof($query) ;$q++) {
                $queryItem =$query[$q];
                
                if(isset($queryItem["limitAuto"]) ){
              
                    $qureyObj['query'][$q]["limitAuto"] = false;
                }
            }
        return  $qureyObj;
             
         }
        function query($qureyObj) {
            $res = $this->queryEngine($qureyObj); 
            return $res["res"];
        }
        function queryApi($qureyObj) {
           return $this->queryEngine($qureyObj); 
            
        }
        function queryEngine($qureyObj) {
                    
                    
            $tables          = $this->i_app['tables'];
            $qureyObjNoLimit = $this->ObjNoLimit($qureyObj);
            $isGet           = $this->isGetQuery($qureyObj);
            $isGetJoin       = $this->isGetQueryJoin($qureyObj);
            
            $isInsert        = $this->isInsertQuery($qureyObj);
            $isDelete        = $this->isDeleteQuery($qureyObj);
            $isUpdate        = $this->isUpdateQuery($qureyObj);
            $isCreate        = $this->isCreateQuery($qureyObj);
            $isCheck         = $this->isCheckQuery($qureyObj);
            $columns         = [];
          
            $db              = $this->i_app['database'];

            $qureyTxt        = new makeQuery( $qureyObj, $tables);
            $queryTextUp     = new makeQuery( [ "query"=>[ [ "a"=>"checkUpTime", "ob"=>$qureyObj, "db"=>$db ] ] ], $tables);
            $queryTxtSize    = new makeQuery( $qureyObjNoLimit , $tables);

            $QSize = 0;

            if($isGet){

                    $columns            = $this->getColumn($qureyObj, $tables);
                    $selectables        = [];

                    if($isGetJoin){
                        $columns        = $this->getColumnJoin($qureyObj, $tables);
                        $selectables    = $this->getTables($qureyObj);
                    }
                    
                    $connectionQ        = $this->connect();
                    $QSizeQuery         = mysqli_query($connectionQ, $queryTxtSize);
                

                    if( isset($QSizeQuery)  && !is_bool($QSizeQuery)  && mysqli_num_rows($QSizeQuery) > 0){
                            $QSize = mysqli_num_rows($QSizeQuery);
                    }

                    mysqli_close($connectionQ);

                    $connectionA        = $this->connect();
                    $queryConnectUp     = mysqli_query($connectionA, $queryTextUp);
                    $upadteTime         = [];
                    

                    if( isset($queryConnectUp)  && !is_bool($queryConnectUp)  && mysqli_num_rows($queryConnectUp) > 0){
                            $QSize_ = mysqli_num_rows($queryConnectUp);
                                while($row = mysqli_fetch_array($queryConnectUp)) {
                                    array_push($upadteTime,$row);
                                }
                    }else{

                        $upadteTime      = [];

                    }


                    mysqli_close($connectionA);
                    
                    $makeUpTodateData   = $this->makeUpTodate($upadteTime);
                   
                    $connectionB        = $this->connect();
                   
            
                    if(isset($queryOBJ["upTime"])){

                     $isUpdated = $this->isUpToDate($queryOBJ["upTime"],$makeUpTodateData);

                        if($isUpdated){

                            $res          =  [];
                            $res["res"]   =  "UPTODATE";
                            return $res;

                        }else{

                                $queryConnect       = mysqli_query($connectionB, $qureyTxt);
                                $result             = [];
                                
                                if(isset($queryConnect)  && !is_bool($queryConnect) &&  mysqli_num_rows($queryConnect) > 0){

                                    $querySize      = mysqli_num_rows($queryConnect);
                                    
                                            if($querySize > 0){
                                    
                                                while( $row = mysqli_fetch_array($queryConnect)){
                                                        
                                                        $rowOB      = [];

                                                        for($column = 0; $column < sizeof($columns); $column++){
                                                                
                                                                $columnName         = $columns[$column];
                                                                $dataColumn         = $row[$columnName];

                                                                if(isset($row[$columnName])){

                                                                    if(in_array($columnName,$selectables)){
                                                                        
                                                                        $rowOB[$columnName] = json_decode($dataColumn,true);
                                                                    }else{
                                                                        $rowOB[$columnName] = $dataColumn;
                                                                    }
                                                                }
                                                               
                                                        }

                                                        array_push($result, $rowOB);
                                                }
                                           
                                            }
                                    }
                    
                                        mysqli_close($connectionB);
                                      
                                        
                                        $res = [];
                             
                                        $res["res"]        = $result;
                                        $result["upTime"]  = $upadteTime;
                                        $res["Qsize"]      = $QSize;
                                        return $res;
                            }
                    
            
                    
                    }else{


                        $connectionC        = $this->connect();
                        $queryConnect       = mysqli_query($connectionC, $qureyTxt);
             
                        $result             = [];
                       
                    
                        if(isset($queryConnect)  && !is_bool($queryConnect) && mysqli_num_rows($queryConnect) > 0){

                            $querySize      = mysqli_num_rows($queryConnect);
                            
                                    if($querySize > 0){
                            
                                        while( $row = mysqli_fetch_array($queryConnect)){  
                                                
                                                $rowOB  = [];
                                                
                                                for($column = 0; $column < sizeof($columns); $column++){

                                                        $columnName         = $columns[$column];
                                                        if(isset($row[$columnName])){

                                                            $dataColumn         = $row[$columnName];
                                                      
                                                            if(in_array($columnName,$selectables)){
                                                                
                                                                $rowOB[$columnName] = json_decode($dataColumn,true);
                                                            }else{
                                                                $rowOB[$columnName] = $dataColumn;
                                                            }
                                                        }
                                                    
                                                }
                            
                                                array_push($result, $rowOB);
                            
                                        }
                          
                                    }
                            }
                     
                            mysqli_close($connectionC);

                            $res = [];
                             
                            $res["res"]         = $result;
                            $result["upTime"]   = $upadteTime;
                            $res["Qsize"]       = $QSize;
                            return $res;
                    }

            }else{

                if($isInsert){
                    $connection         = $this->connect();
              
                    $queryConnect       = mysqli_query($connection, $qureyTxt);
                    $insertedId         = mysqli_insert_id($connection);
                    mysqli_close($connection);
                            $res = [];
                            $res["res"]         = $insertedId;
            
                            return $res;
                

                }elseif($isUpdate || $isDelete || $isCreate){
                    $connection         = $this->connect();
                    $queryConnect       = mysqli_query($connection, $qureyTxt);
                    mysqli_close($connection);
                    $res = [];
                    $res["res"]         = true;
    
                    return $res;

                }else if($isCheck ){
                    $connectionC         = $this->connect();
                    $queryConnect       = mysqli_query($connectionC, $qureyTxt);
                    $result             = [];

                    if(isset($queryConnect)  && !is_bool($queryConnect) && mysqli_num_rows($queryConnect) > 0){
                        mysqli_close($connectionC);
                        $res = [];
                        $res["res"]         = true;
        
                        return $res;

                    }else{
                        mysqli_close($connectionC);
                        $res = [];
                        $res["res"]         = true;
        
                        return $res;

                    }
                }
            }
        }
        function chickDB(){
            if(isset($this->i_app["tables"])){
                return true;
            }else{
                return false;
            }
        }
    }