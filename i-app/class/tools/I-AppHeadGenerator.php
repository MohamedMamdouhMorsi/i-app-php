<?php
class AppHeadGenerator {
    private const META_TAG = '<meta charset="utf-8"><meta i_app="true"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="mobile-web-app-capable" content="yes"/><meta name="full-screen" content="yes"/><meta name="screen-orientation" content="portrait"><link rel="manifest" href="/manifest.json" ><style id="T_ASS"></style><style id="TC_ASS"></style><style id="C_ASS"></style><style id="F_ASS">';
    private const META_TAG_ ='</style><style id="F_ASSB"></style><style id="F_ASSC"></style><style id="PASSSTYLE">:root {--W:#fff;--B:#000;--BODYB: #ffffff;--BODYF: #000000;--WH__: 1000px;--WW__: 1000px;}.F_PR {color: var(--BODYF);}.B_PR {background: var(--BODYB);}</style><style id="STYLE_DIR">:root {--DirL: left;--DirR: right;}</style><style id="AUTO_DIR"></style><style> .FWI{color:#ffffff;}.FBI{color:#000000;}</style><link href="https://fonts.googleapis.com/css?family=Cairo:400,900&display=swap"  rel="stylesheet"   type="text/css"  media="print"  onload="this.media=\'all\'" /><link href="/icofont.css"   rel="stylesheet"   type="text/css"  media="print"  onload="this.media=\'all\'" /><style id="S_FONT">h1,h2,h3,h4,h5,h6,p,button,input,table,th,td,nav,div,table,a,b,tr,ul,li,tbody,select,svg,textarea,title {font-family: \'Lucida Sans Unicode\', \'Lucida Grande\', \'Cairo\', sans-serif;}</style> ';
    public $innerHTML = "<h1>No HTML !!</h1>";
    public function __construct($app, $PR_D , $userDir,$html,$css ) {
        $projectDir = $userDir.''.$app['dir']['main'];
        
        $devMode = isset($app['mode']) && $app['mode'] == 'dev';
        $lang = "en";

        if(isset($app['defLang']) ){
            $lang = $app['defLang'];

        }else if(!isset($app['defLang']) && isset($app['lang']) ){

            if(sizeof($app['lang']) >0){
                $lang =$app['lang'][0];
            }
        }

        $is_three = false;

        if(isset($app['three'])){
            $is_three = true;
        }
        $imgCover    = 'https://' . $app['domain'] .$app['dir']['icon'] . 'android-icon-192x192.png';
        $coverWidth  = "192";
        $coverHeight = "192";

        if(isset($app['linkCover'])){

            $imgCover    = $app['linkCover'];
            if (strpos($imgCover, 'http') === 0) {
               
            }else{
                $imgCover    ='https://' . $app['domain'] . $app['linkCover'];
            } 
            
            $coverWidth  = "1200";
            $coverHeight = "675";

        }
        $this->innerHTML = '<!DOCTYPE html><html lang="' . $lang . '"> <head>';
        $this->innerHTML .= '<title>' . $app['title'] . '</title>';
        $this->innerHTML .= '<meta name="type" content="' . $app['type'] . '">';
        $this->innerHTML .= '<meta name="description" content="' . $app['description'] . '">';
        $this->innerHTML .= '<meta name="keywords" content="' . $app['keywords'] . '">';
        $this->innerHTML .= '<meta name="theme-color" content="' . $PR_D . '">';
        $this->innerHTML .= '<link rel="apple-touch-icon" type="image/png" href="' . $app['dir']['icon'] . 'apple-icon.png"/ >';
        $this->innerHTML .= '<link rel="apple-touch-icon" href="' . $app['dir']['icon'] . 'apple-icon-120x120.png">';
        $this->innerHTML .= '<link rel="icon" type="image/png" href="' . $app['dir']['icon'] . 'favicon-16x16.png" sizes="16x16">';
        $this->innerHTML .= '<link rel="icon" type="image/png" href="' . $app['dir']['icon'] . 'favicon-32x32.png" sizes="32x32">';
        $this->innerHTML .= '<link rel="apple-touch-icon" href="' . $app['dir']['icon'] . 'apple-icon-120x120.png">';
        $this->innerHTML .= '<link href="https://' . $app['domain'] . '' . ($devMode ? '/i-app-basic.css' : '/i-app-basic.min.css') . '" rel="stylesheet" type="text/css" />';
        $this->innerHTML .= '<link id="css_style" href="/css/style.css" rel="stylesheet" type="text/css" />';
        $this->innerHTML .= '<meta property="og:site_name" content="' . $app['short_name'] . '" />';
        $this->innerHTML .= '<meta property="og:title" content="' . $app['title'] . '" />';
        $this->innerHTML .= '<meta property="og:description" content="' . $app['description'] . '" />';
        $this->innerHTML .= '<meta property="og:type" content="' . $app['type'] . '" />';
        $this->innerHTML .= '<meta property="og:url" content="' . $app['domain'] . '" />';
        $this->innerHTML .= '<meta property="og:image:width" content="'.$coverWidth .'">';
        $this->innerHTML .= '<meta property="og:image:height" content="'.$coverHeight .'">';
        $this->innerHTML .= '<meta property="og:image" content="' .$imgCover. '" />';
        $this->innerHTML .= '<meta name="twitter:card" content="summary_large_image">';
        $this->innerHTML .= self::META_TAG;
        $this->innerHTML .= $css;
        $this->innerHTML .= self::META_TAG_;
        

        if($is_three){
            $this->innerHTML .= '<script type="importmap">{"imports": {"three": "https://' . $app['domain'] . '/three.js"}}</script>';
        }


        if(isset($app['opencv'])){
            $this->innerHTML .= '<script async src="https://docs.opencv.org/master/opencv.js"></script>';
        }

       
        
        $this->innerHTML .= '<script type="application/javascript">const appData = '.json_encode($app).';</script/>';
        $this->innerHTML .= '<script type="application/javascript" src="' . ($devMode ? '/i-app-ui.js' : '/i-app-ui.min.js') . '" async defer ></script>';
        $this->innerHTML .= '</head> <body>';
        
        if($html !== "html" && $html !== ""){
         
            $this->innerHTML .= $html.'<div style="width: 100%; background:'. $PR_D .'; height: 100%; position: fixed; z-index: 100000;text-align: center;" id="i-app-start-screen"> <img src="' . $app['dir']['icon'] . 'apple-icon.png"  style="width:150px; height:150px;margin:auto; top:0;margin-top:40vh;" alt="'. $app['title'].'" /><div   style="width:150px;background:#999; height:10px;margin:auto; top:0;margin-top:100px;direction: ltr;"><div id="startProgressBar"  style="background:#c10000; height:10px;left:0; top:0;margin:0px;"></div></div></div>';
            $this->innerHTML .='<script> ';
            $this->innerHTML .='const scB = document.getElementById("startProgressBar"); 
               var time = 10; 
   
               const aniB = () => {
                   console.log("test " + time);
                   
                   if (time <= 100) {
                       scB.style.width = time + "%"; // Set the width of the progress bar
                       time += 10; // Increment the time (width) by 10%
                       setTimeout(aniB, 600); // Call the function again after 1 second
                   }
               };
   
               // Start the animation
               aniB(); ';
            $this->innerHTML .='</script>';
        }
        

        
         $this->innerHTML .='</body></html>';

       
    }

    public function __toString()
    {
        return (string) $this->innerHTML;
    }

}

?>


