<?php

class CreateHTML{
    public $html = "";
    function __construct($data)
    {
        $this->html .='<div id="i-app-os">';
        if(isset($data["e"])){
            $this->html .=$this->pharseElm($data);
        }
       
        $this->html .='</div>';

    }
    function pharseElm($elm){
        $elm_arr = $elm["e"];
        for($i = 0 ; $i < sizeof($elm_arr); $i++){

                $obj = $elm_arr[$i];
                $tag = "div";
                if(isset($obj["t"])){
                    $tag = $obj["t"];
                }

                $this->html .='<'.$tag.'>';
                
                $txt_S = "";
                if(isset($obj["s"])){
                    $txt_S = $obj["s"];
                }

                $this->html .= $txt_S;

                if(isset($obj["e"])){
                    $this->html .=$this->pharseElm($obj);
                }

                $this->html .='</'.$tag.'>';

        }
    }
    function __toString()
    {
        return (string) $this->html;
    }
}