<?php 

    class api {
        function __construct($i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile)
        {
            if(isset($_POST["order"])){
                // do order
                $order = $_POST["order"];
               new orders($order, $i_app,$dir,$i_app_st,$dbConnection,$userData,$loadeAppFile);
               
            }else if(isset($_POST["query"])){
                // do query
                $query = $_POST["query"];
                new query($query , $i_app,$dir,$i_app_st,$dbConnection,$userData);
            }else{
                echo "{ message: 'Invalid request method' }";
                exit();
            }
        }
    }

    