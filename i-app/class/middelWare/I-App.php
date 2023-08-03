<?php
class AppFileHandler
{
    private function isUrlFilleApp($url)
    {
        $urlArr = explode('.app', $url);
        return count($urlArr) > 1;
    }

    private function isUrlDevApp($url)
    {
        $urlArr = explode('dev_', $url);
        return count($urlArr) > 1;
    }

    private function getContentType($extname)
    {
        // You need to implement this function to return the content type based on the file extension
        // Example implementation:
        switch ($extname) {
            case '.html':
                return 'text/html';
            case '.json':
                return 'application/json';
            // Add more cases for other file types if needed
            default:
                return 'text/plain';
        }
    }

    private function iAppReader($data)
    {
        // You need to implement this function to parse and process the .app file data
        // For simplicity, we'll assume it returns the processed data
        return $data;
    }

    public function handleAppFile($req_url, $ext, $fileName, $manifest, $i_app_st, $tree, $userDir, $i_app)
    {
        $backBody = null;
        $filePath = null;
        $isApp    = $this->isUrlFilleApp($req_url);
        $isDevUrl = $this->isUrlDevApp($req_url);

        if ($isDevUrl) {
            $dev_ = explode('/', $req_url);
            $fileNamedev = end($dev_);
            $filePath = __DIR__ . '/../../elements/' . $fileNamedev;
        } elseif ($req_url === '/manifest.json') {
            $backBody = json_encode($manifest);
        } elseif ($backBody == null && $req_url === '/i.app') {
            $backBody = $i_app_st;
        } else {
            if ($req_url === '/sl.app') {
                $filePath = __DIR__ . '/../../elements/sl.app';
            } elseif ($req_url === $i_app['dir']['src'] . 'dev.app' && $i_app['mode'] === 'dev') {
                $filePath = __DIR__ . '/../../elements/dev.app';
            } else {
                if ($filePath == null && $backBody == null && $ext === '.app') {
                    if ($i_app['mode'] && $i_app['mode'] == 'dev') {
                        $filePath = $userDir . '/public_html' . $req_url;
                    } else {
                        $app_file_test = $fileName . '.app';
                        // You need to implement the searchFiles function that searches for the file in the $tree array
                       // $is_app_file = $this->searchFiles($tree, $app_file_test);
                      //  $backBody = $is_app_file['fileData'];
                    }
                } elseif ($filePath == null && $backBody == null && $ext === '.json') {
                    $isApp = true;
                    $filePath = $userDir . '/public' . $req_url;
                }
            }
        }

        if ($backBody == null && $filePath !== null) {
            $extname = pathinfo($filePath, PATHINFO_EXTENSION);
            $contentType = $this->getContentType($extname);
            if (file_exists($filePath)) {
                $data = file_get_contents($filePath);
                $appData = $this->iAppReader($data);
                if (isset($appData['page']) || $isApp) {
                    header('Content-Type: ' . $contentType);
                    echo $data;
                } else {
                    http_response_code(400);
                    echo '<h1>400 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>';
                }
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>The requested URL ' . $req_url . ' was not found on this server.</p>';
            }
        } else {
            if ($backBody !== null) {
                header('Content-Type: application/json');
                echo $backBody;
            } else {
                http_response_code(404);
                echo '<h1>404 Not Found</h1><p>The requested URL ' . $req_url . ' was not found on this server.</p>';
            }
        }
    }

    // You need to implement the searchFiles function here or you can pass it as an external dependency
    // private function searchFiles($tree, $fileName) {
    //     ...
    //     ...
    // }
}