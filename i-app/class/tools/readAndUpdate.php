<?php 
class readAndUpdate{

function __construct($srcFiles, $txtData, $i_app_langDir) {
    global $langOB;

    $langOB = $txtData;
    $data =  $this->readFiles($srcFiles);

    foreach ($data as $item) {
        $filePath = $item['path'];
        $processFileDataApp = $item['data'];
        $pretty = $this->prettyPrint($processFileDataApp);
        file_put_contents($filePath, $pretty);
        echo 'saved > ' . $filePath;
    }

    file_put_contents($i_app_langDir, json_encode($langOB, JSON_PRETTY_PRINT));

   
}
function isKey($sto) {
    return in_array($sto, ['q', 'qt', 't', 'v', 'vt', 'val', 'app', 'u']);
}

function makeShortName($str) {
    $str = preg_replace("/(\r\n|\n|\r)/", '', $str);
    $str = strtolower($str);
    $str = str_replace(' ', '-', $str);
    $strDataG = preg_replace("/[^a-z-]/", '', $str);
    $strDataL = str_replace('--', '-', $strDataG);
    $crNo = strlen($strDataL);
    $count = $crNo > 20 ? 20 : $crNo;
    $strDataR = substr($strDataL, 0, $count);
    $strData = $strDataR;

    while (isset($langOB[$strData])) {
        if ($count <= $crNo) {
            $strData = substr($strData, 0, $count);
        } else {
            $strData = "{$strData}_{$count}";
        }
        $count++;
    }

    if (strpos($strData, "-") === 0) {
        $strData = substr($strData, 1);
    }

    return $strData;
}

function isStringExist($str) {
    global $langOB;

    foreach ($langOB as $key => $value) {
        if ($value == $str) {
            return $key;
        }
    }
    return false;
}

function newStringOb($str) {
    global $langOB;

    if (is_string($str)) {
        // implementation for string
    } else {
        return $str;
    }

    $strAr = str_split($str);
    $Laststr = "";
    $objectIsOpen = false;
    $prag = [];
    $render = true;
    $isObject = false;
    $cutAndUp = false;
    $stateType = 'free';

    for ($i = 0; $i < count($strAr); $i++) {
        $alpha = $strAr[$i];
        $betaIndex = $i + 1;
        $gammaIndex = $i + 2;
        $isKey_ = $this->isKey($alpha);

        if ($isKey_ && isset($strAr[$betaIndex]) && isset($strAr[$gammaIndex])) {
            $beta = $strAr[$betaIndex];
            $gamma = $strAr[$gammaIndex];

            if ($beta === '.' && $gamma === '{' && $Laststr !== '') {
                $isObject = true;
                $prag[] = ['t' => $stateType, 's' => $Laststr];
                $Laststr = $alpha;
            }
        }

        if ($alpha == '}') {
            $stateType = 'fun';
            $isObject = false;
            $cutAndUp = true;
        }

        if ($i == count($strAr) - 1) {
            $cutAndUp = true;
        }

        if ($render) {
            $Laststr .= $alpha;

            if ($cutAndUp) {
                $prag[] = ['t' => $stateType, 's' => $Laststr];
                $Laststr = "";
                $cutAndUp = false;

                if ($stateType == 'fun') {
                    $stateType = 'free';
                }
            }
        }
    }

    $lastStr = "";
    foreach ($prag as $item) {
        if ($item['t'] == 'free') {
            $isStringExist_ = $this->isStringExist($item['s']);

            if ($isStringExist_) {
                $lastStr .= "t.{{$isStringExist_}}";
            } else {
                $isStr = preg_replace("/[^a-z]/", '', $item['s']);

                if ($isStr !== '') {
                    $shortName = $this->makeShortName($item['s']);
                    $langOB[$shortName] = $item['s'];
                    $lastStr .= "t.{{$shortName}}";
                } else {
                    $lastStr .= $item['s'];
                }
            }
        } else {
            $lastStr .= $item['s'];
        }
    }

    return $lastStr;
}

function newArStringOb($ar) {
    global $langOB;

    if (is_array($ar)) {
        foreach ($ar as $key => $item) {
            if (isset($item['s']) && $item['t'] !== 'code') {
                $ar[$key]['s'] = $this->newStringOb($item['s']);
            } elseif (isset($item['e'])) {
                $ar[$key]['e'] = $this->newArStringOb($item['e']);
            }
        }
    }

    return $ar;
}

