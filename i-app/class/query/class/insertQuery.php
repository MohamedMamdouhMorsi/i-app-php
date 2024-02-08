<?php 

class insertQuery {

    public $getText = "";
    
    function __construct($ob, $tables) {

        $tableName = $ob['n'];

        if (isset($tables[$tableName])) {

            $notId = array_filter($tables[$tableName] , function ($e) {

                return $e !== 'id' ;

            });

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
            $i = 0 ;

            foreach ($op as $opT) {

                $opText = "";

                foreach ($opT as $value) {
                    $opText .= " '" . $value . "'";
                    $nextOp = isset($opT[$i + 1]) || $opT[$i + 1] == '0' ? true : false;
                    if ($nextOp) {
                        $opText .= ", ";
                    }
                }

                $opTextAll .= "($opText)";

                if (next($op)) {
                    $opTextAll .= ", ";
                }

                $i= $i +1 ;
            }

            return $opTextAll;
        } else {
            $opTextA = "";
            $i = 0 ;
            foreach ($op as $value) {
                $opTextA .= " '" . $value . "'";
                $nextOp = isset($op[$i + 1]) || $op[$i + 1] == '0' ? true : false;
                if ($nextOp) {
                    $opTextA .= ", ";
                }
                $i= $i +1 ;
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