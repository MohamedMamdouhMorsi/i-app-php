<?php

class middleWare {

    public $i_app;
    public $colorPR_D;
    public $manifest=[];
    public $tree;
    public  $userDir = "dir";
    public  $dbConnection_;
    public $i_app_st;
    public $swScript;
    public $AppThemecolors;
    public $AppThemecolorsPR;
    public $data         = "No Data ";
    public $loadeAppFile = " ";
    public $userData = array("userData"=>array());

    function __construct($iapp,$dir,$dbConnection,$loadeAppFile_) {
      
        $this->i_app            = $iapp;
        $this->dbConnection_    = $dbConnection;
        $this->tree             = 'tree';
        $this->userDir          = $dir;
        $this->i_app_st         = json_encode($iapp);
        $this->swScript         = 'swScript';
        $this->loadeAppFile     = $loadeAppFile_;
        
        ////////////////////////////////////

        $this->AppThemecolors   = $this->getAppStyleColor();
        $this->AppThemecolorsPR = $this->getPRColor( $this->AppThemecolors );
        $this->colorPR_D        = $this->AppThemecolorsPR['PR_D'];


        if(isset($this->AppThemecolorsPR['theme'])){
            $this->colorPR_D    = $this->AppThemecolorsPR['theme'];
        }

        
        $this->manifest         =  new ManifestMaker($iapp,$this->AppThemecolorsPR);


        //////////////////////////////////////
        $is_user = new GetToken('userId');
       

      

        if (isset($this->i_app['users'])) {

            // close app with users system 
            
            if ($is_user != "FALSE") {
          
                //  isUser 

                $this->userData = new userInfo( $this->dbConnection_);
                $this->appWare();

            } else {

                // !isUser 
                
                $this->appWare();
            
            }

        } else {

                // public app

                $this->appWare();

        }
    
    }

    function deleteFirstSlash($str) {
        // Find the position of the first forward slash
        $pos = strpos($str, '/');
        
        // If a forward slash is found
        if ($pos !== false) {
            // Remove the first forward slash
            $str = substr_replace($str, '', $pos, 1);
        }
        
        return $str;
    }
    public function checkForSqlInjection($postData) {
        // SQL injection pattern
        $sqlInjectionPattern = '/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE)?|INSERT(INTO)?|MERGE|SELECT|UPDATE)\b/i';
    
        // Check if $postData is an array
        if (is_array($postData)) {
            // Iterate through each element of the array
            foreach ($postData as $value) {
                // Check if $value is a string and matches the SQL injection pattern
                if (is_string($value) && preg_match($sqlInjectionPattern, $value, $matches)) {
                    // Potential SQL injection detected, return the matched attack keyword
                    return $matches[0];
                }
            }
        } 
        // Check if $postData is a string and matches the SQL injection pattern
        elseif (is_string($postData) && preg_match($sqlInjectionPattern, $postData, $matches)) {
            // Potential SQL injection detected, return the matched attack keyword
            return $matches[0];
        }
    
        // No potential SQL injection detected, return false
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
 
    function appWare () {
    
     

        $isAttack = $this->checkForSqlInjection($_SERVER['REQUEST_URI']);
    
        if(!$isAttack){
            $is_logout = false;

            if($_SERVER['REQUEST_URI'] == "/logout"){
                $is_logout = true;
            }

            if(isset($this->userData->userData) && $is_logout){
                new logoutUser($this->dbConnection_,$this->userData->userData);
            }

            $url =$_SERVER['REQUEST_URI'] ;
            
            if(isset($this->i_app["lang"])){
                for($k = 0 ; $k < sizeof($this->i_app["lang"]); $k++){
                    $lang = '/'.$this->i_app["lang"][$k].'/';
                    if (strpos($url, $lang) === 0) {
                        if($url == $lang){
                            $url = "/";
                        }else{
                            $url = str_replace($lang, "/",$url);
                        }
                       
                    }
                }
            }
                    
            // $destroy = "";
            $is_api = $this->is_api($url);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_api) {
                if(isset($_POST)){
                    $msgPost = base64_decode($_POST['msg']);
                    $isAttack = $this->checkForSqlInjection($msgPost );
                }
                if(!$isAttack){
                                $lastUserData = [];
                                
                                if(isset($this->userData->userData)){
                                    $lastUserData =$this->userData->userData;
                                }
                  
                            new api($this->i_app,$this->userDir,$this->i_app_st,$this->dbConnection_,$lastUserData,$this->loadeAppFile);
                }else{

                            echo  "<h1> 500 Internal Server Error $isAttack</h1>";
                            exit();

                }
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
                        if(isset( $this->userData->userData)){
                            $assetUserData = json_encode( $this->userData->userData);
                       
                        }
                    
                    
                        new AssetFileHandler($url,$extname,$this->userDir,$this->swScript, $assetUserData , $this->i_app, $this->dbConnection_);
            
                    } else if ($is_route) {

               
                            if($url == "" || $url == "/"){
                                $url = $this->i_app['dir']["start"];
                                $url = str_replace('.app', '', $url);
                          }
                            
                             if(isset($this->i_app['dir']['main']) && isset($this->i_app['dir']['src'])){
                                $srcMain = $this->i_app['dir']['main'];
                                $srcDir = $this->i_app['dir']['src'];
                                $projectDir = $this->userDir.''.$srcMain.''.$srcDir;
                                $fixedDirectory = str_replace('//', '/', $projectDir);
                                $projectDir_ = $this->userDir.''.$srcMain.''.$srcDir.$url.'.app';
                                $Directory =  str_replace('//', '/', $projectDir_);
                             
                                 
                                if(file_exists($Directory )){
                                    
                                    $RouteData    = new RouteData( $Directory ,$url,$srcDir,$fixedDirectory );
                                    $app_data     = $RouteData->getData();
                                    $html         = "html";
                                    $css          = "";
                                    if($app_data !== "html"){
    
                                        $htmlDo       = new CreateHTML($app_data,$this->userDir,$this->i_app);
                                        $html         = $htmlDo->getHTML();
                                        $css          = $htmlDo->getCSS();
                                    }
                                  
                                  
                                    new view($this->i_app,$this->colorPR_D,$this->userDir,$html,$css,$url);
                                }else{
                                    new view($this->i_app,$this->colorPR_D,$this->userDir,"","",$url);
                                }
                             
                            }
                        
                 
                
                    } else {

                        echo  "<h1>500 Internal Server Error 2</h1>";
                        exit();
                        
                    }
                }
            }
        }
    }
    
    public function  is_app($ext){

        if($ext === 'app' || $ext === 'json'){
         return true;
        }
         return false;
    }

    public function  getAppStyleColor(){

     
        $styleDir = $this->deleteFirstSlash($this->i_app['dir']['style']);
        $mainDir  = $this->i_app['dir']['main'];
        $styleDir_ = $this->userDir.$mainDir.$styleDir;
   
      if(file_exists($styleDir_)){

            $styleDataSt_ = file_get_contents($styleDir_ ,true); 
          
            $styleData_   = json_decode($styleDataSt_  ,true);
         
           return   $styleData_;

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
                if($color['k'] == "theme"){

                    $colorPR['theme'] = $color['v'];
                }
        }
    
       return $colorPR;
     }


    }