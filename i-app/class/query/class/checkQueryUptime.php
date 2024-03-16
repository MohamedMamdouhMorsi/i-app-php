<?php 
class checkQueryUptime {

   public $getText = "";

    function __construct($ob ,$db) {

        $query      = $ob['query'];
        $tableName  = '';

        foreach ($query as $q => $cureQuery) {

            if ($q < 1 && isset($cureQuery['n'])) {
                $tableName_ = $cureQuery['n'];
                $tableName .= " TABLE_SCHEMA = '$db' AND  TABLE_NAME = '$tableName_' ";

            } else if ($q > 0 && isset($cureQuery['n'])) {
                $tableName_ = $cureQuery['n'];
                $tableName .= " OR  TABLE_SCHEMA = '$db' AND  TABLE_NAME = '$tableName_'"; 
            }

            if (isset($cureQuery['j'])) {

                $cureJoins = $cureQuery['j'];

                foreach ($cureJoins as $cureJoin) {
                    $tableName_j = $cureJoin['n'];
                    $tableName .= " OR  TABLE_SCHEMA = '$db' AND  TABLE_NAME = '$tableName_j'";
                }
            }
        }
        //SELECT UPDATE_TIME FROM information_schema.tables WHERE TABLE_SCHEMA = 'vronlines' AND TABLE_NAME = 'users';
        
        $this->getText = "SELECT TABLE_NAME  , UPDATE_TIME  FROM information_schema.tables WHERE  $tableName ; ";
     
    }
    function __toString(){
        return (string) $this->getText;
    }

}
