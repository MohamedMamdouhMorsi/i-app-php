<?php

class makeQuery{
    public  $queryText = "FALSE";
    function __construct($body, $tables)
    {
        
        $queryOB = $body['query'];
       

        foreach ($queryOB as $cureOB) {
            if(isset($cureOB['a'])){
            $queryAction = $cureOB['a'];

            if ($queryAction === "get") {
                $this->queryText = new getQuery($cureOB, $tables);
            } elseif ($queryAction === "getJ") {
                $this->queryText = new getJQuery($cureOB, $tables);
            } elseif ($queryAction === "in") {
                $this->queryText = new insertQuery($cureOB, $tables);
            } elseif ($queryAction === "up") {
                $this->queryText = new updateQuery($cureOB, $tables);
            } elseif ($queryAction === "del") {
                $this->queryText = new deleteQuery($cureOB, $tables);
            } elseif ($queryAction === "check") {
                $this->queryText = new checkQuery($cureOB);
            } elseif ($queryAction === "querySize") {

                if ($cureOB['ob']['query'][0]['a'] === 'get') {

                    $this->queryText = new querySize($cureOB, $tables);

                } elseif ($cureOB['ob']['query'][0]['a'] === 'getJ') {

                    $this->queryText = new querySizeJoin($cureOB, $tables);
                }
                
            } elseif ($queryAction === "checkUpTime") {
                $this->queryText = new checkQueryUptime($cureOB["ob"],$cureOB["db"]);
            } elseif ($queryAction === "create") {
                $this->queryText = $cureOB['d'];
            }
        }else{
            echo "itsMe";
            exit();
        }
        }

  
    }

    function __toString()
    {
        return (string)  $this->queryText;
    }
}