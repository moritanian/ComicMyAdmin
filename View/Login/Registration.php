<div class="login">
	<h1>新しいユーザ名とパスワードを登録してください</h1>
	* パスワードは6文字以上
	<form method="post" action="">
	    ユーザ名: <input type="text" name="username" value= "<?=$v->h($v->username) ?>" autocomplete="nope" >
	    パスワード: <input type="password" name="password" value="" autocomplete="nope">
	    <input type="hidden" name="submit" value="submit">
	    <input type="submit" value="登録">
	</form>
	<a href="<?= $v->app_pos?>/Login">ログインへ</a>
	<?php if (http_response_code() === 403): ?>
	<p style="color: red;"><?= $v->error ?></p>
	<?php endif; ?>
</div>