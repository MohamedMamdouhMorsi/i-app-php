<?php
class AppFileHandler
{
    
    function __construct($req_url, $ext, $fileName, $manifest, $i_app_st, $tree, $userDir, $i_app)
    {
        $backBody    = null;
        $filePath    = null;
        $isApp       = $this->isUrlFilleApp($req_url);
        $isDevUrl    = $this->isUrlDevApp($req_url);
        $dev_        = explode('/', $req_url);
        $fileNamedev = end($dev_);
        $isJson = false;


        if ($isDevUrl) {
          
            $filePath = __DIR__ . '/../../asset/elements/' . $fileNamedev;
            $isApp = true;
        } elseif ($req_url === '/manifest.json') {
            $backBody = $manifest;
        
        } elseif ($backBody == null && $req_url === '/i.app') {
            $backBody = $i_app_st;
          
        } else if($filePath == null && $backBody == null) {
<<<<<<< HEAD

            if ($req_url === '/countryFlags.json') {
                $filePath = __DIR__ . '/../../asset/img/flags/countryFlags.json';
                $isApp = true;
            } elseif  ($req_url === '/limitAuto.app') {
=======
            if ($req_url === '/limitAuto.app') {
>>>>>>> 43a45af8640155305d00ad73ff5ae490875b71ab
                $filePath = __DIR__ . '/../../asset/elements/limitAuto.app';
                $isApp = true;
            } elseif  ($req_url === '/sl.app') {
                $filePath = __DIR__ . '/../../asset/elements/sl.app';
                $isApp = true;
            } elseif(preg_match('/dev.app/', $req_url) && $i_app['mode'] == 'dev') {

                $filePath = __DIR__ . '/../../asset/elements/dev.app';
                $isApp = true;

            } else {

                if ($filePath == null && $backBody == null && $ext == 'app') { 
                        $filePath = $userDir . '/public_html' . $req_url;
                } elseif ($filePath == null && $backBody == null && $ext == 'json') { 
                        $isJson = true;
                        $filePath = $userDir . '/public_html' . $req_url;
                }
            }
        }

        if ($backBody == null && $filePath !== null) {

            $extname = pathinfo($filePath, PATHINFO_EXTENSION);
            $contentType = $this->getContentType($extname);

            if (file_exists($filePath)) {
              
                $data       = file_get_contents($filePath,true);
                $is_Json    = json_decode($data,true);

                if($is_Json){
                    $isJson = true;
                }

                $userSrcDir =  false;

                if(isset($i_app["dir"]) && isset($i_app["dir"]["src"])){
                    $userSrcDir =  $i_app["dir"]["src"];
                }

                $appDataSt  =  new IAppReadSave($data,$req_url,$userSrcDir);
                $appData    =  json_decode($appDataSt,true);

                if ($isJson) {

                    header('Content-Type: ' . $contentType.";");
                    echo $data;
                    exit();

                } else if (isset($appData['page']) || $isApp) {

                    header('Content-Type: ' . $contentType.";");
                    echo json_encode($appData);
                    exit();

                } else {
                    http_response_code(400);
                    echo '<h1>400 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>';
                    exit();

                }
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>app file_exists The requested URL  was not found on this server.</p>';
                exit();
            }
        } else {

            if ($backBody !== null && $filePath == null) {

                header('Content-Type: application/json;');

                echo $backBody;
                exit();

            } else {

                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>app backBody The requested URL was not found on this server.</p>';
                exit();

            }
        }
    }
    
    private function isUrlFilleApp($url)
    {
        $urlArr =preg_match('/.app/', $url);
        return $urlArr;
    }

    private function isUrlDevApp($url)
    {
        $urlArr =preg_match('/dev_/', $url);
        return $urlArr;
    }

    private function getContentType($extname)
    {
        // You need to implement this function to return the content type based on the file extension
        // Example implementation:
        switch ($extname) {
            case 'html':
                return 'text/html';
            case 'json':
                return 'application/json';
            // Add more cases for other file types if needed
            default:
                return 'text/plain';
        }
    }


}