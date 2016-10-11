<?php
header('Content-type: application/json');
	require_once('../setting.php');
	setting();

 	require_once('Controller/ControllerBase.php');
 	if(!ControllerBase::checkSession())exit();
 	$base = new ControllerBase();
 	$user_id = $base->user_id();

	require_once('request.php');

	$search_text = urldecode($base->request()->get_json()->search_text);
	$is_monthly_new = $base->request()->get_json()->is_monthly_new;
	$info = array();
	if($is_monthly_new){
		$info = getRakutenNewlyPublication();
	}else{
		$search_text = $search_text ? $search_text : "";
		error_log($search_text);
		$info = getRakutenItemByItemName($search_text);
	}
	echo json_encode($info);

?>