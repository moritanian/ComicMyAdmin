<?php
header("HTTP/1.1 404 Not Found");
//include("/View/Footer.php");
?>
<html>
<head>
<title>Error 404 Not Found</title>
</head>
<body>
<div class="contents-box">
	<h1>ページが見つかりませんでした</h1>
	<a href="<?=$v->app_pos?>/ComicAdmin">topへ</a>
</div>
</body>
</html>