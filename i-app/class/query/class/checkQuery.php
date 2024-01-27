<?php 

class checkQuery {

    public $getText = "";

    function __construct($ob){

        $tableName = $ob['n'];
        $this->getText =  "show tables like $tableName";
     
    }
    function __toString(){
      return (string)  $this->getText ;
    }
}