<?php 

class GO_SSL{
    function __construct()
    {
        $host = $_SERVER['HTTP_HOST'];
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
            
        // Construct the redirect URL without 'www'
        $redirect_url = "https://" . $host . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header("Location: $redirect_url");
        exit();
        }
        

        if (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
            $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header("Location: $redirect_url");
            exit();
        }
    }
}