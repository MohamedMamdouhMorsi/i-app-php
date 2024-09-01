<?php 

class orders {

   function __construct($order,$msgData, $i_app, $dir, $i_app_st, $dbConnection,$userData,$i_app_path) {
    
        if ($order === 'countries') {
            return new countries();
        } elseif ($order === 'languages') {
            return new languages();
        } elseif   ($order === 'currency') {
            return new currency();
        } elseif ($order === 'icons') {
            return new icons();
        } elseif ($order === 'serverOffer') {
            return new serverOffer($dbConnection,$userData,$msgData  );
        } elseif ($order === 'serverAnswer') {
            return new serverAnswer($dbConnection,$userData,$msgData);
        } elseif ($order === 'getAnswer') {
            return new getAnswer($dbConnection,$userData );
        } elseif ($order === 'addUser') {
            return new addUser($dbConnection,$msgData);
        } elseif ($order === 'checkUser') {
            return new checkUser($dbConnection,$msgData);
        } elseif ($order === 'logUser') {
            return new logUser($dbConnection,$msgData );
        } elseif ($order === 'setUserOffline') {
            return new setUserOffline($dbConnection,$userData );
        } elseif ($order === 'speechSynthesisData') {
         //   return new speechSynthesisData($body );
        } elseif ($order === 'dataBaseReport') {
            return new dataBaseReport($dbConnection );
        }elseif ($order === 'updateApp') {
            return new updateAppData( $i_app_path);
        } elseif ($order === 'updateTranslate') {
            return new updateTranslate(  $dir, $i_app);
        } elseif ($order === 'updateTxt') {
            return new updateTxt($dir, $i_app);
        } elseif ($order === 'uploadIcons') {
            return new updateIcons( $i_app_path, $i_app);
        } else {
            echo json_encode(['res' => false]);
            exit();
        }
        
    }

}
