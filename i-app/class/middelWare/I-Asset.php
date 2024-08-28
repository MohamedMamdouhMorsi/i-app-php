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
 
    public function __construct($req_url,$extname, $userDir, $swScript, $userData , $i_app,$db)
    {
        $filePath = null;
        $backBody = null;
        $contentType = null;
        $isUiJs = false;
        $is_Module = false;
        $dateTime = new DateTime();

        // Subtract 10 minutes from the current time
        $dateTime->modify('-10 minutes');

        // Format the time as needed
        $timestamp = $dateTime->format('Y-m-d\TH:i:sP');
        if($req_url === '/robots.txt'){
            $host = $_SERVER['HTTP_HOST'];
            $host = $_SERVER['HTTP_HOST'];
            $txt  = "# * \n";
            $txt .= "User-agent: * \n";
            $txt .= "Allow: / \n\n";  // Added extra new line for separation
            
            $txt .= "User-agent: SemrushBot \n";
            $txt .= "Crawl-delay: 60 \n\n";  // Added extra new line for separation
            
            $txt .= "# Host \n";
            $txt .= "Host: https://".$host."\n\n";  // Added extra new line for separation
            
            $txt .= "# Sitemaps \n";
            $txt .= "Sitemap: https://".$host."/sitemap.xml\n";
            
            // Example: Output the content
            echo nl2br($txt);
            
          
            exit();
        }else if ($req_url === '/sw.js') {
            $contentType = 'text/javascript';
           
            if(isset($i_app['dir']) && isset($i_app['dir']['main'])){
                $projectDir = $userDir.''.$i_app['dir']['main'];
                
                if (file_exists($projectDir)) {
                   
                     $treeST         = new getDirectoryTree($projectDir);
            
                     $treeJson       = json_decode($treeST,true);
                     $treeST         = "";

                     $assetArr       = new getDirectoryArray($treeJson,""); 
                   
                     $assetArrDecode = json_decode( $assetArr,true);
                 
                     $assetArray     = ["/","/manifest.json","/i.app","/sw.js","/i-app-basic.min.css","/i-app-ui.min.js","/icofont.css"];


                     if(is_array(  $assetArrDecode )){
                            for($i = 0 ; $i < sizeof($assetArrDecode ); $i++){
                                array_push($assetArray,$assetArrDecode[$i]);
                            }
                    }
                    
                     $contentType = $this->getContentType($extname);
                     header('Content-Type: text/javascript;');
                     $assetArrData = json_encode($assetArray);
                     $tru =  str_replace("\/","/", $assetArrData);
                     $swScript = 'const assete = '.$tru .';';
                     $swPath   = __DIR__."/swTemplate.js";
                     $template = file_get_contents($swPath,true); 
                     $swScript = $swScript.''.$template;
                        echo $swScript;
                        exit();
                }
            }
            
     
        } else if($req_url === '/sitemap.xml'){
            $host = $_SERVER['HTTP_HOST'];
        
            $txtMap  = '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">';
            $txtMap .= '<sitemap>';
            $txtMap .= '<loc>https://'.$host.'/sitemap/main.xml</loc>';
            $txtMap .= '<lastmod>' . $timestamp . '</lastmod>';
            $txtMap .= '</sitemap>';
            
            if (isset($i_app['WP_BLOG'])) {
                $txtMap .= '<sitemap>';
                $txtMap .= '<loc>https://'.$host.'/blog/sitemap_index.xml</loc>';
                $txtMap .= '<lastmod>' . $timestamp . '</lastmod>';
                $txtMap .= '</sitemap>';
            }
            
            $txtMap .= '</sitemapindex>';
            
            header('Content-Type: text/xml;');
            
            echo $txtMap;
            exit();
            

        }else if($req_url === '/sitemap/main.xml') {
            $contentType = 'text/javascript';
           
            if(isset($i_app['dir']) && isset($i_app['dir']['main'])){
                $projectDir = $userDir.''.$i_app['dir']['main'];
                
                if (file_exists($projectDir)) {
                   
                     $treeST         = new getDirectoryTree($projectDir);
            
                     $treeJson       = json_decode($treeST,true);
                     $treeST         = "";
                     $assetArr       = new getPagesArray($treeJson,""); 
                   
                     $assetArrDecode = json_decode( $assetArr,true);
                 
                    $mainMap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0" xmlns:pagemap="http://www.google.com/schemas/sitemap-pagemap/1.0" xmlns:xhtml="http://www.w3.org/1999/xhtml" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';


                     if(is_array(  $assetArrDecode )){
                            for($i = 0 ; $i < sizeof($assetArrDecode ); $i++){
                                $host     = $_SERVER['HTTP_HOST'];
                                $page     = $assetArrDecode[$i];
                                $link     = 'https://'.$host.'/'.$page;
                                $mainMap .='<url>';
                                $mainMap .='<loc>'.$link.'</loc>';
                            
                                $mainMap .= '<lastmod>' . $timestamp . '</lastmod>';
                                $mainMap .='<changefreq>weekly</changefreq>';
                                $mainMap .='<priority>0.5</priority>';
                                $mainMap .='</url>';
                            }
                    }

                    $mainMap .='</urlset>';
                   
                     header('Content-Type: text/xml;');
                    
                        echo $mainMap ;
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
        } elseif (preg_match('/server.js/', $req_url)) {
              
            $filePath = __DIR__ . '/../../asset/js/server.js';
          
            $is_Module = true;
        } elseif($req_url === '/i-app-ui.min.js') {
            $filePath = __DIR__ . '/../../asset/js/i-app-ui.min.js';
            $isUiJs = true;
        }  elseif ($req_url === '/icofont.css') {
            $filePath = __DIR__ . '/../../asset/css/icofont.css';
        } elseif ($req_url === '/app.png') {
            $filePath = __DIR__ . '/../../asset/img/app.png';
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

                    $datajson    = json_decode($userData,true);

                    $connection  = new checkUser($db ,["getConnectionOffer"=>$datajson]);
                    $permissions = new checkUser($db ,["getPermissions"=>$datajson]);

                    $datajson["connect"]        = $connection->userConnection;
                    $datajson["permissions"]    = $permissions;
                    $userData_                  = json_encode($datajson);
                    $userDataSt                 = "const userData = $userData_ ;"  ;
                    $regex                      = 'const userData = {};';
                    $data                       = str_replace($regex, $userDataSt, $data);

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
                echo '<h1>404 Not Found</h1><p>file_exists The requested URL  was not found on this server.</p>';
                exit();
            }
            
        } else {
         
        
            if ($backBody !== null) {
                header('Content-Type: ' . $contentType);
                echo $backBody;
                exit();
            } else {
               http_response_code(404);
                echo '<h1>404 Not Found</h1><p>backBody The requested URL  was not found on this server.</p>';
                exit();
            }
        }
    }
 
}