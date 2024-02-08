<?php 
class updateIcons{
    function __construct($dir ,$i_app )
    {
        if(isset($_POST["icon"]) && isset($_POST["icon"]["image"]) && isset($_POST["icon"]["src"])){
            $imgBody = $_POST["icon"]["image"];
            $imgName = $_POST["icon"]["src"];
            $userPublicDir  =$dir."/public/".$i_app["dir"]["icon"]."/".$imgName;
            $imageBuffer = $this->base64ToImage($imgBody);
            $handl = fopen($userPublicDir,"w");
            fwrite($handl,$imageBuffer);
            fclose($handl);
            
            $res        = [];
            $res["res"] = false;
            echo json_encode($res);
            exit();
        }else{
            $res        = [];
            $res["res"] = false;
            echo json_encode($res);
            exit();
        }
    }
    function base64ToImage($base64String) {
        // Extract the image data and mime type from the base64 string
        if (!preg_match('/^data:(.*?);base64,(.*)$/', $base64String, $matches) || count($matches) !== 3) {
            return false;
        }
    
        $mimeType = $matches[1];
        $base64Data = $matches[2];
    
        // Create a binary string from the base64 data
        $binaryData = base64_decode($base64Data);
    
        return $binaryData;
    }
    
}