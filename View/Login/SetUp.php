<div class="user-name-setup contents-box">
	<div class="title">
		<h4><?=$v->gName?>さん<h4>
		ユーザ名を登録してください
	</div>

	<?php if($v->err): ?>
	<div class="warn">
		<?=$v->err ?>
	</div>
	<?php endif; ?>

	<form method='post' action="<?=$v->app_pos?>/Login/SetUp">
		name
		<input type="text" name="user_name">
		<input type="submit" name="submit" value="送信">
		<input type='hidden' name='g_name' value=<?=$v->gName?>>
	</form>
</div>