<?php 
class updateQueryInput {
   public $query = [];
   function __construct($incomeQuery,$dir,$i_app)
   {
        if(isset($incomeQuery["query"])){

            $QQ = $incomeQuery["query"];

            for($q =0 ; $q < sizeof($QQ); $q++){

                $query_ = $QQ[$q];

                if(isset($query_["id"])){
                        $funcId = $query_["id"];
                        $idAr = explode("_",$query_["id"]);
                       
                        if(sizeof($idAr) == 4 || sizeof($idAr) == 5 && $idAr[0] == 'dev'){

                            $filename   = $idAr[0];
                            $queryIndex = $idAr[1];
                            $inputIndex = $idAr[2];
                            $queryType  = $idAr[3];
                            $fileDir = $dir.$i_app["dir"]["main"].$i_app["dir"]["src"].$filename.".app";
                            if(sizeof($idAr) == 5 && $idAr[0] == 'dev'){
                                $filename   = $idAr[0].'_'.$idAr[1];
                                $queryIndex = $idAr[2];
                                $inputIndex = $idAr[3];
                                $queryType  = $idAr[4];
                               
                                $fileDir = __DIR__ . '/../../../asset/elements/'.$filename.".app";
                            }
                          
                            if(sizeof($idAr) == 4 && $idAr[0] == 'dev'){
                                $filename   = $idAr[0];
                                $queryIndex = $idAr[1];
                                $inputIndex = $idAr[2];
                                $queryType  = $idAr[3];
                               
                                $fileDir = __DIR__ . '/../../../asset/elements/'.$filename.".app";
                            }
                           

                            if(file_exists($fileDir)){
                             
                                $template     = file_get_contents($fileDir,true); 
                                $object       = new IAppReadQuery($template ,$filename, $funcId);
                                if(isset($query_["limitAuto"])){
                                    $object->objectSt_["limitAuto"] = $query_["limitAuto"];
                                }
        
                                if(isset($query_["last"])){
                                    $object->objectSt_["last"] = $query_["last"];
                                }
                                $updatedQuery = $this->updateQuery($object->objectSt_,$object->QMAP,$object->QJMAP,$object->DMAP ,$query_);
                               
                                array_push($this->query, $updatedQuery);

                            }else{
                                $res = [];
                                $res["res"] = "error1";
                                echo json_encode($res);
                                exit();
                            }

                        }else{
                            $res = [];
                            $res["res"] = "error2";
                            echo json_encode($res);
                            exit();
                        }
                    
                }else{
                    $res = [];
                    $res["res"] =  "error3";
                    echo json_encode($res);
                    exit();
                }
            }

        }else{

            $res = [];
            $res["res"] =  "error4";
            echo json_encode($res);
            exit();
            
        }
    
    }

    function updateQuery( $queryFile ,$QMAP , $QJMAP, $DMAP  ,$queryData){
     

        if(isset($queryFile["a"])){

            $action = $queryFile["a"];

            if($action == "get"){
                if(isset($queryFile["q"]) && isset($queryData["q"])){
                   for($q = 0 ; $q < sizeof($queryData["q"]); $q++){
                        $data = $queryData["q"][$q];
                        $position = $QMAP[$q];
                        $or = $position [0];
                        $and = $position [1];
                        $queryFile["q"][$or][$and][1] = $data;
                   }
                }

            }elseif ($action == "getJ"){
                if(isset($queryFile["q"]) && isset($queryData["q"])){
                    for($q = 0 ; $q < sizeof($queryData["q"]); $q++){
                         $data = $queryData["q"][$q];
                         $position = $QMAP[$q];
                         $or = $position [0];
                         $and = $position [1];
                         $queryFile["q"][$or][$and][1] = $data;
                    }
                 }
                 if(isset($queryFile["j"]) && isset($queryData["j"])){
                   
                    for($q = 0 ; $q < sizeof($queryData["j"]); $q++){

                                $data     = $queryData["j"][$q];

                                $position = $QJMAP[$q];
                                $join     = $position[0];
                                $or       = $position[1];
                                $and      = $position[2];

                                $queryFile["q"][$join][$or][$and][1] = $data;
                        
                    }
                 }
            }elseif ($action == "in"){
                if(isset($queryFile["d"]) && isset($queryData["d"])){

                                $queryFile["d"] = $queryData["d"];
                        
                    
                 }
            }elseif ($action == "up"){

                if(isset($queryFile["q"]) && isset($queryData["q"])){
                   
                    for($q = 0 ; $q < sizeof($queryData["q"]); $q++){
                         $data = $queryData["q"][$q];
                         $position = $QMAP[$q];
                         $or = $position [0];
                         $and = $position [1];
                         $queryFile["q"][$or][$and][1] = $data;
                    }
                
                 }
                
                if(isset($queryFile["d"]) && isset($queryData["d"])){
                   
                    for($q = 0 ; $q < sizeof($queryData["d"]); $q++){

                                $data     = $queryData["d"][$q];
                                $index    = $DMAP[$q];

                                $queryFile["d"][$index][1] = $data;
                        
                    }
                 }

            }elseif ($action == "del"){

                    if(isset($queryFile["q"]) && isset($queryData["q"])){

                        for($q = 0 ; $q < sizeof($queryData["q"]); $q++){

                            $data       = $queryData["q"][$q];
                            $position   = $QMAP[$q];
                            $or         = $position [0];
                            $and        = $position [1];

                            $queryFile["q"][$or][$and][1] = $data;

                        }
                    }
            } 

            return $queryFile;
        }
    }
    function __destruct()
    {
      return (array) $this->query;
     
    }
}