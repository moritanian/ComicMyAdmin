<?php //var_dump($v->userData)?>
<h2>Profile</h2>

<div class="user-profile">
	<ul> 
		<li><span class="a-text-bold">username </span> 
			<div><?=$v->userData['user_name']?> </div>
			<div class="edit-button">
			</div>
		</li>
		
		<li><span class="a-text-bold"> mail address </span>
			<div><?= $v->userData['mail_address']?></div>
		</li>
		
		<li><form method="get" action="<?=$v->app_pos?>/User/EditNotification">
			<div class="element-grid">
				<span class="a-text-bold"> notification連携 </span>
				<div>
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
				<div>
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
