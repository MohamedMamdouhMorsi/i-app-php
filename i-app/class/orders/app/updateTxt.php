<?php 
class updateTxt{
    function __construct($dir, $i_app)
    {
        $i_app_file_dir    = $dir."i.app";
        $i_app_src         = $dir."/public/". $i_app["dir"]["src"];
        $i_app_src_temp    = $dir."/public/temp";

        $lang              = "";
        if(isset($i_app["deflang"])){
            $lang              = $i_app["deflang"];
        }else{
            $lang              = $i_app["lang"][0];
        }
        $i_app_langDir     =  $dir."/public/". $i_app["dir"]["txt"]."/".$lang.".json";
        $langData          = file_get_contents($i_app_langDir,true);
        $langDataJ         = json_decode($langData,true);
        $tree              = new getDirectoryTree($i_app_src);
        $this->copyFolder($i_app_src,$i_app_src_temp);
        if(isset($tree["children"]) && sizeof($tree["children"]) > 0){
            new   readAndUpdate($tree["children"],$langDataJ,$i_app_langDir);
        }else{

            $res        = [];
            $res["res"] = false;
            echo json_encode($res);
            exit();
        }
    }

    function copyFolder($sourceFolder, $destinationFolder) {
        // Ensure the source folder exists
        if (!file_exists($sourceFolder)) {
            echo "Source folder '{$sourceFolder}' does not exist.";
            return;
        }
    
        // Create the destination folder if it doesn't exist
        if (!file_exists($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }
    
        // Read the contents of the source folder
        $files = scandir($sourceFolder);
    
        // Remove . and .. from the list
        $files = array_diff($files, array('.', '..'));
    
        // Copy each file from the source to the destination
        foreach ($files as $file) {
            $sourceFilePath = $sourceFolder . '/' . $file;
            $destinationFilePath = $destinationFolder . '/' . $file;
    
            // Check if it's a file or a directory
            if (is_dir($sourceFilePath)) {
                // Recursively copy subdirectories
                $this->copyFolder($sourceFilePath, $destinationFilePath);
            } else {
                // Copy the file
                copy($sourceFilePath, $destinationFilePath);
            }
        }
    }


    
}