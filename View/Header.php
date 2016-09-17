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
<link rel=stylesheet href="<?= $v->app_pos?>/StyleSheet/style1.css" type="text/css">
<title>
ComicMyAdmin
</title>
<!-- <script src="<?= $v->app_pos?>/Plugins/jquery-3.1.0.min.js"> !-->
<script src="<?= $v->app_pos?>/Plugins/jq.js">

</script>

<link href="<?= $v->app_pos?>/Plugins/jquery.bxslider.css" rel="stylesheet" />
<script src="<?= $v->app_pos?>/Plugins/jquery.bxslider.min.js"></script>

<link href="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.css" rel="stylesheet" />
<script src="<?= $v->app_pos?>/Plugins/jqueryUI/jquery-ui.min.js"></script>

<script src="<?=$v->app_pos?>/Scripts/fly-anim.js"></script>

<script src="<?=$v->app_pos?>/Scripts/util.js"></script>

</head>
<?php echo("response code = " . http_response_code()); ?>
<body class="back-img">
<div class="app-container">
	<div class="top-container" id="fly-anim-area">
		<a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/ComicAdmin/')"><div class="top-title"><h1>ComicMyAdmin</h1></div></a>
	</div>
	