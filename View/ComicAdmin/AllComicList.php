<div class="all-comic-list">
<form type="post" action="/">
<ul>
<?php
$initial = "";
foreach($v->all_comic_data as $key => $comic_data): ?>
	
	<?php if($initial != $comic_data['initial']): 
		$initial = $comic_data['initial']; ?>
		<div class="initial-box">
		<?=$initial?>
		</div>
	<?php endif; ?>
	<div class='series-title'>
		<div class="series-select-box">
			<input type="checkbox" value="id<?=$comic_data['series_id']?>">
		</div>
		<a href='./ComicVolumeList?series_id=<?=$comic_data['series_id']?>'> 
		<?=$comic_data['title']?>
		</a>
	</div>

<?php endforeach; ?>

</ul>
<input type="submit" value="mylistに追加">
</form>
</div>