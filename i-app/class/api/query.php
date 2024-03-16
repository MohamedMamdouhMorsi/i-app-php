<?php 

class query {

    function __construct($dbConnection , $msgJson,$dir,$i_app){

       $updateVal = new updateQueryInput($msgJson,$dir,$i_app);
       $res = $dbConnection->queryApi( ["query"=>$updateVal->query]);
      
      
       
       //$res["report"] =  $updateVal  ;
       echo json_encode($res);

        exit();
    }
    
}