<?php 
class checkQueryUptime {

   public $getText = "";

    function __construct($ob) {

        $query      = $ob['ob']['query'];
        $tableName  = '';

        foreach ($query as $q => $cureQuery) {

            if ($q < 1 && isset($cureQuery['n'])) {

                $tableName .= " TABLE_NAME = ".$cureQuery['n'];

            } else if ($q > 0 && isset($cureQuery['n'])) {

                $tableName .= " OR TABLE_NAME = ".$cureQuery['n']; 
            }

            if (isset($cureQuery['j'])) {

                $cureJoins = $cureQuery['j'];

                foreach ($cureJoins as $cureJoin) {
                    $tableName .= " OR TABLE_NAME = ".$cureJoin['n'];
                }
            }
        }
        
        $this->getText = "SELECT TABLE_NAME, UPDATE_TIME FROM information_schema.tables WHERE $tableName";
     
    }
    function __toString(){
        return (string) $this->getText;
    }

}
