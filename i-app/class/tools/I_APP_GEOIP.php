<?php
use MaxMind\Db\Reader;

class I_APP_GEOIP extends I_APP_IP_INFO
{
    function __construct($msg )
    {
        $ip  = I_APP_IP_INFO::get_ip();
			
		if($ip  == "FALSE"){
			echo '{"res":false}';
            exit();
		}else{
            $dirBasic = realpath(__DIR__ . '/../..');
            $dir      = $dirBasic."/asset/db/GeoLite2-City.mmdb";
            $ipData = new Reader(  $dir );
        
            $ipData_ = $ipData->get( $ip );
            echo json_encode($ipData_);
            exit();
        }
  

    }
}