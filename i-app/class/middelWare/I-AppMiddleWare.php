<?php

class middleWare {
    private $req;
    private $res;
    private $i_app;
    private $colorPR_D;
    private $manifest;
    private $tree;
    private $userDir;
    private $i_app_st;
    private $swScript;
    private $AppThemecolors;
    private $AppThemecolorsPR;

    public function __construct($i_app,$dir,$i_app_st) {
        $this->req = $_REQUEST; // Assume required data is passed as URL parameters
        $this->res = new stdClass(); // Dummy response object for demonstration purposes
        $this->i_app = $i_app;
        $this->AppThemecolors = $this->getAppStyleColor();
        $this->AppThemecolorsPR = $this->getPRColor( $this->AppThemecolors );
        $this->colorPR_D = $this->AppThemecolorsPR['PR_D'];
        $manifestMaker = new ManifestMaker();
        $this->manifest = $manifestMaker->manifestMaker($i_app,$this->AppThemecolorsPR);

        $this->tree = '$tree';
        $this->userDir = $dir;
        $this->i_app_st = $i_app_st;
        $this->swScript =' $swScript';
    }

    public function middleWareApp() {

        $is_user = false;
        $userData = null;

        $appWare = function ($req, $res, $userData) {
            $req->user = $userData;
            $url = $_SERVER['REQUEST_URI'];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (property_exists($res, 'destroySession')) {
                    echo 'res.destroySession';
                  
                } else {
                    $is_api = $this->is_api($url);
                    if ($is_api) {
                       /// return api($req, $res);
                    } else {
                     /*   $userRouterPost = routerPost::match($req, $res);
                        if (!$userRouterPost) {
                            $res->writeHead(200, array('Content-Type' => 'text/html'));
                            $res->end('<h1>500 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>');
                        }*/
                    }
                }
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
              //  $userRouter = router::match($req, $res);
              $userRouter = false;
                if (!$userRouter) {
                    $extname = pathinfo($url, PATHINFO_EXTENSION);
                    $is_app = $this->is_app($extname);
                    $is_asset = $this->is_asset($extname);
                    $is_route = $this->is_route($extname);
                    $fileName = $this->getfileName($url);

                    if ($is_app) {
                      //  return app_file($req, $res, $extname, $fileName, $this->manifest, $this->i_app_st,  $this->userDir, $this->i_app);
                      $appFileHandler = new AppFileHandler();
                      $data = $appFileHandler->handleAppFile($url,$extname , $fileName, $this->manifest,$this->i_app_st, $this->tree, $this->userDir, $this->i_app);
                        return $data;
                    } else if ($is_asset) {
                     //   return asset_file($req, $res, $this->userDir, $this->swScript, $userData);
                     $assetFileHandler = new AssetFileHandler();
                     $data = $assetFileHandler->handleAssetFile($url, $this->userDir,$this->swScript, $userData);
                    return $data;
                    } else if ($is_route) {
                     //   return route_file($req, $res, $this->i_app, $this->colorPR_D);
                     $data =  new view($this->i_app,$this->colorPR_D);
                     return $data;
                    } else {
                     /*   $res->writeHead(400, array('Content-Type' => 'text/html'));
                        $res->end('<h1>500 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>');
                        */
                    }
                }
            }
        };

        if ($this->i_app['users']) {
            if ($is_user) {
               // sessionData($this->req, $this->res, $appWare, $userData);
            } else {
             //   sessionsControl($this->req, $this->res, $appWare, $userData);
            }
        } else {
            $appWare($this->req, $this->res, array('id' => 0, 'notBasic' => true));
        }
    }
    public function getFileName($reqUrl) {
        $reqStAr = explode("/", $reqUrl);
    
        if (count($reqStAr) > 1) {
            $fileNameEx = end($reqStAr);
            $fileNameExArr = explode('.', $fileNameEx);
            $fileName = $fileNameExArr[0];
            return $fileName;
        } else {
            return '';
        }
    }
    public function  is_api($url){

        if($url == '/api'){
            return true;
        }
        return false;
    }
    public function  is_asset($url){
        if($url !== '' && $url !== '.app' && $url !== '.json'){
            return true;
           }
            return false;
     }
     public function  is_route($url){

        if($url == ''){
            return true;
        }
        return false;
     }
     public function  is_app($url){

        if($url === '.app' || $url === '.json'){
         return true;
        }
         return false;
     }
     public function  getAppStyleColor(){

       
        $styleDir = $this->userDir.$this->i_app['dir']['style'];
        if(file_exists($styleDir)){
            $styleDataSt = file_get_contents($styleDir,true); 
            $styleData = json_decode($styleDataSt,true);
            return  $styleData ;
        }else{
            $basicStyleDir = __DIR__.'../../asset/css/style.json';
            $styleDataSt = file_get_contents( $basicStyleDir,true); 
            $styleData = json_decode($styleDataSt,true);
            return  $styleData ;
        }
       
     }
     public function getPRColor($styleData){
        $theme = $styleData['theme'];
        $themeData = [];
        $colorPR = [];
        if($theme == "dark"){
            $themeData  = $styleData['dc'];
            }else{
                $themeData  = $styleData['lc'];
            }
               
       for($c = 0 ; $c < sizeof($themeData);$c++){
        $color = $themeData[$c];
        if($color['k'] == "PR_D"){
            $colorPR['PR_D'] = $color['v'];
        }
        if($color['k'] == "PR"){
            $colorPR['PR'] = $color['v'];
        }
       }

       return $colorPR;
     }
}
/*
<?php
// Assuming the required data is passed as URL parameters
$i_app = ...; // Set the i_app array
$colorPR_D = ...; // Set your colorPR_D value
$manifest = ...; // Set the manifest array
$tree = ...; // Set the tree array
$userDir = ...; // Set your userDir value
$i_app_st = ...; // Set your i_app_st value
$swScript = ...; // Set your swScript value

$middleware = new MiddleWareApp($i_app, $colorPR_D, $manifest, $tree, $userDir, $i_app_st, $swScript);
$middleware->middleWareApp();
?>
*/