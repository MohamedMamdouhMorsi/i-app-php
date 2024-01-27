<?php

class DirectoryArray {
    public static function getDirectoryArray($tree, $pathD) {
        $ar = [];
        $lastPath = '';

        if ($tree['type'] == "folder") {
            if ($tree['name'] !== '' && $tree['name'] !== 'public' && $tree['name'] !== 'public_html') {
                $lastPath = $pathD . '/' . $tree['name'];
            }

            for ($c = 0; $c < count($tree['children']); $c++) {
                if ($tree['children'][$c]['type'] == "folder") {
                    $children = self::getDirectoryArray($tree['children'][$c], $lastPath);
                    $ar = array_merge($ar, $children);
                } else {
                    $newPath = $lastPath . '/' . $tree['children'][$c]['name'];
                    $ar[] = $newPath;
                }
            }
        } else {
            // file case
            $newPath = $pathD . '/' . $tree['name'];
            $ar[] = $newPath;
        }

        return $ar;
    }
}



?>
