<?php

/**

 * PHPのinclude_pathを設定。

 * 本番環境、テスト環境を切り分ける。

 */

function IncludePathSetting($dispatcher){

    if ($_SERVER['SERVER_NAME']=='moritanian.s2.xrea.com' ){

        //本番環境

        $path = '/home/hoge/smarty/libs/';

        $path .= PATH_SEPARATOR . '/home/hoge/別にインクルードするディレクトリあれば指定/';

        $dispatcher->setSystemRoot('/home/hoge/html/eigyou');

    } else {

        //開発環境

        $path = '/';

       // $path .= PATH_SEPARATOR . 'D:/devlop/php/別にインクルードするディレクトリあれば指定/';

        //$dispatcher->setSystemRoot('C:/Users/moritania/Destop/wavserver/Apache24/ComicMyAdmin');
        $dispatcher->setSystemRoot($_SERVER['DOCUMENT_ROOT'].'/ComicMyAdmin');
    }

    set_include_path(get_include_path() . PATH_SEPARATOR . $path); 

}

?>
