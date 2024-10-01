<?php

class view {
    public $data = "view";
    function __construct($appData, $PR_D,$userDir,$html,$css,$url)
    {
        header("Content-Type: text/html; charset=UTF-8");

        
        $this->data = new AppHeadGenerator($appData, $PR_D,$userDir,$html,$css,$url);
        echo $this->data;
        exit();
      
    }

}