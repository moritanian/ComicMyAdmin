<?php
header("HTTP/1.1 403 Forbidden");
//include("/View/Footer.php");
?>
<html>
<head>

<title>Error 403 Forbidden</title>
</head>
<body>
<h1>あなたの権限でこのページを見ることはできません</h1>
<a href="<?=$v->app_pos?>/ComicAdmin">topへ</a>
</body>
</html>