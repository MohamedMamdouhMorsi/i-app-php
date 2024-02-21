<?php 

class insertQuery {

    public $getText = "";
    
    function __construct($ob, $tables) {

        $tableName = $ob['n'];

        if (isset($tables[$tableName])) {

            $notId = [];
            for($i = 0 ; $i < sizeof($tables[$tableName]); $i++){
                if($tables[$tableName][$i] !== "id"){
                    $newColumn = $tables[$tableName][$i] ;
                    array_push($notId,  $newColumn );
                }
            }
            $selectedColumn     = new selectColumn($notId);
            $insertValuesData   = $this->insertValues($ob['d']);

            $this->getText = "INSERT INTO $tableName ($selectedColumn) VALUES $insertValuesData";
            
        } else {

            echo "Table $tableName is not exist";

        }
    }

    function insertValues($op) {
        if (is_array($op[0])) {
            $opTextAll = "";
            for ($i = 0; $i < count($op); $i++) {
                $opText = "";
                for ($j = 0; $j < count($op[$i]); $j++) {
                    $opText .= " '" . $op[$i][$j] . "'";
                    $nextOp = isset($op[$i][$j + 1]) || isset($op[$i][$j + 1]) && $op[$i][$j + 1] == '0' ? true : false;
                    if ($nextOp) {
                        $opText .= ", ";
                    }
                }
                $opTextAll .= "($opText)";
                if ($i < count($op) - 1) {
                    $opTextAll .= ", ";
                }
            }
            return $opTextAll;
        } else {
            $opTextA = "";
            for ($i = 0; $i < count($op); $i++) {
                $opTextA .= " '" . $op[$i] . "'";
                $nextOp = isset($op[$i + 1]) || isset($op[$i + 1])  && $op[$i + 1] == '0' ? true : false;
                if ($nextOp) {
                    $opTextA .= ", ";
                }
            }
            $opTextA = "($opTextA)";
            return $opTextA;
        }
    }
    
    
    function __toString()
    {
        return (string) $this->getText;

    }
}