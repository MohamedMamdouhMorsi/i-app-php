<?php 
 
class dataBaseUpddate{
  
    function __construct($db ,$dir, $i_app)
    {
        $i_app_dir      = new appDir($dir , $i_app);
        $copObjectSt    = json_encode($i_app_dir);

        $i_app_dir      = json_decode($copObjectSt,true);
        $i_app_db_path  = $i_app_dir["treeDir"]["i_app_db_path"];

        // Check if users system 

        if (isset($i_app['users'])) {

            // Dummy callback function

            $callBack = function ($res) {

            };

            if (!file_exists($i_app_db_path)) {
                echo "Please add db.app to your project main directory with your Database configuration or delete or comment users key from i-app" . $i_app_db_path;
                return false;

            } else {

                $dbSt        = file_get_contents($i_app_db_path);
                $cleanDataSt = new iAppReader($dbSt);
            
                $jsonData    = json_decode($cleanDataSt,true);


                if ($jsonData['mysql'] && count($jsonData['mysql']) > 0) {
                    $basicDataBase = $jsonData['mysql'][0];
                    $basicTables = isset($basicDataBase['tables']) ? $basicDataBase['tables'] : false;
                
                    $basicDB     = ["users", "answers","usersSessions", "usersPasswords", "usersApps", "usersType",  "permissions", "appsPermissions"];
                    $insertDB    = [
                        "usersApps" => ["users", "Basic Users App"],
                        "usersType" => ["admin", "Basic Admin User"],
                        "typesApps" => [1, 1],
                        "permissions" => [

                            ["create", "Ability to create new orders"],
                            ["update", "Ability to update existing orders"],
                            ["delete", "Ability to delete orders"],
                            ["view",   "Ability to view order details"],
                            ["approve","Ability to approve orders"]
                            
                        ],
                        "appsPermissions" => [[1, 1, 1], [1, 1, 2], [1, 1, 3], [1, 1, 4], [1, 1, 5]]
                    ];

                    $needDB = [];
                    $editDB = [];
                    $isNew = false;
                    if ($basicTables) {

                        foreach ($basicDB as $testDB) {

                            $isExist = isset($basicTables[$testDB]);

                            if (!$isExist) {
                                $needDB[] = $testDB;
                            }

                        }

                    } else {
                        $basicDBPath = dirname(__FILE__) . '/basicDB/basicDB.json';
                        $basicDBdata = file_get_contents($basicDBPath);
                        $basicDBdataJD = json_decode($basicDBdata,true);
                        $db->setIappTables($basicDBdataJD);
                        $needDB = $basicDB;
                        $isNew = true;
                    }

                    

                

                    if (count($needDB) > 0) {
                        echo "needDB: " . json_encode($needDB);
                        $editDB = [];
                        foreach ($needDB as $dbName) {
                       
                            $sqlFileName = $dbName . ".sql";
                            $sqlFilePath = dirname(__FILE__) . "/basicDB/" . $sqlFileName;

                            $sqlData = file_get_contents($sqlFilePath);
                          
                            if ($sqlData !== false) {

                                $creatResult = $db->query(['query' => [['a' => 'create', 'd' => $sqlData]]]);
                               
                                if($creatResult){
                                    echo "DB: " . $dbName . " created";
                                    array_push($editDB,$dbName);
                                }
                     
                              

                            } else {
                                echo "sql file is missing " . $dbName;
                            }
                        }
                        
                        $this->updateDBFILE($editDB, $jsonData, $i_app_db_path,$insertDB, $db);
                    }
                } else {
                    echo "Please add db.app to your project main directory with your Database configuration or delete or comment users key from i-app";
                }
            }
        }
    }

    function insertBasic($editDB, $insertDB, $db) {
        foreach ($editDB as $dbN) {
            if (isset($insertDB[$dbN])) {
                echo "insertDB: " . $dbN;
                $db->query(['query' => [['a' => 'in', 'n' => $dbN, 'd' => $insertDB[$dbN]]]]);
            }
        }
    }

    function updateDBFILE($editDB, $jsonData, $i_app_db_path,$insertDB, $db) {
        if (count($editDB) > 0) {
            $basicDBPath = dirname(__FILE__) . '/basicDB/basicDB.json';
            $basicDBdata = file_get_contents($basicDBPath);
            $basicDBdataJD = json_decode($basicDBdata,true);

            foreach ($editDB as $createdDBName) {
                if (!isset($jsonData['mysql'][0]['tables'])) {
                    $jsonData['mysql'][0]['tables'] = [];
                }
                $jsonData['mysql'][0]['tables'][$createdDBName] = $basicDBdataJD[$createdDBName];
            }

            $db_app = json_encode($jsonData);
            $db_app_file = new AppFileMaker($db_app);
            file_put_contents($i_app_db_path, $db_app_file);

            echo "db.app updated";
            $this->insertBasic($editDB, $insertDB, $db);
        }
    }

}