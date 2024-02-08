<?php

class DirectoryTree {
    public static   function getDirectoryTree($rootDir) {
       
        $tree = array();
        $tree['name']       = basename($rootDir);
        $tree['children']   = array();
        
        if (!is_dir($rootDir)) {
            throw new Exception("\"$rootDir\" is not a directory");
        }

        $files = scandir($rootDir);
   

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $rootDir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $aFile = self::getDirectoryTree($filePath);
                $tree['children'][] = $aFile;
            } else {
                $fileStrArr = explode('.', $file);
                $fileEx = end($fileStrArr);

                if ($fileEx === 'app') {

                    $data           = file_get_contents($filePath);
                    $fileConfigTx   = new iAppReader($data);
                     
                    $fileConfig = json_decode($fileConfigTx,true);
                    $isPage     = isset($fileConfig['page']);
                    $saveCoby   = new IAppFileMaker($fileConfig);

                    $tree['children'][] = [
                        'name' => $file,
                        'type' => 'file',
                        'path' => $rootDir,
                        'ext' => $fileEx,
                        'fileData' => $saveCoby,
                        'page' => $isPage,
                    ];
                } else {
                    $tree['children'][] = [
                        'name' => $file,
                        'type' => 'file',
                        'path' => $rootDir,
                        'ext' => $fileEx,
                    ];
                }
            }
        }

        $tree['type'] = 'folder';
        return $tree;
        
    }
}



?>
