<?php

class middleWare {

    private $i_app;
    private $colorPR_D;
    private $manifest;
    private $tree;
    public  $userDir;
    public  $dbConnection_;
    private $i_app_st;
    private $swScript;
    private $AppThemecolors;
    private $AppThemecolorsPR;
    public $data         = "No Data ";
    public $loadeAppFile = " ";
    public $userData     ;

    function __construct($iapp,$dir,$i_app_tx,$dbConnection,$loadeAppFile_) {
       
        $this->i_app = $iapp;
        $this->dbConnection_ = $dbConnection;
        $this->AppThemecolors   = $this->getAppStyleColor();
        $this->AppThemecolorsPR = $this->getPRColor( $this->AppThemecolors );
        $this->colorPR_D        = $this->AppThemecolorsPR['PR_D'];
        $this->manifest         =  new ManifestMaker($iapp,$this->AppThemecolorsPR);
        $this->tree             = 'tree';
        $this->userDir          = $dir;
        $this->i_app_st         = $i_app_tx;
        $this->swScript         = 'swScript';
        $this->loadeAppFile     = $loadeAppFile_;
        ////////////////////////////////////

        $is_user = new GetToken('userId');
       

        $appWare = function () {

                
            $isAttack = $this->checkForSqlInjection($_SERVER['REQUEST_URI']);

            if(!$isAttack){

                $url =$_SERVER['REQUEST_URI'] ;

                // $destroy = "";
                $is_api = $this->is_api($url);
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_api) {
                
                      
                                new api($this->i_app,$this->userDir,$this->i_app_st,$this->dbConnection_,$this->userData,$this->loadeAppFile);
                            
                    }
                
                if (isset($_GET['url'])) {

                    //  $userRouter = router::match($req, $res);
                
                    $userRouter = false;

                    if (!$userRouter) {

                        $extname  = pathinfo($url, PATHINFO_EXTENSION);
                        if($extname !== ''){
                            $testScript = explode('?', $extname);
                             if(count($testScript) > 1){
                                $extname = $testScript[0];
                             }
                         
                        }
                        $is_app   = $this->is_app($extname);
                        $is_asset = $this->is_asset($extname);
                        $is_route = $this->is_route($url ,$extname);
                        $fileName = $this->getfileName($url);
                    
                        if ($is_app) {
                        //  return app files
                    
                            new AppFileHandler($url,$extname , $fileName, $this->manifest,$this->i_app_st, $this->tree, $this->userDir, $this->i_app);
                        
                        } else if ($is_asset) {

                        //   return asset file img , js , css and others      
                            $assetUserData = "FALSE";
                            if(isset( $this->userData->userData["id"])){
                                $assetUserData = json_encode( $this->userData->userData);
                           
                            }
                        
                        
                            new AssetFileHandler($url,$extname,$this->userDir,$this->swScript, $assetUserData , $this->i_app);
                
                        } else if ($is_route) {

                            //   return route app file
                            new view($this->i_app,$this->colorPR_D);
                    
                        } else {

                            echo  "<h1>500 Internal Server Error</h1>";
                            exit();
                            
                        }
                    }
                }
            }
        };

        if (isset($this->i_app['users'])) {

            // close app with users system 
            
            if ($is_user != "FALSE") {
          
                //  isUser 

                $this->userData = new userInfo( $this->dbConnection_);
         
                
            
                $appWare();

            } else {

                // !isUser 
                
                $appWare();
            
            }

        } else {

                // public app

                $appWare();

        }
    
    }

    public function checkForSqlInjection($postData) {

        $sqlInjectionPattern = '/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE)?|INSERT(INTO)?|MERGE|SELECT|UPDATE)\b/i';
    
        if (is_array($postData)) {
            foreach ($postData as $value) {
                if (is_string($value) && preg_match($sqlInjectionPattern, $value)) {
                    // Potential SQL injection detected
                    return true;
                }
            }
        } elseif (is_string($postData) && preg_match($sqlInjectionPattern, $postData)) {
                    // Potential SQL injection detected
                    return true;
        }
    
        return false;
    }
    
    public function getFileName($reqUrl) {

        $reqStAr = explode("/", $reqUrl);
    
        if (count($reqStAr) > 1) {

            $fileNameEx     = end($reqStAr);
            $fileNameExArr  = explode('.', $fileNameEx);
            $fileName       = $fileNameExArr[0];
         
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
            $styleData   = json_decode($styleDataSt,true);

            return   $styleData;

        }else{

            $basicStyleDir  = __DIR__.'/../../asset/css/style.json';
            $styleDataSt    = file_get_contents( $basicStyleDir,true); 
            $styleData      = json_decode($styleDataSt,true);

            return  $styleData ;
        }
       
     }

     public function getPRColor($styleData){

        $theme      = $styleData['theme'];
        $themeData  = [];
        $colorPR    = [];

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