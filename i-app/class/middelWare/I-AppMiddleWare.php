<?php

class middleWare {
    private $req;
    private $res;
    private $i_app;
    private $colorPR_D;
    private $manifest;
    private $tree;
    public $userDir;
    private $i_app_st;
    private $swScript;
    private $AppThemecolors;
    private $AppThemecolorsPR;
    public $data = "No Data ";

    public function __construct($i_app,$dir,$i_app_st) {
       
        $this->req = $_REQUEST; // Assume required data is passed as URL parameters
        $this->res = new stdClass(); // Dummy response object for demonstration purposes
        $this->i_app = $i_app;
        $this->AppThemecolors = $this->getAppStyleColor();
        $this->AppThemecolorsPR = $this->getPRColor( $this->AppThemecolors );
        $this->colorPR_D = $this->AppThemecolorsPR['PR_D'];
      
        $this->manifest =  new ManifestMaker($i_app,$this->AppThemecolorsPR);
       
        $this->tree = 'tree';
        $this->userDir = $dir;
        $this->i_app_st = $i_app_st;
        $this->swScript ='swScript';

        ////////////////////////////////////
        $is_user = false;
       

        $appWare = function () {
            $userData = 'FALSE';
           
            $url = $_SERVER['REQUEST_URI'];
            // $destroy = "";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              //  if ($destroy) {
                //    echo 'res.destroySession';
                  
               // } else {
                    $is_api = $this->is_api($url);
                    if ($is_api) {
                        echo "api";
                        exit();
                       /// return api($req, $res);
                    } 
                    //else {
                     /*   $userRouterPost = routerPost::match($req, $res);
                        if (!$userRouterPost) {
                            $res->writeHead(200, array('Content-Type' => 'text/html'));
                            $res->end('<h1>500 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>');
                        }*/
                    //}
                }
            //} else 
            
            if (isset($_GET['url'])) {
              //  $userRouter = router::match($req, $res);
              
              $userRouter = false;
                if (!$userRouter) {
                    $extname = pathinfo($url, PATHINFO_EXTENSION);
                    $is_app = $this->is_app($extname);
                    $is_asset = $this->is_asset($extname);
                    $is_route = $this->is_route($url ,$extname);
                    $fileName = $this->getfileName($url);
                
                   
             
                    
                    if ($is_app) {
                      //  return app_file($req, $res, $extname, $fileName, $this->manifest, $this->i_app_st,  $this->userDir, $this->i_app);
                  
                      new AppFileHandler($url,$extname , $fileName, $this->manifest,$this->i_app_st, $this->tree, $this->userDir, $this->i_app);
                      
                    } else if ($is_asset) {
                     //   return asset_file($req, $res, $this->userDir, $this->swScript, $userData);
                   
                
                    new AssetFileHandler($url,$extname,$this->userDir,$this->swScript, $userData);
               
                    } else if ($is_route) {
                     //   return route_file($req, $res, $this->i_app, $this->colorPR_D);
                     new view($this->i_app,$this->colorPR_D);
                  
                    } else {
                        echo  "<h1>500 Internal Server Error</h1>";
                        exit();
                     /*   $res->writeHead(400, array('Content-Type' => 'text/html'));
                        $res->end('<h1>500 Internal Server Error</h1><p>Sorry, there was a problem loading the requested URL.</p>');
                        */
                    }
                }
            }
        };

        if (isset($this->i_app['users'])) {
            if ($is_user) {
               // sessionData($this->req, $this->res, $appWare, $userData);
            } else {
             //   sessionsControl($this->req, $this->res, $appWare, $userData);
            }
        } else {
       
            $appWare();
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
    public function  is_asset($ext){
      
        if($ext !== '' && $ext !== 'app' && $ext !== 'json'){
            return true;
           }
            return false;
     }
     public function  is_route($url,$ext ){

        if(  $url !== '' && $ext ===  '' ){
            return true;
        }else{
            return false;
        }

     }
     public function  is_app($ext){

        if($ext === 'app' || $ext === 'json'){
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
            $basicStyleDir = __DIR__.'/../../asset/css/style.json';
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