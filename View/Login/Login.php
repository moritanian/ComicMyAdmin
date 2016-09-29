<div class="login">
	<h1 class="inline-block">ログインしてください</h1>

	<div class="google-login inline-block">
		<a href="<?=$v->app_pos?>/Login/SignInWithGoogle"> 
			<img src="<?=$v->app_pos?>/Images/sign-in-with-google.png">
		</a>
	</div>
	<form method="post" action="">
	  	ユーザ名: <input type="text" name="username" value= "<?=$v->h( $v->username ) ?>">
	    パスワード: <input type="password" name="password" value="">
	    <input type="hidden" name="token" value="<?=$v->h($v->token)?>" />
	    <input type="submit" value="ログイン">
	</form>
	<?php if (http_response_code() === 403): ?>
	<p style="color: red;">ユーザ名またはパスワードが違います</p>
	<?php endif; ?>

	<a href="<?=$v->app_pos?>/Login/Registration"> 新規登録へ </a>
</div>

