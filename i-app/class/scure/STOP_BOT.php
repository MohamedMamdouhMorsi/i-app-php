<?php 


class STOP_BOT {
    function __construct()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $blacklist = ['Selenium', 'PhantomJS', 'HeadlessChrome', 'Python'];
       
        foreach ($blacklist as $bot) {
            if (strpos($userAgent, $bot) !== false) {
                // Block access or redirect to an error page
                header('HTTP/1.0 403 Forbidden');
                exit('You are not allowed to access this page.');
            }
        }
    }
}