<?php 

class getDirectoryTree{
    public $tree = [];
    function __construct($rootDir)
    {
        $this->tree = $this-> getDirectoryTreeFunc_($rootDir);
    }

    function getDirectoryTreeFunc_($rootDir) {
        // Check if the root directory exists
        if (!is_dir($rootDir)) {
            throw new Exception("{$rootDir} is not a directory");
        }
    
        $tree = [];
        $tree['name'] = basename($rootDir);
        $tree['children'] = [];
    
        $files = scandir($rootDir);
    
        // Remove . and .. from the list
        $files = array_diff($files, array('.', '..'));
    
        foreach ($files as $file) {
            $filePath = $rootDir . '/' . $file;
            $fileStats = stat($filePath);
    
            if (is_dir($filePath)) {
                // Recursively get the directory tree
                $aFile = $this->getDirectoryTreeFunc_($filePath);
                $tree['children'][] = $aFile;
            } else {
                $fileStrArr = explode('.', $file);
                $fileEx = end($fileStrArr);
    
                if ($fileEx === 'app') {
                    $data = file_get_contents($filePath);
                    $fileConfigTx = new iAppReader($data);
                    $fileConfig = json_decode($fileConfigTx,true);
                    $isPage = isset($fileConfig['page']) ? true : false;
                    $isSEO = isset($fileConfig['SEO']) ? true : false;
    
                    $tree['children'][] = [
                        'name' => $file,
                        'type' => 'file',
                        'path' => $rootDir,
                        'ext' => $fileEx,
                        'fileData' => $data,
                        'page' => $isPage,
                        'SEO' =>$isSEO
                    ];
                } else {
                    $tree['children'][] = [
                        'name' => $file,
                        'type' => 'file',
                        'path' => $rootDir,
                        'ext' => $fileEx
                    ];
                }
            }
        }
        $tree['type'] = "folder";
        return $tree;
    }
    function __toString()
    {
        $data = json_encode($this->tree);
        return (string)  $data;
    }
}