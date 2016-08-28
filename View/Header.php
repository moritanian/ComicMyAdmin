<html>
<head>
<?php
header("HTTP/1.1 200");
?>
<meta charset="utf-8">
<link rel="icon" href="<?= $v->app_pos?>/Images/icon.jpg">
<link rel=stylesheet href="<?= $v->app_pos?>/StyleSheet/style1.css" type="text/css">
<title>
ComicMyAdmin
</title>
<script src="<?= $v->app_pos?>/Plugins/jquery-3.1.0.min.js">
</script>
</head>

<div class="top-container">
	<h2>ComicMyAdmin</h2>
	<div class="top-bar">
		<ul>
			<li class="top-icon"><a href="<?= $v->app_pos?>/ComicAdmin">Top</a></li>
			<li class=""><a href="<?= $v->app_pos?>/User/">Mypage</a></li>
			<li><a href="<?= $v->app_pos?>/Login/Logout">Logout</a></li>
			<li><a href="<?= $v->app_pos?>/ComicAdmin/SeriesMyList">MyList</a></li>
			<li><a href="<?= $v->app_pos?>/ComicAdmin/SeriesAllList">AddTitle</a></li>
		</ul>
	</div>
</div>
