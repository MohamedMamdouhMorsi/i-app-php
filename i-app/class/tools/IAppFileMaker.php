<?php

class IAppFileMaker {
    public $fileOut = "{}";
    public function __construct($str)
    {
        $str = $this->fixString($str);
        $str = $this->convertStrToOb($str);
        $str = str_replace('_.ACOMA._', ':', $str);
        $str = str_replace(':  {', ':{', $str);
        $str = str_replace(':  [', ': [', $str);
        $str = str_replace(":  '", ":'", $str);
       $this->fileOut = $str;
    }
    public function convertStrToOb($str) {
        $str = preg_replace('/(\r\n|\n|\r)/', '', $str); // remove newlines
        $str = preg_replace('/\/\*[\s\S]*?\*\/|\/\/.*/', '', $str);
        $str = trim($str); // convert to string and remove leading/trailing whitespace
        $str = preg_replace('/("w+"\s*:\s*[^,\{\[\]]+)\s*(\}|,|\])/', '$1,$2', $str);
        $str = preg_replace('/\s+/', ' ', $str); // replace multiple spaces with single space
        $str = str_replace("\t", ' ', $str); // replace tabs with spaces
        $str = str_replace('\\"', '"', $str); // remove escaped quotes
        $str = preg_replace('/([\'"])?([a-z0-9A-Z_]+)([\'"])?:/', '$2: ', $str); // add quotes around property names
        $str = str_replace('"', "'", $str); // replace double quotes with single quotes
        $str = str_replace(',', ' ', $str); // missing comma
        return $str;
    }

    public function fixString($str) {
        $strOb = is_string($str) ? json_decode($str, true) : $str;
        
        if (is_array($strOb)) {
            foreach ($strOb as $key => $value) {
                if (is_string($value)) {
                    $strOb[$key] = str_replace(':', '_.ACOMA._', $value);
                } else {
                    $strOb[$key] = json_decode($this->fixString($value));
                }
            }
            return json_encode($strOb);
        } elseif (is_object($strOb) && $strOb instanceof Traversable) {
            // Handle the case where $strOb is an object implementing Traversable
            // You can iterate over it using foreach or handle it accordingly.
        } else {
            // Handle other data types or non-iterable cases as needed
            return $strOb;
        }
    }
    
    function __toString()
    {
        return (string) $this->fileOut;
    }
     
}



?>
