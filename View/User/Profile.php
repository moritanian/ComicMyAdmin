<div class="user-profile">
<h2>Profile</h2>
<?php  if($v->authority > 2): ?><a href="#" onclick="linkWithTimeStamp('<?=$v->app_pos?>/User/UserList')">UserList</a><?php endif; ?>

	<ul> 
		<li><span class="a-text-bold">username </span> 
			<div class="user-data-box"><?=$v->userData['user_name']?> </div>
			<div class="edit-button">
			</div>
		</li>
		
		<li>
			<div class="element-grid">
				<span class="a-text-bold"> mail address </span>
				<div class="user-data-box"><?= $v->userData['mail_address']?></div>
			</div>
			<div class="edit-button-ace">
				<input class="edit-button" type="submit" value="編集する">
			</div>
		</li>
		
		<li><form method="get" action="<?=$v->app_pos?>/User/EditNotification">
			<div class="element-grid">
				<span class="a-text-bold"> notification連携 </span>
				<div class="user-data-box">
				<?php if($v->userData['notification_id']): ?>
					登録済み
				<?php else: ?>
					未登録
				<?php endif;?>
				</div>
			</div>
			<div class="edit-button-ace">
				<input class="edit-button" type="submit" value="編集する">
			</div>
		</form></li>

		<li><form method="get" action="<?=$v->app_pos?>/User/EditLine">
			<div class="element-grid">
				<span class="a-text-bold"> line連携 </span>
				<div class="user-data-box">
				<?php if($v->userData['line_id']): ?> 
					登録済み
				<?php else: ?>
					未登録
				<?php endif;?>
				</div>
			</div>
			<div class="edit-button-ace">
				<input class="edit-button" type="submit" value="編集する">
			</div>
		</form></li>
	</ul>
</div>
