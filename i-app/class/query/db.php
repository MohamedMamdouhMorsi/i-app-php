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
                $AUTH = creatAUTH($dbString); // Assuming creatAUTH is a function available in your PHP codebase.
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

        function query($qureyObj) {
                    
                    
            $tables         = $this->i_app['tables'];

            $isGet          = $this->isGetQuery($qureyObj);
            $isInsert       = $this->isInsertQuery($qureyObj);
            $isDelete       = $this->isDeleteQuery($qureyObj);
            $isUpdate       = $this->isUpdateQuery($qureyObj);
            $isCreate       = $this->isCreateQuery($qureyObj);
            $columns        = [];
          
           
            $qureyTxt       = new makeQuery($qureyObj, $tables);
            $queryTextUp    = new makeQuery(["query"=>[["a"=>"checkUpTime","ob"=>$qureyObj]]], $tables);
        
      
            if($isGet){
                $columns        = $this->getColumn($qureyObj, $tables);
                    $connectionA         = $this->connect();
                
                    $queryConnectUp     = mysqli_query($connectionA, $queryTextUp);
                
                    $upadteTime         = [];

                    if( isset($queryConnectUp)  && !is_bool($queryConnectUp)  && mysqli_num_rows($queryConnectUp) > 0){

                        while($row = mysqli_fetch_array($queryConnectUp)) {
                            
                            if(isset($row['UPDATE_TIME'])){

                                $upadteTime = $row['UPDATE_TIME'];

                            }
                        }
                    
                    }else{
                        $upadteTime         = [];
                    }

                    mysqli_close($connectionA);

                    $connectionB        = $this->connect();
                    $makeUpTodateData  = $this->makeUpTodate($upadteTime);
            
                    if(isset($queryOBJ["upTime"])){

                    $isUpdated = $this->isUpToDate($queryOBJ["upTime"],$makeUpTodateData);

                        if($isUpdated){

                            ///
                            return (string) "UPTODATE";

                        }else{

                                $queryConnect       = mysqli_query($connectionB, $qureyTxt);
                                $result             = [];
                                

                                if(isset($queryConnect)  &&!is_bool($queryConnect) &&  mysqli_num_rows($queryConnect) > 0){

                                    $querySize      = mysqli_num_rows($queryConnect);
                                    
                                            if($querySize > 0){
                                    
                                                while( $row = mysqli_fetch_array($queryConnect)){  
                                                        
                                                        $rowOB  = [];
                                                        
                                                        for($column = 0; $column < sizeof($columns); $column++){

                                                                $columnName         = $columns[$column];
                                                                $rowOB[$columnName] = $row[$column];
                                                            
                                                        }
                                    
                                                        array_push($result, $rowOB);
                                    
                                                }
                                    
                                            }
                                    }

                                        mysqli_close($connectionB);

                                        return $result;
                            }
                    
            
                    
                    }else{

                        $connectionC         = $this->connect();
                        $queryConnect       = mysqli_query($connectionC, $qureyTxt);
                        $result             = [];
                       
                        
                        if(isset($queryConnect)  && !is_bool($queryConnect) && mysqli_num_rows($queryConnect) > 0){

                            $querySize      = mysqli_num_rows($queryConnect);
                            
                                    if($querySize > 0){
                            
                                        while( $row = mysqli_fetch_array($queryConnect)){  
                                                
                                                $rowOB  = [];
                                                
                                                for($column = 0; $column < sizeof($columns); $column++){

                                                        $columnName         = $columns[$column];
                                                        $rowOB[$columnName] = $row[$column];
                                                    
                                                }
                            
                                                array_push($result, $rowOB);
                            
                                        }
                            
                                    }
                            }

                            mysqli_close($connectionC);

                            return $result;
                    }

            }else{
                if($isInsert){
                    $connection         = $this->connect();
                    $queryConnect       = mysqli_query($connection, $qureyTxt);
                    $insertedId         = mysqli_insert_id($connection);
                    mysqli_close($connection);
                    return $insertedId ;
                }elseif($isUpdate || $isDelete || $isCreate){
                    $connection         = $this->connect();
                    $queryConnect       = mysqli_query($connection, $qureyTxt);
                    mysqli_close($connection);
                    return true ;
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