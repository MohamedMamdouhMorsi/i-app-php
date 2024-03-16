<?php 

class getJQuery {

    public $getText = "";

    private static $realationSympole = [
        'eq' => '=',
        'uneq' => '!=',
        'gr' => '>',
        'greq' => '>=',
        'le' => '<',
        'leeq' => '<=',
        'like' => 'LIKE'
    ];
    public $okRender = false;
    function __construct($ob, $tables) {
        $tableName = $ob['n'];
        $newColumnSelectName =  [];
        if(isset($ob['sn'])){
            $newColumnSelectName = $ob['sn'] ;
        }

        if (isset($tables[$tableName])) {
            $orAndOptionText = self::orAndOptionJoin($ob['q'], $tables, $tableName, $tableName);
            $limit = self::getLimit($ob);
       
            $selectedColumn = $ob['s'] && $ob['s'][0] !== 'A' ? $this->selectColumnJoin($ob['s'], $tableName, $newColumnSelectName) : $tableName . '.* ';
            $joinSting = '';
            $testJoin_ = $this->testJoin($ob['j']);
            if (isset($ob['j'])) {
                for ($o = 0; $o < sizeof($ob['j']); $o++) {
                    if (isset($ob['j'][$o]['s'])) {

                        $cureTableName           = $ob['j'][$o]['n'];
                        $newColumnSelectNameJoin = [];
                        $cureTableCol            = $tables[$cureTableName];  
                        $pointerData             = $this->getPointer($ob['j'][$o]['q'],$cureTableName,$cureTableCol);
                        if(isset($ob['j'][$o]['sn'])){
                            $newColumnSelectNameJoin =$ob['j'][$o]['sn'] ;
                        }

                        $selectedColumnJoin = isset($ob['j'][$o]['s'] ) && $ob['j'][$o]['s'][0] !== "A" ? $this->selectColumnJoin($ob['j'][$o]['s'], $cureTableName, $newColumnSelectNameJoin) : $cureTableName . '.* ';
                        
                        if(isset($ob['j'][$o]['l']) && $ob['j'][$o]['l'] !== "0"){
                            $selectedColumn .= ' , ' . $selectedColumnJoin;
                            //normal
                        }else  if(isset($ob['j'][$o]['l']) && $ob['j'][$o]['l'] == "0"){
                            //array
                            if($testJoin_ ){
                               
                                $selectedColumn .= ' , '.$cureTableName.'.'.$cureTableName;
                            }else{
                                $selectAllColumnsJoin_      = $this->selectAllColumnsJoin($cureTableCol , $cureTableName);
                                $selectedColumn            .= ", JSON_ARRAYAGG(JSON_OBJECT($selectAllColumnsJoin_ )) AS $cureTableName ";
                            }
                        }
                       

                        if ($ob['j'][$o]['q']) {

                            $joinMethod      =  'LEFT';

                            if(isset($ob['j'][$o]['jm'])){
                                $joinMethod      =  $ob['j'][$o]['jm'];
                            }

                            $multiArraySelect         = "";
                            if($testJoin_){
                                    $selectAllColumnsJoin_B   = $this->selectAllColumnsJoinKata($cureTableCol , $cureTableName);
                                    $multiArraySelect         = " (SELECT $pointerData , JSON_ARRAYAGG(JSON_OBJECT($selectAllColumnsJoin_B )) AS $cureTableName FROM $cureTableName GROUP BY $pointerData ) AS ";
                            }

                            $orAndOptionText_ = self::orAndOptionJoin($ob['j'][$o]['q'], $tables, $cureTableName, $tableName);
                            $joinSting       .= " $joinMethod JOIN $multiArraySelect $cureTableName ON $orAndOptionText_ ";
                        }
                    }
                }
            }

            $isIdTable = "";
            if (isset($tables[$tableName]) && $tables[$tableName][0] == "id") {
                $isIdTable = "id";
            }
            $orderBy = $isIdTable !== '' ? "ORDER BY $tableName.$isIdTable" : "";
            $groupBy = "";
            if (isset($ob['order'])) {
                $obOrder = $ob['order'];
                $orderBy = "ORDER BY $tableName.$obOrder ";
            }
            if (isset($ob['group'])) {
                $obGroup = $ob['group'];
                $groupBy = "GROUP BY $tableName.$obGroup ";
                
            }
            $this->getText = "SELECT $selectedColumn FROM $tableName $joinSting WHERE $orAndOptionText $groupBy $orderBy  $limit ;";

           if($this->okRender){
             echo $this->getText ;
            }
        } else {
            echo "Table $tableName is not exist";
        }
    }
    public static function valueFN($ob, $master) {
        $val = '';
        if ($ob['t'] === 'q') {
            $name =  $master . '.';
            if(isset($ob['n'])){
                $name = $ob['n'] . '.' ;
            }
            $val = $name . $ob['d'];
        }
        return $val;
    }

