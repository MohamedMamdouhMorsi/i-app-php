<?php
class dataBaseReport{

    function __construct($db)
    {

            $insertUser = $db->query([
                "query"=>[[
                    "a"=>"check",
                    "n"=>"users" ]]
            ]);

            
            if($insertUser){
                echo '{"res":"true"}';
            }else{
                echo '{"res":"false"}';
            }
            
            exit();
        


    }
}