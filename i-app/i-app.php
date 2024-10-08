<?php

class i_app{

    public $system;
    public $dbC;
    public $userDir ;
    function __construct($_dir)
    {
        $this->userDir = $_dir;
        $this->system = new system($_dir); 
        $this->dbC =  $this->system->getDB();
    }

    public function start(){
        $this->system->start();
    }
    public function set($key ,$data){
        $this->system->set($key ,$data);
    }
    public function query($query){
       $d = new db($this->dbC);
       return $d->query($query);
      
    }
    public function user(){
        $d = new db($this->dbC);
       return new userInfo($d );
    }
    public function getDir(){
     
       return  $this->userDir ;
    }
    public function get($apiUrl){
        $getArray =  new IappGet($apiUrl);
        return $getArray->getArray;
    }

    public function post($apiUrl){
        $postArray =  new IappPost($apiUrl);
        return $postArray->postArray;
    }


}
