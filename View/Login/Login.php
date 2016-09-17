<title>ログインページ</title>
<?php var_dump($_SESSION); 
	var_dump($_COOKIE);
?>
<h1>ログインしてください</h1>
<form method="post" action="">
    ユーザ名: <input type="text" name="username" value= "<?=$v->h($v->username) ?>" >
    パスワード: <input type="password" name="password" value="">
    <input type="hidden" name="token" value="<?=$v->h($v->generate_token())?>">
    <input type="submit" value="ログイン">
</form>
<?php if (http_response_code() === 403): ?>
<p style="color: red;">ユーザ名またはパスワードが違います</p>
<?php endif; ?>

<a href="<?=$v->app_pos?>/Login/Registration"> 新規登録へ </a>