<?php
class deleteQuery {
    
   public $getText = "";


 function __construct($ob, $tables) {
    $tableName = $ob['n'];

    if (isset($tables[$tableName])) {
        $orAndOptionText = new orAndOption($ob['q'], $tables[$tableName]);
        $limit = $ob['l'] && $ob['l'] == 0 ? '' : "LIMIT ".$ob['l'];

        $this->getText = "DELETE FROM $tableName WHERE $orAndOptionText $limit ;";
        
    } else {
        echo "Table $tableName is not exist";
    }
}

function __toString()
{
    return (string) $this->getText;
}

}
