<?php

class system {
    public  $data = "No App Data";
    public  $dbConnection; 
    public $dir;
    public $loadeAppFile;
    public $dbAppFile;
    public $i_app_file;
    public $cleanedObject;
    public $appData;
    function __construct($dir_)
    {

        $host = $_SERVER['HTTP_HOST'];
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
            
        // Construct the redirect URL without 'www'
        $redirect_url = "https://" . $host . $_SERVER['REQUEST_URI'];
        header("Location: $redirect_url");
        exit();
        }
        

        if (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
            $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("Location: $redirect_url");
            exit();
        }

        $this->dir =$dir_;
      
        $this->loadeAppFile = $this->dir."/i.app";
        $this->dbAppFile    = $this->dir."/db.app";
        
        if(file_exists($this->loadeAppFile)){

            $this->i_app_file    = file_get_contents($this->loadeAppFile,true);
            $this->cleanedObject = new IAppReader($this->i_app_file);
            $this->appData       = json_decode($this->cleanedObject,true);
            
            if(file_exists($this->dbAppFile)){
                
                $this->dbConnection  = new db($this->dbAppFile);
                if(!$this->dbConnection->chickDB()){
                    new dataBaseUpddate($this->dbConnection,$this->dir,$this->appData);
                }

            }else{
                $this->dbConnection  = false;
            }
      
        }else{
                echo "Please insert i.app file to your dir ".$this->dir;
                exit();
        }
    }

    public function getDB(){
        return  $this->dbAppFile;
    }
    public function set($key ,$data){
    
        $this->appData[$key] = $data;
    }
    public function start(){
        new middleWare($this->appData,$this->dir,$this->dbConnection,$this->loadeAppFile);
    }
  
}