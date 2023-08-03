<?php

class view {
    public $data = "view";
    function __construct($appData, $PR_D)
    {
          
        $appHeadGenerator = new AppHeadGenerator();
    
        $this->data = $appHeadGenerator->createAppHead($appData, $PR_D);

    }
    function __toString()
    {
        return (string) $this->data;
    }
}