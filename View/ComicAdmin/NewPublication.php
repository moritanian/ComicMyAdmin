<div class="publication contents-box">
	<h3>今月の新刊</h3>
	<div class="search">
		<form action="" method="get">
			<input type="text" name="search_text" value="<?=$v->search_text?>">
			<input type="submit" name="search" value="検索">
		</form>

	</div>
	<div>
		<div>全 <?=$v->info['data']['item_all'] ?> 件 </div>
		<div class="page-list"> 
		<table>
		<tr>
		<?php for($i=0; $i<$v->info['data']['pages']; $i++): ?>
			<td>
				<?php if($v->info['data']['page'] != $i + 1): ?>
					<a href="<?=$v->app_pos?>/ComicAdmin/NewPublication/?page=<?=($i+1)?>
						<?php if($v->search_text):?>&search_text=<?=$v->search_text?> <?php endif;?>">
						<?= ($i + 1) ?>   
					</a>
				<?php else: ?>
					<?= ($i + 1) ?>
				<?php endif; ?>
			</td>
		<?php endfor; ?>
		</tr>
		</table>
		</div>
	</div>

	<?php  //var_dump($v->publication_list); echo("publication");?>

	<?php foreach ($v->info['item_list'] as $key => $item): ?>
		<?php include("View/_snippet/volume_data.php");?>
	<?php endforeach; ?>
</div>