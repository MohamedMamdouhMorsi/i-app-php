<?php 

    class api {
        function __construct($i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile)
        {

            header('Content-Type: application/json');
            // 

            if(isset($_POST["msg"])){

                
                $msg       = base64_decode($_POST["msg"]);
             
                $msgJson   = json_decode($msg,true);
               
                if(isset($msgJson["order"])){
              
                    // do order
                        $order  = $msgJson["order"];
                     
                        new orders($order,$msgJson, $i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile);
                    
                }else  if(isset($msgJson["query"])){

                    // do query

                    
                   
                    new query( $dbConnection , $msgJson,$dir,$i_app);
                
            }else{
                echo '{ "message": "Invalid request method" , "error":'.$msg.' }';
                
                exit();
            }
            }else{

                echo '{ "message": "Invalid request method" }';
                
                exit();

            }

        }
        function unicode_replace_with_pattern($unicode, $value, $text, $replacement) {
            // Construct the regular expression pattern
            $pattern = '/' . preg_quote($unicode, '/') . $value . '/u';
        
            // Perform the replacement using preg_replace
            $newText = preg_replace($pattern, $replacement, $text);
     
            // Return the modified text
            return $newText;
        }
        
        function unicode_replace($unicode, $text, $replacement) {
            // Construct the regular expression pattern
            $pattern = '/' . preg_quote($unicode, '/') . '/u';
        
            // Perform the replacement using preg_replace
            $newText = preg_replace($pattern, $replacement, $text);
        
            // Return the modified text
            return $newText;
        }
        function decompress($compressedText) {
            $decompressedText = '';
            for ($i = 0; $i < strlen($compressedText); $i++) {
                $charCode = ord($compressedText[$i]);
                $decompressedText .= chr($charCode - 1000); // Reverse the offset
            }
            return $decompressedText;
        }
    function zipS($txt) {

        $symbol  = array("►","◄","▲","▼","я","з","л","ь","д","Ф","и","й","ч","ш","ж","я","Д","Э","Ц","щ","г","п","б","ъ","Ю","ä","ß","ü","Ü","ö","ñ","è","ê","É","à");
        $keys_    = array("☺","☻","♥","♦","♣","♠","ф");
      
            $zAR = [];
            $zAR = explode(",", $txt);
            $zAR_ = [];
            for ($i = 0; $i < mb_strlen($txt, 'UTF-8'); $i++) {
                $zAR_[] = mb_substr($txt, $i, 1, 'UTF-8');
            }
          echo sizeof($zAR_);
            if (sizeof($zAR) < 8) {
                return $txt;
            }
                     
        /// two
       
        $index2 = sizeof($zAR) - 1;
        $BD2    = [];
        $BD2TX  = $zAR[$index2];
        
        for ($i = 0; $i < mb_strlen($BD2TX, 'UTF-8'); $i += 2) {
            $num = $i + 1;
            $dif = mb_strlen($BD2TX, 'UTF-8') - $num;
            if ($dif >= 2) {
                $BD2TX_ = mb_substr($BD2TX, $i, 2, 'UTF-8');
                array_push($BD2, $BD2TX_);
            }
        }
        
        for ($i = 0; $i < sizeof($BD2); $i++) {
            if (isset($symbol[$i])) {
                $key_ = $symbol[$i];
                $val_ = $BD2[$i];
                
                $txt = $this->unicode_replace($key_, $txt, $val_);
            }
        }
       
            $zAR = explode(",", $txt);
          
        // four
        
        $index4 = sizeof($zAR) - 2;
        $BD4    = [];
        $indexSymp4 = $keys_[5];
        $BD4TX = $zAR[$index4];
        
        if ($BD4TX !== '') {
        
            // Split $BD4TX into 4-character segments
            for ($i = 0; $i < mb_strlen($BD4TX, 'UTF-8'); $i += 4) {
                $BD4TX_ = mb_substr($BD4TX, $i, 4, 'UTF-8');
                array_push($BD4, $BD4TX_);
            }
        
            // Construct an array with keys and values
            $BD4A = [];
            for ($i = 0; $i < sizeof($BD4); $i++) {
                $key_   = $i + 1;
                $val_   = $BD4[$i];
                $upAr   = [$key_, $val_];
                array_push($BD4A, $upAr);
            }
        
            // Reverse the array
            $BD4AR = array_reverse($BD4A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD4AR); $i++) {
                $key_   = $BD4AR[$i][0];
                $val_   = $BD4AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp4, $key_, $txt, $val_);
            }
        }

        // five
        $zAR = explode(",", $txt);
        $index5 = sizeof($zAR) - 3;
        $BD5 = [];
        $indexSymp5 = $keys_[4];
        $BD5TX = $zAR[$index5];
        
        if ($BD5TX !== '') {
        
            // Split $BD5TX into 5-character segments
            for ($i = 0; $i < mb_strlen($BD5TX, 'UTF-8'); $i += 5) {
                $BD5TX_ = mb_substr($BD5TX, $i, 5, 'UTF-8');
                array_push($BD5, $BD5TX_);
            }
        
            // Construct an array with keys and values
            $BD5A = [];
            for ($i = 0; $i < sizeof($BD5); $i++) {
                $key_   = $i + 1;
                $val_   = $BD5[$i];
                $upAr   = [$key_, $val_];
                array_push($BD5A, $upAr);
            }
        
            // Reverse the array
            $BD5AR = array_reverse($BD5A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD5AR); $i++) {
                $key_   = $BD5AR[$i][0];
                $val_   = $BD5AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp5, $key_, $txt, $val_);
            }
        }

        // six
        $zAR = explode(",", $txt);
        $index6 = sizeof($zAR) - 4;
        $BD6 = [];
        $indexSymp6 = $keys_[3];
        $BD6TX = $zAR[$index6];
        
        if ($BD6TX !== '') {
        
            // Split $BD6TX into 6-character segments
            for ($i = 0; $i < mb_strlen($BD6TX, 'UTF-8'); $i += 6) {
                $BD6TX_ = mb_substr($BD6TX, $i, 6, 'UTF-8');
                array_push($BD6, $BD6TX_);
            }
        
            // Construct an array with keys and values
            $BD6A = [];
            for ($i = 0; $i < sizeof($BD6); $i++) {
                $key_   = $i + 1;
                $val_   = $BD6[$i];
                $upAr   = [$key_, $val_];
                array_push($BD6A, $upAr);
            }
        
            // Reverse the array
            $BD6AR = array_reverse($BD6A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD6AR); $i++) {
                $key_   = $BD6AR[$i][0];
                $val_   = $BD6AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp6, $key_, $txt, $val_);
            }
        }

        // seven
        $zAR = explode(",", $txt);
        $index7 = sizeof($zAR) - 5;
        $BD7 = [];
        $indexSymp7 = $keys_[2];
        $BD7TX = $zAR[$index7];
        
        if ($BD7TX !== '') {
        
            // Split $BD7TX into 7-character segments
            for ($i = 0; $i < mb_strlen($BD7TX, 'UTF-8'); $i += 7) {
                $BD7TX_ = mb_substr($BD7TX, $i, 7, 'UTF-8');
                array_push($BD7, $BD7TX_);
            }
        
            // Construct an array with keys and values
            $BD7A = [];
            for ($i = 0; $i < sizeof($BD7); $i++) {
                $key_   = $i + 1;
                $val_   = $BD7[$i];
                $upAr   = [$key_, $val_];
                array_push($BD7A, $upAr);
            }
        
            // Reverse the array
            $BD7AR = array_reverse($BD7A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD7AR); $i++) {
                $key_   = $BD7AR[$i][0];
                $val_   = $BD7AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp7, $key_, $txt, $val_);
            }
        }
            
        // eghit 
        $zAR = explode(",", $txt);
        $index8 = sizeof($zAR) - 6;
        $BD8 = [];
        $indexSymp8 = $keys_[1];
        $BD8TX = $zAR[$index8];
        
        if ($BD8TX !== '') {
        
            // Split $BD8TX into 8-character segments
            for ($i = 0; $i < mb_strlen($BD8TX, 'UTF-8'); $i += 8) {
                $BD8TX_ = mb_substr($BD8TX, $i, 8, 'UTF-8');
                array_push($BD8, $BD8TX_);
            }
        
            // Construct an array with keys and values
            $BD8A = [];
            for ($i = 0; $i < sizeof($BD8); $i++) {
                $key_   = $i + 1;
                $val_   = $BD8[$i];
                $upAr   = [$key_, $val_];
                array_push($BD8A, $upAr);
            }
        
            // Reverse the array
            $BD8AR = array_reverse($BD8A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD8AR); $i++) {
                $key_   = $BD8AR[$i][0];
                $val_   = $BD8AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp8, $key_, $txt, $val_);
            }
        }
        // nine
        $zAR = explode(",", $txt);
        $index9 = sizeof($zAR) - 7;
        $BD9 = [];
        $indexSymp9 = $keys_[0];
        $BD9TX = $zAR[$index9];
        
        if ($BD9TX !== '') {
        
            // Split $BD9TX into 9-character segments
            for ($i = 0; $i < mb_strlen($BD9TX, 'UTF-8'); $i += 9) {
                $BD9TX_ = mb_substr($BD9TX, $i, 9, 'UTF-8');
                array_push($BD9, $BD9TX_);
            }
        
            // Construct an array with keys and values
            $BD9A = [];
            for ($i = 0; $i < sizeof($BD9); $i++) {
                $key_   = $i + 1;
                $val_   = $BD9[$i];
                $upAr   = [$key_, $val_];
                array_push($BD9A, $upAr);
            }
        
            // Reverse the array
            $BD9AR = array_reverse($BD9A);
        
            // Perform replacements using the pattern
            for ($i = 0; $i < sizeof($BD9AR); $i++) {
                $key_   = $BD9AR[$i][0];
                $val_   = $BD9AR[$i][1];
                $txt = $this->unicode_replace_with_pattern($indexSymp9, $key_, $txt, $val_);
            }
        }
        $zAR = explode(",", $txt);
        $const = $zAR[0];
              
                    
                   $lastData = base64_decode($const);
            return $lastData;
    }
            
 
         
}
        
    


    