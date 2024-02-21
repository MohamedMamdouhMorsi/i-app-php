<?php

class updateQuery {
    public $getText = "";
    function __construct($ob, $tables) {
        $tableName = $ob['n'];
    
        if (isset($tables[$tableName])) {

            $orAndOptionText = new orAndOption($ob['q'], $tables[$tableName]);
            $upData          = self::setUpData($ob['d'], $tables[$tableName]);
            $limit           = $ob['l'] && $ob['l'] == 0 ? '' : "LIMIT ".$ob['l'];

            $this->getText = "UPDATE $tableName SET $upData WHERE $orAndOptionText $limit";
          
        } else {

            echo "Table $tableName is not exist";

        }
    }

private static function setUpData($data, $table) {

    $upData    = "";
    $dataCount = count($data);

    for ($i = 0; $i < $dataCount; $i++) {

        $columnIndex = $data[$i][0] - 1;
        $columnName = $table[$columnIndex];
        $value = $data[$i][1];

        if (is_string($value)) {
            $upData .= "$columnName = '$value'";
        } else {
            $upData .= "$columnName = $value";
        }

        if (isset($data[$i + 1])) {
            $upData .= ' , ';
        }
    }

    return $upData;
}

function __toString(){

    return (string) $this->getText;

}
}