 function processFile($fileAppData, $filePath) {
    global $langOB;

    foreach ($fileAppData as $key => $value) {
        if ($key === 's') {
            $fileAppData[$key] = $this->newStringOb($value);
        } elseif ($key === 'e') {
            $fileAppData[$key] = $this->newArStringOb($value);
        }
    }

    return $fileAppData;
}

 function readFiles($srcFiles) {
    global $data;

    foreach ($srcFiles as $fileOb) {
        if (isset($fileOb['path']) && $fileOb['type'] == 'file' && $fileOb['ext'] == 'app') {
            $filePath = $fileOb['path'] . '/' . $fileOb['name'];
            $fileData = file_get_contents($filePath);
            $fileAppData = new iAppReader($fileData);
            $fileAppDataJson = json_decode($fileAppData, true);
            $processFileData =  $this->processFile($fileAppDataJson, $filePath);
            $toJson = json_encode($processFileData);
            $processFileDataApp = new iAppFileMaker($toJson);
            $data[] = ['path' => $filePath, 'data' => $processFileDataApp];
        } elseif (isset($fileOb['children'])) {
            $this->readFiles($fileOb['children']);
            echo ' process > ' . count($fileOb['children']);
        }
    }

    return $data;
}

function isCharacterNumber($char) {
    return is_numeric($char);
}

function prettyPrint($obj) {
    $tabSize = 2;
    $indentLevel = 0;
    $result = '';
    $result1 = '';
    $obj = str_replace(["\t", "     ", "\n"], '', $obj);
    $obj = str_replace('"', "'", $obj);
    $isStringOpenKey = false;

    for ($i = 0; $i < strlen($obj); $i++) {
        $char = $obj[$i];
        if (!$isStringOpenKey && $char === "'") {
            $isStringOpenKey = true;
        } elseif ($isStringOpenKey && $char === "'") {
            $isStringOpenKey = false;
        }
        $isNum = $this->isCharacterNumber($char);
        if (!$isStringOpenKey) {
            if ($i > 0 && $obj[$i - 1] === ' ') {
                if (
                    $isNum ||
                    $char === '{' ||
                    $char === '[' ||
                    $char === '}' ||
                    $char === ']' ||
                    $char === ':' ||
                    $char === ' ' ||
                    ($char === 't' && $obj[$i + 1] === 'r' && $obj[$i + 2] === 'u' && $obj[$i + 3] === 'e') ||
                    ($char === 'f' && $obj[$i + 1] === 'a' && $obj[$i + 2] === 'l' && $obj[$i + 3] === 's' && $obj[$i + 4] === 'e')
                ) {
                    $result1 .= $char;
                } else {
                    $result1 .= '.!' . $char;
                }
            } else {
                $result1 .= $char;
            }
        } else {
            $result1 .= $char;
        }
    }
    $isStringOpen = false;
    for ($i = 0; $i < strlen($result1); $i++) {
        $char = $result1[$i];
        if (!$isStringOpen && $char === "'") {
            $isStringOpen = true;
        } elseif ($isStringOpen && $char === "'") {
            $isStringOpen = false;
        }
        if ($char === '{' || $char === '[') {
            $result .= $char;
            if (!$isStringOpen) {
                $indentLevel++;
                $result .= "\n" . str_repeat(' ', $indentLevel * $tabSize);
            }
        } elseif ($char === '}' || $char === ']') {
            if (!$isStringOpen) {
                $indentLevel--;
                $result .= "\n" . str_repeat(' ', $indentLevel * $tabSize);
            }
            $result .= $char;
        } elseif ($char === '!' && $result1[$i - 1] === '.') {
            $result .= $char;
            if (!$isStringOpen) {
                $result .= "\n " . str_repeat(' ', $indentLevel * $tabSize);
            }
        } else {
            $result .= $char;
        }
    }

    $result = str_replace('.!', '', $result);
    $txt = '/fndc:\'(.*?)\'/';
    $output = preg_replace_callback($txt, function($matches) {
        global $EC_;
        $testData = trim($matches[1]);

        if ($testData) {
            return 'fn:' . $EC_($testData);
        } else {
            return $matches[0];
        }
    }, $result);

    return $output;
}

function __destruct()
{
  
    $res        = [];
    $res["res"] = true;
    echo json_encode($res);
    exit();
}
}