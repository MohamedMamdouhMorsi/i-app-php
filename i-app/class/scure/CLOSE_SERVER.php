<?php 

class CLOSE_SERVER{
    function __construct()
    {
        header('HTTP/1.1 503 Service Unavailable');
        header('Retry-After: 3600'); // Instructs to retry after 1 hour
        exit('Service is temporarily unavailable. Please try again later.');
    }
}