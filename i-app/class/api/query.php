<?php 

class query {

    function __construct($dbConnection , $msgJson,$dir,$i_app){
        ini_set('memory_limit', '-1');
       $updateVal = new updateQueryInput($msgJson,$dir,$i_app);
       $res = $dbConnection->queryApi( ["query"=>$updateVal->query]);
      
      
       @ob_end_clean();
       //$res["report"] =  $updateVal  ;
       echo json_encode($res);

        exit();
    }
    
}