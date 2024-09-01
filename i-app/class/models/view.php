<?php

class view {
    public $data = "view";
    function __construct($appData, $PR_D,$userDir)
    {
          
 
 
        $this->data =new AppHeadGenerator($appData, $PR_D,$userDir);
        echo $this->data;
        exit();
      
    }

}