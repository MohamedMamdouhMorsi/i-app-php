<?php 
class appDir{
    public $treeDir = [];
    function __construct($dir,$i_app)
    {
        
        $publicName = "public_html";
        
        if(isset($i_app["dir"]) && isset($i_app["dir"]["main"])){
            $publicName = $i_app["dir"]["main"];
        }

        $userPublic     = $dir."".$publicName;
        $treeData       = new getDirectoryTree($dir);
        $i_app_path     = $dir."/i.app";
        $i_app_db_path  = $dir."/db.app";
        $copObjectSt = json_encode($treeData);
        $copObjectOb = json_decode($copObjectSt,true);
        $assetArray     = ["/"];
        $goAssetArray[]  = new getDirectoryArray($copObjectOb["tree"],$dir);
        

        for($i = 0 ; $i < sizeof($goAssetArray); $i++){
        
            $app =  $goAssetArray[$i];
            array_push($assetArray,$app );
        
        }

        $this->treeDir["tree"]                  = $treeData;
        $this->treeDir["userDir"]               = $dir;
        $this->treeDir["userPublicDir"]         = $userPublic;
        $this->treeDir["i_app_path"]            = $i_app_path;
        $this->treeDir["assetArray"]            = $assetArray;
        $this->treeDir["i_app_db_path"]         = $i_app_db_path;


    }
    function __destruct()
    {
        return (array) $this->treeDir;
    }

}