<?php
function setting(){
	
	require_once 'Dispatcher.php';

	require_once 'util.php';

	require_once 'Config/database.php';
	
	$dispatcher = new Dispatcher();

	IncludePathSetting($dispatcher);

	require_once 'Model/ModelBase.php ';

	

	// DB接続
	$database_config = new DATABASE_CONFIG();

	ModelBase::setConnectionInfo($database_config->connInfo );

	//apacheのドキュメントルートから何階層目のディレクトリにMVCアプリを配備するか。

	//ルートを0とすると、eigyou/ 配下は1階層目に当たるので、1とする。

	$dispatcher->setPramLevel(1);

	return $dispatcher;
}
?>