<?php 
class querySizeJoin {
    public $getText = "";
    
    function __construct($obs, $tables) {
    $ob = $obs['ob']['query'][0];
    $tableName = $ob['n'];
    $newColumnSelectName =  [];

    if(isset($ob['sn'])){
        $newColumnSelectName = $ob['sn'];
    }

    if (isset($tables[$tableName])) {

        $orAndOptionText = new orAndOptionJoin($ob['q'], $tables, $tableName, $tableName);

        $selectedColumn = $ob['s'] && $ob['s'][0] !== 'A' ? new selectColumnJoin($ob['s'], $tableName, $newColumnSelectName) : $tableName . '.* ';
        $joinSting = '';

        if ($ob['j']) {
            foreach ($ob['j'] as $join) {
                if ($join['s']) {
                    $cureTableName = $join['n'];
                    $newColumnSelectNameJoin =  [];
                    if(isset($join['sn'] )){
                        $newColumnSelectNameJoin = $join['sn'] ;
                    }
                    $selectedColumnJoin = $join['s'] && $join['s'][0] !== 'A' ? new selectColumnJoin($join['s'], $cureTableName, $newColumnSelectNameJoin) : $cureTableName . '.* ';
                    $selectedColumn .= ' , ' . $selectedColumnJoin;

                    if ($join['q']) {
                         
                        $joinMethod =  'LEFT';

                        if(isset($join['jm'])){

                            $joinMethod = $join['jm'];
                        }
                        
                        $orAndOptionText = new orAndOptionJoin($join['q'], $tables, $cureTableName, $tableName);
                        $joinSting .= " $joinMethod JOIN $cureTableName ON $orAndOptionText ";
                    }
                }
            }
        }

        $isIdTable = "";

        $orderBy = $isIdTable !== '' ? "ORDER BY $isIdTable" : "";
        if ($ob['order']) {
            $orderBy = "ORDER BY {$ob['order']}";
        }

        $getText = "SELECT $selectedColumn FROM $tableName $joinSting WHERE $orAndOptionText ";

        return $getText;
    } else {
        echo "Table $tableName is not exist";
    }
}

}
