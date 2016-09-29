<!DOCTYPE html> 
<html>
<!--<html lang="ja" manifest="<?=$v->app_pos?>/no-cache.appcache"> -->
<head>
<?php
//header("HTTP/1.1 200");
?>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<link rel="icon" href="<?= $v->app_pos?>/Images/icon.jpg">


<script src="<?=$v->app_pos?>/Scripts/util.js"></script>

<script type="text/javascript">
if(_ua.Mobile){

	document.write( '<link rel="stylesheet"'+
       	'href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />' + 
		'<script src="http://code.jquery.com/jquery-1.10.2.min.js"><\/script>' +
		'<script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"><\/script>');
} else {
	document.write( '<link rel=stylesheet href="<?= $v->app_pos?>/StyleSheet/style1.css" type="text/css">' + 
		'<script src="<?= $v->app_pos?>/Plugins/jq.js"><\/script>' +
		'<link href="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.css" rel="stylesheet" />'+
		'<script src="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.js"><\/script>');

}

</script>

<link href="<?= $v->app_pos?>/Plugins/jquery.bxslider.css" rel="stylesheet" />
<script src="<?= $v->app_pos?>/Plugins/jquery.bxslider.min.js"></script>

<script src="<?=$v->app_pos?>/Scripts/fly-anim.js"></script>

<title>
ComicMyAdmin
</title>

</head>
<?php echo("response code = " . http_response_code()); ?>
<?php echo ("<br> session = " ); var_dump($_SESSION); 
?>
<body class="back-img">
<div class="app-container">
	<div class="top-container" id="fly-anim-area">
		<a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/ComicAdmin/')"><div class="top-title"><h1>ComicMyAdmin</h1></div></a>
	</div>
	