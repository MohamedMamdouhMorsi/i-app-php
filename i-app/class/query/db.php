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

    function query($qureyObj){
                
                
        $tables         = $this->i_app['tables'];

        $columns        = $this->getColumn($qureyObj, $tables);
        $qureyTxt       = new makeQuery($qureyObj, $tables);
        $queryTextUp    = new makeQuery(["query"=>[["a"=>"checkUpTime","ob"=>$qureyObj]]], $tables);
    
        $isGet          = $this->isGetQuery($qureyObj);

        if($isGet){

                $connection         = $this->connect();
                $queryConnectUp     = mysqli_query($connection, $queryTextUp);
                $upadteTime         = [];

                if( isset($queryConnectUp)  && mysqli_num_rows($queryConnectUp) > 0){

                    while($row = mysqli_fetch_array($queryConnectUp)) {
                        
                        if(isset($row['UPDATE_TIME'])){

                            $upadteTime = $row['UPDATE_TIME'];

                        }
                    }
                
                }

                mysqli_close($connection);

                $connection        = $this->connect();
                $makeUpTodateData  = $this->makeUpTodate($upadteTime);
        
                if(isset($queryOBJ["upTime"])){

                $isUpdated = $this->isUpToDate($queryOBJ["upTime"],$makeUpTodateData);

                    if($isUpdated){

                        ///
                        return (string) "UPTODATE";

                    }else{

                        $queryConnect       = mysqli_query($connection, $qureyTxt);
                        $result             = [];
                        

                        if(isset($queryConnect)  && mysqli_num_rows($queryConnect) > 0){

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

                            mysqli_close($connection);

                            return $result;
                        }
                
        
                
                }else{
                
                }

        }
    }

    }