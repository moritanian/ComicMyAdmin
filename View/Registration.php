<title>新規登録	</title>
<h1>新しいユーザ名とパスワードを登録してください</h1>
* パスワードは6文字以上
<form method="post" action="">
    ユーザ名: <input type="text" name="username" value= "<?=$v->h($v->username) ?>" >
    パスワード: <input type="password" name="password" value="">
    <input type="submit" value="登録">
</form>
<a href="../Login">ログインへ</a>
<?php if (http_response_code() === 403): ?>
<p style="color: red;"><?$v->h($v->$error) ?></p>
<?php endif; ?>