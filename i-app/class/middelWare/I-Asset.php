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
            case 'jpg':
           
                return 'image/jpeg';
            case 'ico':
                return 'image/x-icon';
            default:
                return 'application/app';
        }
    }


    public function __construct($req_url,$extname, $userDir, $swScript, $userData)
    {
        $filePath = null;
        $backBody = null;
        $contentType = null;
        $isUiJs = false;
     
        if ($req_url === '/sw.js') {
            $contentType = 'text/javascript';
            $backBody = $swScript;
        } elseif (preg_match('/\/img\/flags\//', $req_url)) {
         
            $img = str_replace('/img/flags/', '', $req_url);
            $filePath = __DIR__ . '/../../asset/img/flags/' . $img;
        } elseif ($req_url === '/i-app-ui.js') {
            $filePath = __DIR__ . '/../../asset/js/i-app-ui.js';
            $isUiJs = true;
        }  elseif ($req_url === '/i-app-ui.min.js') {
            $filePath = __DIR__ . '/../../asset/js/i-app-ui.min.js';
            $isUiJs = true;
        } elseif ($req_url === '/icofont.css') {
            $filePath = __DIR__ . '/../../asset/css/icofont.css';
        } elseif ($req_url === '/app.png') {
            $filePath = __DIR__ . '/../../img/app.png';
        } elseif ($req_url === '/i-app-basic.css') {
            $filePath = __DIR__ . '/../../asset/css/i-app-basic.css';

        }  elseif ($req_url === '/i-app-basic.min.css') {
            $filePath = __DIR__ . '/../../asset/css/i-app-basic.min.css';

        } else {
            $is_get = false;
            $lastUrl = $req_url;
            $getBody = '';
            $urlArr = explode('?', $req_url);
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
                    $userDataSt = 'const userData = ' . json_encode($userData);
                    $regex = '/\/\/\*\*userDataArea\*\*\/\/?\n?/';
                    $data = preg_replace($regex, $userDataSt, $data);
                }

               
                if($extname === 'png' || $extname === 'jpg' ){
                  

                 header('Content-Type: ' . $contentType.';');
                 header('Content-Length: ' . filesize($filePath));
             
                 // Disable output buffering to allow large image files
                 @ob_end_clean();
             
                 // Output the image data directly to the browser
                 readfile($filePath);
                 exit();
                  }else{
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