    public static function orAndOptionJoin($op, $tables, $tableName, $master) {
        if(isset($tables[$tableName])){
            $table = $tables[$tableName];
            $opText = "";
            $opCount = count($op);
    
            for ($orOpIndex = 0; $orOpIndex < $opCount; $orOpIndex++) {
                $orOp = $op[$orOpIndex];
                $orOpCount = count($orOp);
    
                for ($andOpIndex = 0; $andOpIndex < $orOpCount; $andOpIndex++) {
                    $andOp = $orOp[$andOpIndex];
    
                    $columnIndex = $andOp[0];
                    $columnName = '';
    
                    if (is_string($columnIndex)) {
                        $coulmnExist = in_array($columnIndex, $table);
                        if ($coulmnExist) {
                            $columnName = $tableName . '.' . $columnIndex;
                        }else{
                            echo "Error";
                            exit();
                        }
                    } elseif (is_numeric($columnIndex)) {
                        $columnIndex = $columnIndex - 1;
                        $columnName = $tableName . '.' . $table[$columnIndex];
                    }
    
                    $realtionText = $andOp[2];
                    $realtion = isset(self::$realationSympole[$realtionText]) ? self::$realationSympole[$realtionText] : 'false';
                    $valueOB = $andOp[1];
                    $value = null;
    
                    if (is_array($valueOB)) {
                        $value = self::valueFN($valueOB, $master);
                    } elseif (is_string($valueOB)) {
                        $value = "'$valueOB'";
                    } elseif (is_integer($valueOB)) {
                        $value = $valueOB;
                    }
    
                    $andText = '';
    
                    if (isset($orOp[$andOpIndex + 1])) {
                        $andText = 'AND';
                    }
    
                    if ($realtion == 'false') {
                        if ($realtionText == 'likeCode') {
                            $opText .= " IF(CHAR_LENGTH($value) < 4, $columnName LIKE CONCAT($value,'%'), $columnName LIKE CONCAT($value,'000%')) $andText";
                        }
                    } else {
                        $opText .= " $columnName $realtion $value $andText";
                    }
                }
    
                if (isset($op[$orOpIndex + 1])) {
                    $opText .= ' OR ';
                }
            }
    
            return $opText;
        }

    }
    public static function getPointer($ob,$tableName,$table){
        $columnIndex = $ob[0][0][0];
        $columnName  = "";

        if (is_string($columnIndex)) {

            $coulmnExist = in_array($columnIndex, $table);

            if ($coulmnExist) {
                $columnName = $columnIndex;
            }else{
                echo "Error";
                exit();
            }

        } elseif (is_numeric($columnIndex)) {
            $columnIndex = $columnIndex - 1;
            $columnName = $table[$columnIndex];
        }
        return $columnName;
    }
    public static function selectColumnJoin($op, $tableName, $sn) {

        $opText = "";
      
        for ($i = 0; $i < sizeof($op); $i++) {

            $selectColumnName =  '';
            if(isset( $sn[$i] )){
                $selectColumnName =  ' AS ' . $sn[$i];
            }
        
            $opText          .= $tableName . '.' . $op[$i] . $selectColumnName . ' ';

            if (isset($op[$i + 1])) {
                $opText .= ', ';
            }

        }

        return $opText;
    }
    public static function testJoin($ob) {
        $num = 0 ;
        for ($o = 0; $o < sizeof($ob); $o++) {
            if(isset($ob[$o]['l']) && $ob[$o]['l'] == "0"){
                $num =  $num + 1 ;
            }
        }
        if($num > 1){
            return true;
        }else{
            return false;
        }
    }
    public static function selectAllColumnsJoinOld($op, $tableName) {
     
            
        $opText = '\'{\',';
        for ($i = 0; $i < sizeof($op); $i++) {

            $opKeyName        = $op[$i];
            $opKey            = $tableName . '.' . $op[$i];
       
            if($i < sizeof($op) - 1 && sizeof($op) > 1){
                if($i == 0){
                    $opText .= '\'';
                }
                    $opText .= '"'.$opKeyName.'": "\','.$opKey.',\'" ,' ;
    
            }else{
        
                    $opText .= '"'.$opKeyName.'": "\','.$opKey.',\'" ' ;
        
            }
        }
        
        $opText .= '}\'';
    return $opText ;
}
    public static function selectAllColumnsJoin($op, $tableName) {
     
            $opText = "";
        
            for ($i = 0; $i < sizeof($op); $i++) {

                $opKeyName        = $op[$i];
                $opKey            = $tableName . '.' . $op[$i];
                $opText          .= "'".$opKeyName."' ,". $opKey ;

                if (isset($op[$i + 1])) {

                    $opText .=  " , " ;

                }

            }
            
            $opText .="";
        
        return $opText;
    }
    public static function selectAllColumnsJoinKata($op, $tableName) {
     
        $opText = "";
    
        for ($i = 0; $i < sizeof($op); $i++) {

            $opKeyName        = $op[$i];
         
            $opText          .= "'".$opKeyName."' ,". $opKeyName ;

            if (isset($op[$i + 1])) {

                $opText .=  " , " ;

            }

        }
        
        $opText .="";
    
    return $opText;
}
    public  function getLimit($ob) {

        $limit = '';

        if (isset($ob['limitAuto']) && $ob['limitAuto'] !== false) {

            if (isset($ob['last'])) {
                $limit = "LIMIT " . (int)$ob['last'] . " , " . (int)$ob['limitAuto'];
            } else {
                $limit = "LIMIT 0 , " . (int)$ob['limitAuto'];
            }

        } elseif (isset($ob['l'])){

            if($ob['l'] == '0' || $ob['l'] == 0) {

                $limit = '';
    
            } else {
    
                $limit = "LIMIT " . $ob['l'];
    
            }
    
        }

        return $limit;
    }

   function __toString()
   {
 
    return (string) $this->getText;
   }
}
