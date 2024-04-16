<?php

class i_app{

    public $system;
    public $dbC;
    function __construct($_dir)
    {
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

   
    public function get($apiUrl){
        $getArray =  new IappGet($apiUrl);
        return $getArray->getArray;
    }

    public function post($apiUrl){
        return  new IappPost($apiUrl);
    }


}
