<?php 
class querySize {
    
    public $getText = "";


     function __construct($obs, $tables) {

        $ob = $obs['ob']['query'][0];

        $tableName = $ob['n'] ? $ob['n'] : 'No table Name';

        if (isset($tables[$tableName]) && $ob['q']) {

            $orAndOptionText = new orAndOption( $ob['q'], $tables[$tableName]);
            $selectedColumn  = new selectColumn([0]);
            $this->getText   = "SELECT $selectedColumn FROM $tableName WHERE $orAndOptionText ";

        } else {

            echo "Table $tableName is not exist";

        }
    }
    
    function __toString(){
      return (string) $this->getText;
    }
}
