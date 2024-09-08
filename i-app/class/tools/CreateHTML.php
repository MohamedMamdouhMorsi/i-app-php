<?php

class CreateHTML{
    public $html = "";
    public $i_app =[];
    public $lang_txt = [];
    public $css_list = [];
    public $userDir = "";
    
    function __construct($data_,$dir,$app)
    {
        $dataSt_ = json_encode($data_);
       $dataJo =    str_replace(', ","', ' ","', $dataSt_);
       $data =json_decode($dataJo,true);
        $this->i_app   = $app;
        $this->userDir = $dir;
        $txtDir        = ltrim($this->i_app["dir"]["txt"], '/');
        $txt           = $this->userDir.$this->i_app["dir"]["main"].$txtDir."ar.json";
        $langTxt       = file_get_contents( $txt ,true);
        $this->lang_txt = json_decode($langTxt,true);
        $this->html   .='<div id="i-app" style="opacity:0;">';

        if(isset($data["e"])){
            $this->html .=$this->pharseElm($data);
        }
    
        $this->html .='</div>';

    }

    function replaceTextKeys($string, $jsonObject) {
        // Match all instances of t.{textKey} in the string
        return preg_replace_callback('/t\.\{([^}]+)\}/', function($matches) use ($jsonObject) {
            $textKey = trim($matches[1]);
            // Check if the key exists in the JSON object and return its value, otherwise return the original pattern
            return isset($jsonObject[$textKey]) ? $jsonObject[$textKey] : $matches[0];
        }, $string);
    }

    function pharseElm($elm){
        $elm_arr = $elm["e"];
        for($i = 0 ; $i < sizeof($elm_arr); $i++){

                $obj         = $elm_arr[$i];
                $tag         = $this->HT_($obj);
                
                $attr        = $this->addAtrr($obj);
                $this->html .='<'.$tag.'  '.$attr.' >';

                $txt_S       = "";
             
                if(isset($obj["s"])){

                    $trv   = chop($obj["s"], ',');
                    $txt_S = $this->replaceTextKeys( $trv,$this->lang_txt);

                }

                $this->html .= $txt_S ;

                if(isset($obj["e"])){
                    $this->html .=$this->pharseElm($obj);
                }

                $this->html .='</'.$tag.'>';

        }
    }

    function HT_($obj) {
        $typ = '';
    
        if (isset($obj['typ'])) {
            $typ = $obj['typ'];
        } elseif (isset($obj['t'])) {
            $typ = $obj['t'];
        }
    
        $tagNames = array(
            "tring" => "tring",
            "ti" => "h2",
            "tx" => "p",
            "ly" => "div",
            "ls" => "div",
            "in" => "input",
            "txa" => "textarea",
            "bt" => "button",
            "img" => "img",
            "br" => "br",
            "hr" => "hr",
            "ico" => "ico",
            "icon" => "icon",
            "cr" => "cr",
            "sl" => "select",
            "op" => "option",
            "ifr" => "iframe",
            "a" => "a",
            "gos" => "gos",
            "t" => "table",
            "tr" => "tr",
            "td" => "td",
            "sp" => "span",
            "lb" => "label",
            "th" => "th",
            "form" => "form",
            "b" => "b",
            "mar" => "marquee",
            "canv" => "canvas",
            "nav" => "nav",
            "ul" => "ul",
            "li" => "li",
            "thead" => "thead",
            "tbody" => "tbody"
        );
    
        if (isset($tagNames[$typ])) {
            return $tagNames[$typ];
        } elseif ($typ == '') {
            return "div";
        } else {
            return $typ;
        }
    }

    function C_CSS($cs){
        $c = [];
        if(is_array($cs)){
            $c = $cs;
        }else{
            $c = explode(" ", $cs);
        }
       
        $obj_css = "";
        for($i = 0 ; $i < sizeof($c); $i++){
            $cls = $c[$i];
            if(isset($this->css_list[$cls])){
                $c_css = $this->G_CSS($cls);
                if($c_css && $c_css !== ""){
                    $obj_css .= " ".$this->css_list[$cls];
                }
            }else{
                $c_css = $this->G_CSS($cls);
                if($c_css && $c_css !== ""){
                    $obj_css .= " ".$cls;
                }
                $this->css_list[$cls] = $c_css;
            }
        }
        return $obj_css ;
    }

