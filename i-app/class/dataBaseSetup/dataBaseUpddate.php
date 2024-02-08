<?php 
class dataBaseUpddate{
  
function __construct($i_app)
{
    $i_app_db_path = appDir()['i_app_db_path'];

    // Check if users system
    if (isset($i_app['users'])) {
        // Dummy callback function
        $callBack = function ($res) {
        };

        if (!file_exists($i_app_db_path)) {
            echo "Please add db.app to your project main directory with your Database configuration or delete or comment users key from i-app" . $i_app_db_path;
            return false;
        } else {
            $dbSt = file_get_contents($i_app_db_path);
            $cleanDataSt = new iAppReader($dbSt);
            $jsonData = json_decode($cleanDataSt,true);

            if ($jsonData && isset($jsonData['mysql'])) {
                $dbConfigFn->set($jsonData['mysql'][0]);
            } else {
                CL_(["error db file"]);
            }

            if ($jsonData['mysql'] && count($jsonData['mysql']) > 0) {
                $basicDataBase = $jsonData['mysql'][0];
                $basicTables = isset($basicDataBase['tables']) ? $basicDataBase['tables'] : false;
                $basicDB = ['users', 'usersSessions', 'usersPasswords', 'usersApps', 'usersType', 'typesApps', 'permissions', 'appsPermissions', 'usersPermissions'];
                $insertDB = [
                    'usersApps' => ['users', 'Basic Users App'],
                    'usersType' => ['admin', 'Basic Admin User'],
                    'typesApps' => [1, 1],
                    'permissions' => [
                        ['create', 'Ability to create new orders'],
                        ['update', 'Ability to update existing orders'],
                        ['delete', 'Ability to delete orders'],
                        ['view', 'Ability to view order details'],
                        ['approve', 'Ability to approve orders']
                    ],
                    'appsPermissions' => [[1, 1], [1, 2], [1, 3], [1, 4], [1, 5]],
                    'usersPermissions' => [[1, 1, 1], [1, 1, 2], [1, 1, 3], [1, 1, 4], [1, 1, 5]]
                ];
                $needDB = [];
                $editDB = [];

                if ($basicTables) {
                    foreach ($basicDB as $testDB) {
                        $isExist = isset($basicTables[$testDB]);
                        if (!$isExist) {
                            $needDB[] = $testDB;
                        }
                    }
                } else {
                    $needDB = $basicDB;
                }

                $insertBasic = function () use ($editDB, $insertDB, $db, $callBack) {
                    foreach ($editDB as $dbN) {
                        if (isset($insertDB[$dbN])) {
                            echo "insertDB: " . $dbN;
                            $db(['query' => [['a' => 'in', 'n' => $dbN, 'd' => $insertDB[$dbN]]]], $dbN, $callBack);
                        }
                    }
                };

                $updateDBFILE = function () use ($editDB, $jsonData, $i_app_db_path) {
                    if (count($editDB) > 0) {
                        $basicDBPath = dirname(__FILE__) . '/basicDB/basicDB.json';
                        $basicDBdata = file_get_contents($basicDBPath);
                        $basicDBdataJD = JD_($basicDBdata);

                        foreach ($editDB as $createdDBName) {
                            if (!isset($jsonData['mysql'][0]['tables'])) {
                                $jsonData['mysql'][0]['tables'] = [];
                            }
                            $jsonData['mysql'][0]['tables'][$createdDBName] = $basicDBdataJD[$createdDBName];
                        }

                        $db_app = JDS_($jsonData);
                        $db_app_file = iAppFileMaker($db_app);
                        file_put_contents($i_app_db_path, $db_app_file);

                        echo "db.app updated";
                        $insertBasic();
                    }
                };

                if (count($needDB) > 0) {
                    echo "needDB: " . json_encode($needDB);
                    $lastDB = end($needDB);

                    foreach ($needDB as $dbName) {
                        $result = $db(['query' => [['a' => 'check', 'n' => $dbName]]], false, $callBack);
                        if (count($result) > 0) {
                            $editDB[] = $dbName;
                            if (count($needDB) == count($editDB)) {
                                echo "all needed DB table Created";
                                $updateDBFILE();
                            }
                        } else {
                            $sqlFileName = $dbName . ".sql";
                            $sqlFilePath = dirname(__FILE__) . "/basicDB/" . $sqlFileName;
                            $sqlData = file_get_contents($sqlFilePath);

                            if ($sqlData !== false) {

                                $creatResult = $db(['query' => [['a' => 'create', 'd' => $sqlData]]], false, $callBack);
                                $editDB[] = $dbName;

                                echo "DB: " . $dbName . " created";

                                if (count($needDB) == count($editDB)) {
                                    echo "all needed DB table Created";
                                    $updateDBFILE();
                                }

                            } else {
                                echo "sql file is missing " . $dbName;
                            }
                        }
                    }
                }
            } else {
                echo "Please add db.app to your project main directory with your Database configuration or delete or comment users key from i-app";
            }
        }
    }
}


}