<?php

class view {
    public $data = "view";
    function __construct($appData, $PR_D,$userDir,$html,$css)
    {
        $this->data =new AppHeadGenerator($appData, $PR_D,$userDir,$html,$css);
        echo $this->data;
        exit();
      
    }

}