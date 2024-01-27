<?php 
class orAndOption{
    public   $opText = "";
     function __construct($op, $table) {
      
        $realationSympole = [
            'eq' => '=',
            'uneq' => '!=',
            'gr' => '>',
            'greq' => '>=',
            'le' => '<',
            'leeq' => '<=',
            'like' => 'LIKE'
        ];
     
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
                        $columnName = $columnIndex;
                    }
                } elseif (is_numeric($columnIndex)) {
                    $columnIndex = $columnIndex - 1;
                    $columnName = $table[$columnIndex];
                }
    
                $realtionText = $andOp[2];
                $realtion = isset($realationSympole[$realtionText]) ? $realationSympole[$realtionText] : 'false';
                $value = $andOp[1];
    
                if (is_string($value)) {
                    $value = "'$value'";
                }
    
                $andText = '';
    
                if (isset($orOp[$andOpIndex + 1])) {
                    $andText = 'AND';
                }
    
                if ($realtion == 'false') {
                    if ($realtionText == 'likeCode') {
                        $this->opText .= " IF(CHAR_LENGTH($value) < 4, $columnName LIKE CONCAT($value,'%'), $columnName LIKE CONCAT($value,'000%')) $andText";
                    }
                } else {
                    $this->opText .= " $columnName $realtion $value $andText";
                }
            }
    
            if (isset($op[$orOpIndex + 1])) {
                $this->opText .= ' OR ';
            }
        }
    
 
    }
    function __toString()
    {
        return $this->opText;
    }
}