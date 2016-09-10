

<div class="series-my-list">
<h3>MyList</h3>
<ul>
<?php 
$initial = "";
foreach($v->mylist as $key => $comic_data): ?>
	<div class="series">
		<?php if($initial != $comic_data['initial']): 
			$initial = $comic_data['initial']; ?>
			<div class="initial-box">
			<?=$initial?>
			</div>
		<?php endif; ?>

		<div class='series-title'>
			<a href='./VolumeMyList?series_id=<?=$comic_data['series_id']?>'> 
			<?=$comic_data['title']?>
			</a>
		</div>
	</div>
<?php endforeach; ?>
</ul>