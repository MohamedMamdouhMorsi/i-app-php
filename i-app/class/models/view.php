<?php

class view {
    public $data = "view";
    function __construct($appData, $PR_D)
    {
          
 
 
        $this->data =new AppHeadGenerator($appData, $PR_D);
        echo $this->data;
        exit();
      
    }

}