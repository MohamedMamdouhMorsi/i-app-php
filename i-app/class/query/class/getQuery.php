<?php

class getQuery{

    public $getText = "FALSE";
    
    function __construct($ob, $tables) {
        
        $tableName = $ob['n'];

        if (isset($tables[$tableName])) {
        
            $orAndOptionText = new orAndOption($ob['q'], $tables[$tableName]);
            $limit = self::getLimit($ob);
           
            if (!isset($ob['s'])) {
                $ob['s'] = ["A"];
            }

            if ($ob['s'] && $ob['s'][0] == "A") {
                
                $this->getText = "SELECT * FROM " . $tableName . " WHERE " . $orAndOptionText . " " . $limit;
               
            } else {

                $selectedColumn = new selectColumn($ob['s']);
                $this->getText = "SELECT " . $selectedColumn . " FROM " . $tableName . " WHERE " . $orAndOptionText . " " . $limit ." ;";
               
            }

        } else {
            echo "Table " . $tableName . " is not exist";
            exit();
        }
    }

    public static function getLimit($ob) {
    
        $limit = '';
    
        if(isset($ob['limitAuto']) && $ob['limitAuto'] !== false) {
            if (isset($ob['last'])) {
                $limit = "LIMIT " . (int)$ob['last'] . " , " . (int)$ob['limitAuto'];
            } else {
                $limit = "LIMIT 0 , " . (int)$ob['limitAuto'];
            }
        } else if(isset($ob['l'] )){
            if ($ob['l'] == '0' || $ob['l'] == 0) {
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