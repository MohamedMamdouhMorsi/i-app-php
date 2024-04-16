<?php
class deleteQuery {
    
   public $getText = "";


 function __construct($ob, $tables) {
    $tableName = $ob['n'];

    if (isset($tables[$tableName])) {
        $orAndOptionText = new orAndOption($ob['q'], $tables[$tableName]);
        $limit = '';
        if(isset($ob['l']) && $ob['l'] != 0 &&  $ob['l'] != '0'){
            $limit = "LIMIT ".$ob['l'];
        }

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
