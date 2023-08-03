<?php

class IAppReader {
    private function removeComments($str) {
        return preg_replace('/\/\*[\s\S]*?\*\/|\/\/.*/', '', $str);
    }

    private function funcHandel($str) {
        $ar = explode('fn:', $str);
        if (count($ar) > 1) {
            $ls = '';
            for ($a = 0; $a < count($ar); $a++) {
                if ($a < 1) {
                    $ls = $ar[0];
                }
                $op = 0;
                $cl = 0;
                $vv = '';
                $aft = '';
                $done = false;
                $cr = str_split($ar[$a]);
                if ($a > 0 && $ar[$a] !== '') {
                    foreach ($cr as $c) {
                        if (!$done) {
                            if ($c === '{') {
                                $op++;
                            }
                            if ($c === '}') {
                                $cl++;
                            }
                        }

                        if (!$done) {
                            if ($op === $cl) {
                                $done = true;
                            }
                            $vv .= $c;
                        } else if ($done) {
                            $aft .= $c;
                        }
                    }
                }
                $enc = base64_decode($vv);
                $ls .= " fndc: '$enc' $aft";
            }
            return $ls;
        } else {
            return $str;
        }
    }

    private function escapeKeysSym($str) {
        $strArr = str_split($str);
        $start = false;
        $type = '';
        $out = '';
        foreach ($strArr as $cureValue) {
            if ($cureValue == '"') {
                if ($start == true && $type == $cureValue) {
                    $type = '';
                    $start = false;
                } else {
                    $type = '"';
                    $start = true;
                }
            }
            if ($cureValue == "'") {
                if ($start == true && $type == $cureValue) {
                    $type = '';
                    $start = false;
                } else {
                    $type = "'";
                    $start = true;
                }
            }
            if ($start && $cureValue == ':') {
                $out .= 'aaa@aaa';
            } else {
                $out .= $cureValue;
            }
        }
        return $out;
    }

    private function convertStrToOb($str) {
        $str = preg_replace('/(\r\n|\n|\r)/', '', $str); // remove newlines
        $str = $this->escapeKeysSym($str);
        $str = $this->funcHandel($str);
        $str = preg_replace('/\/\*[\s\S]*?\*\/|\/\/.*/', '', $str);
        $str = trim($str); // convert to string and remove leading / trailing whitespace
        $str = preg_replace('/(\"\w+\"\s*:\s*[^,\{\[\]]+)\s*(\}|,|\])/', '$1,$2', $str);
        $str = preg_replace('/\s+/', ' ', $str); // replace multiple spaces with single space
        $str = str_replace("\t", ' ', $str); // replace tabs with spaces
        $str = str_replace('\\"', '"', $str); // remove escaped quotes
        $str = preg_replace("/(['\"])?([a-z0-9A-Z_]+)(['\"])?:/", '"$2": ', $str); // add quotes around property names
        $str = str_replace("'", '"', $str); // replace single quotes with double quotes
        $str = preg_replace('/,\s*}/', '}', $str); // remove trailing commas

        // Add missing commas
        $str = str_replace('" "', '" , "', $str); // missing comma
        $str = str_replace('] "', '] , "', $str); // missing comma
        $str = str_replace('} "', '} , "', $str); // missing comma
        $str = str_replace('} {', '} , {', $str); // missing comma
        $str = str_replace('" {', '" , {', $str); // missing comma

        $str = str_replace('""', '" , "', $str); // missing comma
        $str = str_replace(']"', '] , "', $str); // missing comma
        $str = str_replace('}"', '} , "', $str); // missing comma
        $str = str_replace('}{', '} , {', $str); // missing comma
        $str = str_replace('"{', '" , {', $str); // missing comma

        $str = preg_replace('/([a-z0-9A-Z_]+) "/', '$1 , "', $str); // delete last comma comma

        return $str;
    }

    public function readIAppFile($str) {
        $str = $this->removeComments($str);
        $str = $this->convertStrToOb($str);
        return  $str;
    }
}

// Usage example:
// $iAppReader = new IAppReader();
// $cleanedObject = $iAppReader->readIAppFile($inputString);
?>