    function G_CSS($cs) {
        $c = explode("_", $cs);
    
        switch($c[0]) {
           
            case "sc":
                if (count($c) === 3) {
                    return ".$cs {transform: scale(" . $c[1] . "." . $c[2] . ");} ";
                }
                break;
            case "XO":
                if ($c[1] !== "ST") {
                    $cc = $c[1];
                    if (count($c) === 3 && $c[1] === "PR" && $c[2] === "D") {
                        $cc = "PR_D";
                    }
                    $ssB = ".XO_$cc{ width: 100%; height: 100%; background: transparent;}";
                    $ssC = ".XO_$cc::before { content: ''; clip-path: polygon(100% 50%, 88% 81%, 0% 82%, 0% 81%,87.7% 80%,99% 50.00%,87.7% 19%, 0% 19%,0% 18%, 88% 18%); background:var(--$cc); width: 50%; height: 100%; position: absolute; left: 0;}";
                    $ssD = ".XO_$cc::after { content: ''; clip-path: polygon(0% 50%, 12% 82%, 100% 82%, 100% 81%,12.3% 80%,0.8% 50.00%,12.3% 19%, 100% 19%,100% 18%, 12% 18%); background:var(--$cc); width: 50%; height: 100%; position: absolute; left: 50%;}";
                    return $ssB . $ssC . $ssD;
                }
                break;
            case "hex":
                if ($c[1] !== "ST") {
                    $cc = $c[1];
                    if (count($c) === 3 && $c[1] === "PR" && $c[2] === "D") {
                        $cc = "PR_D";
                    }
                    $ssA = ".hex_$cc { content: ''; clip-path: polygon(16.67% 50.00%, 33.33% 78.87%, 66.67% 78.87%, 83.33% 50.00%, 66.67% 21.13%, 33.33% 21.13%); background:var(--$cc); width: 100%; height: 100%;}";
                    return $ssA;
                }
                break;
            case "OPC":
                if (count($c) === 2) {
                    return ".$cs {opacity: 0." . $c[1] . ";} ";
                }
                break;
            case "B":
                if ($c[1] == "R") {
                    if (count($c) === 3) {
                        return ".$cs {border-radius: " . $c[2] . "px}  ";
                    } elseif (count($c) === 5) {
                        switch($c[2] . $c[3]) {
                            case "TL":
                                return ".$cs {border-top-left-radius: " . $c[4] . "px;} ";
                            case "TR":
                                return ".$cs {border-top-right-radius: " . $c[4] . "px;} ";
                            case "BL":
                                return ".$cs {border-bottom-left-radius: " . $c[4] . "px;} ";
                            case "BR":
                                return ".$cs {border-bottom-right-radius: " . $c[4] . "px;} ";
                        }
                    }
                } else {
                    if (count($c) === 2) {
                        $cc = ($cs === "B_B_") ? "B_" : str_replace("B_", "", $cs);
                        if (isset($selected_theme_colors[$cc])) {
                            return ".$cs {background-color: var(--$cc);}  ";
                        } else {
                            return false;
                        }
                    } elseif (count($c) === 3 && $cs === "B_PR_D") {
                        return ".$cs {background-color: var(--PR_D);}  ";
                    }
                }
                break;
            case "LH":
                if (count($c) === 2) {
                    return ".$cs { line-height:  " . $c[1] . ";} ";
                }
                break;
            case "F":
                if ($c[1] == "S") {
                    if (count($c) === 3) {
                        return ".$cs { font-size:" . $c[2] . "px;}  ";
                    }
                } else {
                    $cc = str_replace("F_", "", $cs);
                    if (isset($selected_theme_colors[$cc])) {
                        return ".$cs {color: var(--$cc);}  ";
                    } else {
                        return false;
                    }
                }
                break;
            case "PD":
                if ($c[1] == "P") {
                    if (count($c) === 3) {
                        return ".$cs { padding:" . $c[2] . "%;}  ";
                    }
                } elseif (count($c) === 2) {
                    return ".$cs { padding:" . $c[1] . "px;}  ";
                }
                break;
            case "H":
                if ($c[1] === "P" && count($c) === 3) {
                    return ".$cs {height:" . $c[2] . "%;}   ";
                } elseif (count($c) === 2) {
                    return ".$cs {height:" . $c[1] . "px;}  ";
                }
                break;
            case "W":
                if ($c[1] === "P" && count($c) === 3) {
                    return ".$cs {width:" . $c[2] . "%;}   ";
                } elseif (count($c) === 2) {
                    return ".$cs {width:" . $c[1] . "px;}  ";
                }
                break;
            // Add other cases similarly
        }
    
        return false;
    }
    
    function G_SRC($src) {
        if ($src == 'app.png') {
            return $src;
        }
    
        $chick = explode("_", $src);
    
        if (count($chick) > 1) {
            if ($chick[0] == "J") {
                return  $this->i_app['dir']['img'] . $chick[1] . '.jpg';
            } elseif ($chick[0] == "P") {
                return $this->i_app['dir']['img'] . $chick[1] . '.png';
            } elseif ($chick[0] == "G") {
                return $this->i_app['dir']['img'] . $chick[1] . '.gif';
            }
        }
    
        return 'https://' .  $_SERVER['HTTP_HOST']. $this->i_app['dir']['img'] . $src;
    }


    function addAtrr($obj){
        $attr = "";
        
        if (isset($obj['hr'])) {
            if (isset($obj['hr']['http'])) {
               $attr .= ' href="http://' . $obj['hr']['http'].'" ';
            } elseif (isset($obj['hr']['https'])) {
               $attr .= ' href="https://' . $obj['hr']['https'].'" ';
            } elseif (isset($obj['hr']['tel'])) {
               $attr .= ' href="tel:' . $obj['hr']['tel'].'" ';
            } elseif (isset($obj['hr']['mailto'])) {
               $attr .= ' href="mailto:' . $obj['hr']['mailto'].'" ';
            } else {
               $attr .= ' href="mailto:' .$obj['hr'].'" ';
            }
        }
        
        if (isset($obj['src'])) {
            $src   =  $this->G_SRC($obj['src']);
            $attr .= ' src="' .$src.'" ';
        }
        
        // if(isset($obj["c"])){
        //   $cls   =  $this->C_CSS($obj['c']);
        //  $attr .= ' class="' .$obj['c'] .'" ';
            
        // }else if(isset($obj["cls"]) && is_array($obj["cls"]) ){
        //    $cls   =  $this->C_CSS($obj['cls']);
        //  $stringCls = "";
        //    for($j =0; $j < sizeof($obj["cls"]); $j++){
        //      $stringCls .= " ".$obj["cls"][$j];
        // }
        //    $attr .= ' class="' .$stringCls .'" ';
        //}

        return $attr;

    }

    function getHTML(){
        return (string) $this->html;
    }

    function getCSS(){
        $css_txt = "";
            foreach ($this->css_list as $key => $value) {
                $css_txt .= " ".$value;
            }
        return $css_txt;
    }
 
}