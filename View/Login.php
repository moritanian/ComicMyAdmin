<title>ログインページ</title>
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

<a href="./Login/Registration"> 新規ログインへ </a>