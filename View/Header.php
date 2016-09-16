<html>
<head>
<?php
//header("HTTP/1.1 200");
?>
<meta charset="utf-8">
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

<script src="<?=$v->app_pos?>/Plugins/fly-anim.js"></script>

</head>
<?php var_dump($_SESSION); ?>
<body class="back-img">
<div class="app-container">
<div class="top-container" id="fly-anim-area">
	<a href="<?= $v->app_pos?>/ComicAdmin/"><div class="top-title"><h1>ComicMyAdmin</h1></div></a>
	
	<div class="top-bar">
		<ul>
			<li class="top-icon"><a href="<?= $v->app_pos?>/ComicAdmin/?">Top</a></li>
			<li class=""><a href="<?= $v->app_pos?>/User/?">Mypage</a></li>
			<li><a href="<?= $v->app_pos?>/ComicAdmin/SeriesMyList/?">MyList</a></li>
			<li><a href="<?= $v->app_pos?>/ComicAdmin/SeriesAllList/?">AddTitle</a></li>
			<?php if($v->authority > 1): ?>
			<li><a href="<?=$v->app_pos?>/ComicAdmin/AddComicSeries/?">AddSeries</a></li>
			<?php endif; ?>
		
			
			<li><a href="<?= $v->app_pos?>/Login/Logout">Logout</a></li>
		</ul>
	</div>
</div>
