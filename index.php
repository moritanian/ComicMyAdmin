<?php

require_once( 'Model/ModelBase.php ');

require_once 'Dispatcher.php';

require_once 'util.php';

// DB接続情報設定
$connInfo = array(
    'host'     => 'localhost',
    'dbname'   => 'data1',
    'dbuser'   => 'root',
    'password' => 'sincostan'
);
ModelBase::setConnectionInfo($connInfo );

$dispatcher = new Dispatcher();



IncludePathSetting($dispatcher);



//apacheのドキュメントルートから何階層目のディレクトリにMVCアプリを配備するか。

//ルートを0とすると、eigyou/ 配下は1階層目に当たるので、1とする。

$dispatcher->setPramLevel(1);

$dispatcher->dispatch();

?>
