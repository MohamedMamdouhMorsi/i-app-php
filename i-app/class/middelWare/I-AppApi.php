<?php 

    class api {
        function __construct($i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile)
        {

            header('Content-Type: application/json');
            // 

            if(isset($_POST["msg"])){

                
                $msg       = $_POST["msg"];
                $msgEncode = $this->zipS($msg);
               
                $msgJson   = json_decode($msgEncode,true);
                
                if(isset($msgJson["order"])){

                    // do order
                        $order  = $msgJson["order"];
                        
                        new orders($order,$msgJson, $i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile);
                    
                }else  if(isset($msgJson["query"])){

                    // do query

                    $query = $msgJson["query"];

                    new query($query , $i_app,$dir,$i_app_st,$dbConnection,$userData);
                
            }else{
                echo '{ "message": "Invalid request method" , "error":'.$msgEncode.' }';
                
                exit();
            }
            }else{

                echo '{ "message": "Invalid request method" }';
                
                exit();

            }

        }
    
    function zipS($txtO) {

        $symbol  = ['►','◄','▲','▼','я','з','л','ь','д','Ф','и','й','ч','ш','ж','я','Д','Э','Ц','щ','г','п','б','ъ','Ю','ä','ß','ü','Ü','ö','ñ','è','ê','É','à'];
        $keys    = ['☺','☻','♥','♦','♣','♠','ф'];
        $keys_    = ['$','&','|','#','@','!','_'];
        $txt = str_replace($keys, $keys_, $txtO);
            $zAR = [];
            $zAR = explode(",", $txt);
        
            if (sizeof($zAR) < 8) {
                return $txt;
            }
           
        /// two
       
            $index2 = sizeof($zAR) - 1;
            $BD2    = [];
            $BD2TX  = $zAR[$index2];
        
            for ($i = 0; $i < strlen($BD2TX); $i += 2) {
                $BD2TX_ = substr($BD2TX, $i, 2);
                array_push($BD2,$BD2TX_);
            }

            for ($i = 0; $i < sizeof($BD2); $i++) {

                $key_   = $symbol[$i];
                $val_   = "$BD2[$i]";
                $regex  = $key_;
                
              
                for ($x = 0; $x < $index2; $x++) {

                    $txtQ = $zAR[$x];

                   if($txtQ !== ""){
                        $zAR[$x] = str_replace($regex, $val_, $txtQ);
                   }

                }
            }

        // four
        
                $index4 = sizeof($zAR) - 2;
                $BD4    = [];
                $indexSymp4 = $keys_[5];
                $BD4TX = $zAR[$index4];

                

                    if($BD4TX !== ''){

                        for ($i = 0; $i < strlen($BD4TX); $i += 4) {

                            $BD4TX_ = substr($BD4TX, $i, 4);
                            array_push($BD4,$BD4TX_);

                        }

                        $BD4A = [];

                        for ($i = 0; $i < sizeof($BD4); $i++) {
                            $key_   = $i + 1;
                            $val_   = $BD4[$i];
                            $upAr   = [$key_,$val_];
                            array_push($BD4A,$upAr);
                        
                        }

                        $BD4AR = array_reverse($BD4A);
                 
                        for ($i = 0; $i < sizeof($BD4AR); $i++) {
            
                            $key_   = $BD4AR[$i][0];
                            $val_   = $BD4AR[$i][1];
                            $regex  = $indexSymp4.''.$key_;
                      
                           
                            for ($x = 0; $x < $index4; $x ++) {
                                $txtQ = $zAR[$x];
                               
                                if($txtQ !== ""){
                                  
                                    $zAR[$x] = str_replace($regex, $val_, $txtQ);
                                }
                            }
                        }
                    }

        // five

                $index5 = sizeof($zAR) - 3;
                $BD5 = [];
                $indexSymp5 = $keys_[4];
                $BD5TX = $zAR[$index5];

                if($BD5TX !== ''){

                    for ($i = 0; $i < strlen($BD5TX); $i += 5) {

                        $BD5TX_ = substr($BD5TX, $i, 5);
                        array_push($BD5,$BD5TX_);

                    }

                    $BD5A = [];

                    for ($i = 0; $i < sizeof($BD5); $i++) {
                        $key_   = $i + 1;
                        $val_   = $BD5[$i];
                        $upAr   = [$key_,$val_];
                        array_push($BD5A,$upAr);
                    
                    }

                    $BD5AR = array_reverse($BD5A);

                    for ($i = 0; $i < sizeof($BD5AR); $i++) {

                        $key_   = $BD5AR[$i][0];
                        $val_   = $BD5AR[$i][1];
                        $regex  = $indexSymp5.''.$key_;


                        for ($x = 0; $x < $index5; $x ++) {
                            $txtQ = $zAR[$x];
                            if($txtQ !== ""){
                                $zAR[$x] = str_replace($regex, $val_, $txtQ);
                            }
                        }

                    }
                }

        // six

                    $index6 = sizeof($zAR) - 4;
                    $BD6 = [];
                    $indexSymp6 = $keys_[3];
                    $BD6TX = $zAR[$index6];

                    if($BD6TX !== ''){

                        for ($i = 0; $i < strlen($BD6TX); $i += 6) {

                            $BD6TX_ = substr($BD6TX, $i, 6);
                            array_push($BD6,$BD6TX_);

                        }

                        $BD6A = [];

                        for ($i = 0; $i < sizeof($BD6); $i++) {
                            $key_   = $i + 1;
                            $val_   = $BD6[$i];
                            $upAr   = [$key_,$val_];
                            array_push($BD6A,$upAr);
                        
                        }

                        $BD6AR = array_reverse($BD6A);

                        for ($i = 0; $i < sizeof($BD6AR); $i++) {

                            $key_   = $BD6AR[$i][0];
                            $val_   = $BD6AR[$i][1];
                            $regex  = $indexSymp6.''.$key_;


                            for ($x = 0; $x < $index6; $x ++) {
                                $txtQ = $zAR[$x];
                                if($txtQ !== ""){
                                    $zAR[$x] = str_replace($regex, $val_, $txtQ);
                                }
                            }

                        }
                    }

        // seven

                    $index7 = sizeof($zAR) - 5;
                    $BD7 = [];
                    $indexSymp7 = $keys_[2];
                    $BD7TX = $zAR[$index7];

                    if($BD7TX !== ''){

                        for ($i = 0; $i < strlen($BD7TX); $i += 7) {

                            $BD7TX_ = substr($BD7TX, $i, 7);
                            array_push($BD7,$BD7TX_);

                        }

                        $BD7A = [];

                        for ($i = 0; $i < sizeof($BD7); $i++) {
                            $key_   = $i + 1;
                            $val_   = $BD7[$i];
                            $upAr   = [$key_,$val_];
                            array_push($BD7A,$upAr);
                        
                        }

                        $BD7AR = array_reverse($BD7A);

                        for ($i = 0; $i < sizeof($BD7AR); $i++) {

                            $key_   = $BD7AR[$i][0];
                            $val_   = $BD7AR[$i][1];
                            $regex  = $indexSymp7.''.$key_;


                            for ($x = 0; $x < $index7; $x ++) {
                                $txtQ = $zAR[$x];
                                if($txtQ !== ""){
                                    $zAR[$x] = str_replace($regex, $val_, $txtQ);
                                }
                            }

                        }
                    }
            
        // eghit 
                    $index8 = sizeof($zAR) - 6;
                    $BD8 = [];
                    $indexSymp8 = $keys_[1];
                    $BD8TX = $zAR[$index8];

                    if($BD8TX !== ''){

                        for ($i = 0; $i < strlen($BD8TX); $i += 8) {

                            $BD8TX_ = substr($BD8TX, $i, 8);
                            array_push($BD8,$BD8TX_);

                        }

                        $BD8A = [];

                        for ($i = 0; $i < sizeof($BD8); $i++) {
                            $key_   = $i + 1;
                            $val_   = $BD8[$i];
                            $upAr   = [$key_,$val_];
                            array_push($BD8A,$upAr);
                        
                        }

                        $BD8AR = array_reverse($BD8A);

                        for ($i = 0; $i < sizeof($BD8AR); $i++) {

                            $key_   = $BD8AR[$i][0];
                            $val_   = $BD8AR[$i][1];
                            $regex  = $indexSymp8.''.$key_;


                            for ($x = 0; $x < $index8; $x ++) {
                                $txtQ = $zAR[$x];
                                if($txtQ !== ""){
                                    $zAR[$x] = str_replace($regex, $val_, $txtQ);
                                }
                            }

                        }
                    }
        // nine
                    $index9 = sizeof($zAR) - 7;
                    $BD9    = [];
                    $indexSymp9 = $keys_[0];
                    $BD9TX      = $zAR[$index9];

                    if($BD9TX !== ''){

                        for ($i = 0; $i < strlen($BD9TX); $i += 9) {

                            $BD9TX_ = substr($BD9TX, $i, 9);
                            array_push($BD9,$BD9TX_);

                        }

                        $BD9A = [];

                        for ($i = 0; $i < sizeof($BD9); $i++) {
                            $key_   = $i + 1;
                            $val_   = $BD9[$i];
                            $upAr   = [$key_,$val_];
                            array_push($BD9A,$upAr);
                        
                        }

                        $BD9AR = array_reverse($BD9A);

                        for ($i = 0; $i < sizeof($BD9AR); $i++) {

                            $key_   = $BD9AR[$i][0];
                            $val_   = $BD9AR[$i][1];
                            $regex  = $indexSymp9.''.$key_;
                            

                            for ($x = 0; $x < $index9; $x ++) {
                                $txtQ = $zAR[$x];

                                if($txtQ !== ""){
                                    $zAR[$x] = str_replace($regex, $val_, $txtQ);
                                }
                            }

                        }
                    }
                    $lasTDe = $zAR[0];
                   
            return base64_decode($lasTDe);
    }
            
 
         
}
        
    


    