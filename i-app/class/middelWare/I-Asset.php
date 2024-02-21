<?php
class AssetFileHandler
{
    public $publicData = "No Asset Data";
    private function getContentType($extname)
    {
        switch ($extname) {
            case 'html':
                return 'text/html';
            case 'css':
                return 'text/css';
            case 'js':
                return 'text/javascript';
            case 'json':
                return 'application/json';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'mp4':
                return 'image/mp4';
            case 'mp3':
                return 'image/mp3';
            case 'jpg':
                return 'image/jpeg';
            case 'mjs':
                    return 'text/javascript';
            case 'jpeg':
                    return 'image/jpeg';
            case 'ico':
                return 'image/x-icon';
            default:
                return 'application/app';
        }
    }


    public function __construct($req_url,$extname, $userDir, $swScript, $userData , $i_app)
    {
        $filePath = null;
        $backBody = null;
        $contentType = null;
        $isUiJs = false;
        $is_Module = false;
        if ($req_url === '/sw.js') {
            $contentType = 'text/javascript';
           
            if(isset($i_app['dir']) && isset($i_app['dir']['main'])){
                $projectDir = $userDir.''.$i_app['dir']['main'];
                
                if (file_exists($projectDir)) {
                     $tree     = DirectoryTree::getDirectoryTree($projectDir);
                     $assetArr = DirectoryArray::getDirectoryArray($tree,"");
                     $assetArray = ["/","/manifest.json","/i.app","/sw.js","/i-app-basic.min.css","/i-app-ui.min.js","/icofont.css"];
                     for($i = 0 ; $i < sizeof($assetArr); $i++){
                        array_push($assetArray,$assetArr[$i]);
                     }

                     $contentType = $this->getContentType($extname);
                     header('Content-Type: text/javascript;');
                     
                     $swScript = 'const assete = '.json_encode($assetArray).';';
                     $swPath   = __DIR__."/swTemplate.js";
                     $template = file_get_contents($swPath,true); 
                     $swScript = $swScript.''.$template;
                        echo $swScript;
                        exit();
                }
            }
            
     
        } elseif (preg_match('/\/img\/flags\//', $req_url)) {
         
            $img = str_replace('/img/flags/', '', $req_url);
            $filePath = __DIR__ . '/../../asset/img/flags/' . $img;
       
        } elseif (preg_match('/\/img\/install\//', $req_url)) {
         
            $img = str_replace('/img/install/', '', $req_url);
            $filePath = __DIR__ . '/../../asset/img/install/' . $img;
       
        } elseif ($req_url === '/i-app-ui.js') {
            $filePath = __DIR__ . '/../../asset/js/i-app-ui.js';
            $isUiJs = true;
        } elseif  ($req_url === '/three.js') {
            $filePath = __DIR__ . '/../../asset/js/WEBGL/three.module.min.js';
       
        } elseif (preg_match('/\/WEBGL\//', $req_url)) {
            $filePP = str_replace('/WEBGL/', '', $req_url);
            if($filePP === '/WEBGL/three.js'){
                $filePP = 'three.module.js';
            }
            $filePath = __DIR__ . '/../../asset/js/WEBGL/'.$filePP;
          
            $is_Module = true;
        } elseif($req_url === '/i-app-ui.min.js') {
            $filePath = __DIR__ . '/../../asset/js/i-app-ui.min.js';
            $isUiJs = true;
        }  elseif ($req_url === '/icofont.css') {
            $filePath = __DIR__ . '/../../asset/css/icofont.css';
        } elseif ($req_url === '/app.png') {
            $filePath = __DIR__ . '/../../img/app.png';
        } elseif ($req_url === '/i-app-basic.css') {
            $filePath = __DIR__ . '/../../asset/css/i-app-basic.css';

        } elseif ($req_url === '/i-app-basic.min.css') {
            $filePath = __DIR__ . '/../../asset/css/i-app-basic.min.css';

        } else {
            $is_get  = false;
            $lastUrl = $req_url;
            $getBody = '';
            $urlArr  = explode('?', $req_url);
            if (count($urlArr) > 1) {
                $lastUrl  = $urlArr[0];
                $is_get   = true;
                $getBody  = $urlArr[1];
                $filePath = $userDir . '/public_html' . $lastUrl;
            } else {
                if ($filePath == null && $backBody == null) {
                   
                    $filePath = $userDir . '/public_html' .$req_url;
             
                }
            }
        }

     
        if ($filePath !== null) {
         
        
            $contentType = $this->getContentType($extname);
          
            if (file_exists($filePath)) {
                $data = file_get_contents($filePath,true);
           
                if ($isUiJs && $userData !== 'FALSE') {
                    $userDataEC = json_encode($userData);
                    $userDataSt = str_replace('\"', '"', $userDataEC);
                    $userDataSt = "const userData = $userDataSt ;"  ;
                    $userDataSt = str_replace('"{', '{', $userDataSt);
                        $userDataSt = str_replace('}"', '}', $userDataSt);
                    $regex = 'const userData = {};';
                    $data = str_replace($regex, $userDataSt, $data);
                }

               
                if($extname === 'png' || $extname === 'jpg' || $extname === 'jpeg' ||  $extname === 'gif' ){
                  

                 header('Content-Type: ' . $contentType.';');
                 header('Content-Length: ' . filesize($filePath));
             
                 // Disable output buffering to allow large image files
                 @ob_end_clean();
             
                 // Output the image data directly to the browser
                 readfile($filePath);
                 exit();
                  }else{
                    if($is_Module){
                        $contentType = 'text/javascript';
                    }
                    header('Content-Type: ' . $contentType.';');
                    echo($data);
                    exit();
                  }
             
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>file_exists The requested URL ' . $filePath . ' was not found on this server.</p>';
                exit();
            }
            
        } else {
         
        
            if ($backBody !== null) {
                header('Content-Type: ' . $contentType);
                echo $backBody;
                exit();
            } else {
               http_response_code(404);
                echo '<h1>404 Not Found</h1><p>backBody The requested URL ' . $filePath . ' was not found on this server.</p>';
                exit();
            }
        }
    }
 
}