<?php
use MaxMind\Db\Reader;

class I_APP_GEOIP
{
    function __construct($msg )
    {
        $ipB  = new sessionInfo();
        $ip   = $ipB->get_ip();
			
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