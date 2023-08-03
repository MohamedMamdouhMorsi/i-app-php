<?php
class AssetFileHandler
{
    private function getContentType($extname)
    {
        switch ($extname) {
            case '.html':
                return 'text/html';
            case '.css':
                return 'text/css';
            case '.js':
                return 'text/javascript';
            case '.json':
                return 'application/json';
            case '.png':
                return 'image/png';
            case '.jpg':
            case '.jpeg':
                return 'image/jpeg';
            case '.ico':
                return 'image/x-icon';
            default:
                return 'application/app';
        }
    }

    private function iAppReader($data, $userData)
    {
        // You need to implement this function to process the i-app data
        // For simplicity, we'll assume it returns the processed data
        // The $userData argument is not used in this example, but you can use it if needed
        return $data;
    }

    public function handleAssetFile($req_url, $userDir, $swScript, $userData)
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
            $filePath = __DIR__ . '/../../img/flags/' . $img;
        } elseif ($req_url === '/i-app-ui.js') {
            $filePath = __DIR__ . '/../../i-app-ui.js';
            $isUiJs = true;
        } elseif ($req_url === '/icofont.css') {
            $filePath = __DIR__ . '/../../css/icofont.css';
        } elseif ($req_url === '/app.png') {
            $filePath = __DIR__ . '/../../img/app.png';
        } elseif ($req_url === '/i-app-basic.css') {
            $filePath = __DIR__ . '/../../css/i-app-basic.css';
        } else {
            $is_get = false;
            $lastUrl = $req_url;
            $getBody = '';
            $urlArr = explode('?', $req_url);
            if (count($urlArr) > 1) {
                $lastUrl  = $urlArr[0];
                $is_get   = true;
                $getBody  = $urlArr[1];
                $filePath = $userDir . '/public' . $lastUrl;
            } else {
                if ($filePath == null && $backBody == null) {
                    $filePath = $userDir . '/public' . $lastUrl;
                }
            }
        }

        if ($filePath !== null) {
            $extname = pathinfo($filePath, PATHINFO_EXTENSION);
            $contentType = $this->getContentType($extname);

            if (file_exists($filePath)) {
                $data = file_get_contents($filePath);
                
                if ($isUiJs) {
                    $userDataSt = 'const userData = ' . json_encode($userData);
                    $regex = '/\/\/\*\*userDataArea\*\*\/\/?\n?/';
                    $data = preg_replace($regex, $userDataSt, $data);
                }

                header('Content-Type: ' . $contentType);
                echo $data;
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>The requested URL ' . $req_url . ' was not found on this server.</p>';
            }
        } else {
            if ($backBody !== null) {
                header('Content-Type: ' . $contentType);
                echo $backBody;
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>The requested URL ' . $req_url . ' was not found on this server.</p>';
            }
        }
    }
}