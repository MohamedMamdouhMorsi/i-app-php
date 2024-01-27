<?php 

class getJQuery {

    public $getText = "";

    private static $realationSympole = [
        'eq' => '=',
        'uneq' => '!=',
        'gr' => '>',
        'greq' => '>=',
        'le' => '<',
        'leeq' => '<=',
        'like' => 'LIKE'
    ];

    function __construct($ob, $tables) {
        $tableName = $ob['n'];
        $newColumnSelectName = $ob['sn'] ? $ob['sn'] : [];

        if (isset($tables[$tableName])) {
            $orAndOptionText = self::orAndOptionJoin($ob['q'], $tables, $tableName, $tableName);
            $limit = self::getLimit($ob);
            $selectedColumn = $ob['s'] && $ob['s'][0] !== 'A' ? self::selectColumnJoin($ob['s'], $tableName, $newColumnSelectName) : $tableName . '.* ';
            $joinSting = '';

            if ($ob['j']) {
                for ($o = 0; $o < count($ob['j']); $o++) {
                    if (isset($ob['j'][$o]['s'])) {

                        $cureTableName = $ob['j'][$o]['n'];
                        $newColumnSelectNameJoin = $ob['sn'] ? $ob['sn'] : [];
                        $selectedColumnJoin = $ob['j'][$o]['s'] && $ob['j'][$o]['s'][0] !== 'A' ? self::selectColumnJoin($ob['j'][$o]['s'], $cureTableName, $newColumnSelectNameJoin) : $cureTableName . '.* ';
                        $selectedColumn .= ' , ' . $selectedColumnJoin;

                        if ($ob['j'][$o]['q']) {

                            $joinMethod      = $ob['j'][$o]['jm'] ? $ob['j'][$o]['jm'] : 'LEFT';
                            $orAndOptionText = self::orAndOptionJoin($ob['j'][$o]['q'], $tables, $cureTableName, $tableName);
                            $joinSting      .= " $joinMethod JOIN $cureTableName ON $orAndOptionText ";
                        }
                    }
                }
            }

            $isIdTable = "";

            $orderBy = $isIdTable !== '' ? "ORDER BY $isIdTable" : "";
            
            if ($ob['order']) {
                $orderBy = "ORDER BY ".$ob['order'];
            }

            $this->getText = "SELECT $selectedColumn FROM $tableName $joinSting WHERE $orAndOptionText $orderBy $limit";

           
        } else {
            echo "Table $tableName is not exist";
        }
    }
    public static function valueFN($ob, $master) {
        $val = '';
        if ($ob['t'] === 'q') {
            $name = $ob['n'] ? $ob['n'] . '.' : $master . '.';
            $val = $name . $ob['d'];
        }
        return $val;
    }

    public static function orAndOptionJoin($op, $tables, $tableName, $master) {
        $table = $tables[$tableName];
        $opText = "";
        $opCount = count($op);

        for ($orOpIndex = 0; $orOpIndex < $opCount; $orOpIndex++) {
            $orOp = $op[$orOpIndex];
            $orOpCount = count($orOp);

            for ($andOpIndex = 0; $andOpIndex < $orOpCount; $andOpIndex++) {
                $andOp = $orOp[$andOpIndex];

                $columnIndex = $andOp[0];
                $columnName = '';

                if (is_string($columnIndex)) {
                    $coulmnExist = in_array($columnIndex, $table);
                    if ($coulmnExist) {
                        $columnName = $tableName . '.' . $columnIndex;
                    }
                } elseif (is_numeric($columnIndex)) {
                    $columnIndex = $columnIndex - 1;
                    $columnName = $tableName . '.' . $table[$columnIndex];
                }

                $realtionText = $andOp[2];
                $realtion = isset(self::$realationSympole[$realtionText]) ? self::$realationSympole[$realtionText] : 'false';
                $valueOB = $andOp[1];
                $value = null;

                if (is_array($valueOB)) {
                    $value = self::valueFN($valueOB, $master);
                } elseif (is_string($valueOB)) {
                    $value = "'$valueOB'";
                }

                $andText = '';

                if (isset($orOp[$andOpIndex + 1])) {
                    $andText = 'AND';
                }

                if ($realtion == 'false') {
                    if ($realtionText == 'likeCode') {
                        $opText .= " IF(CHAR_LENGTH($value) < 4, $columnName LIKE CONCAT($value,'%'), $columnName LIKE CONCAT($value,'000%')) $andText";
                    }
                } else {
                    $opText .= " $columnName $realtion $value $andText";
                }
            }

            if (isset($op[$orOpIndex + 1])) {
                $opText .= ' OR ';
            }
        }

        return $opText;
    }

    public static function selectColumnJoin($op, $tableName, $sn) {

        $opText = "";

        for ($i = 0; $i < count($op); $i++) {

            $selectColumnName = $sn[$i] ? ' AS ' . $sn[$i] : '';
            $opText          .= $tableName . '.' . $op[$i] . $selectColumnName . ' ';

            if ($op[$i + 1]) {
                $opText .= ', ';
            }

        }

        return $opText;
    }

    public  function getLimit($ob) {

        $limit = '';

        if (isset($ob['limitAuto'])) {

            if (isset($ob['last'])) {
                $limit = "LIMIT " . (int)$ob['last'] . " , " . (int)$ob['limitAuto'];
            } else {
                $limit = "LIMIT 0 , " . (int)$ob['limitAuto'];
            }

        } elseif (isset($ob['l'])){

            if($ob['l'] == '0' || $ob['l'] == 0) {

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
