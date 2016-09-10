<?php
 
 //error_log("api user_volume_data\edit");
 //$path = "C:\\Users\\moritania\\Desktop\\wavserver\\Apache24\\htdocs\\ComicMyAdmin";
 //set_include_path(get_include_path() . PATH_SEPARATOR . $path); 
	require_once('../setting.php');
	setting();

 	require_once('Controller/ControllerBase.php');
 	if(!ControllerBase::checkSession())exit();
 	$base = new ControllerBase();
 	$user_id = $base->user_id();

	require_once('request.php');
	require_once('Model/UserComicVolumeData.php');
	$book_id =  filter_var($base->request()->get_json()->book_id, FILTER_VALIDATE_INT);
	$series_id =  filter_var($base->request()->get_json()->series_id, FILTER_VALIDATE_INT);
	$is_possess = filter_var($base->request()->get_json()->is_possess, FILTER_VALIDATE_INT);
	$is_read = filter_var($base->request()->get_json()->is_read, FILTER_VALIDATE_INT);
	$assessment = filter_var($base->request()->get_json()->assessment, FILTER_VALIDATE_INT);
	$user_comment = $base->request()->get_json()->user_comment;

	$user_volume_model = new UserComicVolumeData();
	$data = array(
		'user_id' => $user_id,
		'book_id' => $book_id,
		'series_id' => $series_id,
		'is_possess' => $is_possess,
		'is_read'	=> $is_read,
		'assessment' => $assessment,
		'user_comment' => $user_comment
		);
	$user_volume_model->updateInsertData($data);

?>