<?php


class RouteData {

    public $userDir ;
    public $userSD;
    public $lastSrc ;
    public $app_data = [];
    function __construct($dir,$req_url,$userSrcDir,$user_Dir)
    {
        $this->userDir = $user_Dir;
        $this->userSD  = $userSrcDir;
        $file = file_get_contents($dir,true);
        $appDataSt  =  new IAppReadSave($file,$req_url,$userSrcDir);
        $this->app_data    =  json_decode($appDataSt,true);
        if(isset($this->app_data["page"]) && isset($this->app_data["SEO"]) ){
            if(isset($this->app_data["e"])){
                $this->app_data = $this->getElement("home");
               
            }
        }
  
    }
    function getData(){
        return  $this->app_data;
    }
    function pharseElm($appData){
        for($i = 0 ; $i < sizeof($appData["e"]); $i++){
            $obj  = $appData["e"][$i];
            if(isset($obj["I"])){
                $elmName = $obj["I"];
                $appData["e"][$i] = $this->getElement($elmName);
            
            }else if(isset($obj["e"])){
                $elmArr = $appData["e"][$i];
                $appData["e"][$i]= $this->pharseElm($elmArr );
            }
        }
        return $appData;
    }
    function getElement($elm){
        $req_url = '/'.$elm.'.app';
        $fixedDirectory =  $this->userDir.$req_url ;
        if(file_exists($fixedDirectory)){
            $elmType = "div";
           
            $file = file_get_contents($fixedDirectory,true);
            $appDataSt  =  new IAppReadSave($file,$req_url, $this->userSD);
            $appData    =  json_decode($appDataSt,true);
         
                if(isset($appData["e"])){
                   
                    $appData = $this->pharseElm($appData);
                }
            
            return $appData;
        }else{
            echo "can not found". $elm ;
            exit();
        }
    }
}