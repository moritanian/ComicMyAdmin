<ul>
<?php
$initial = "";
foreach($v->all_comic_data as $key => $comic_data): ?>
	
<?
	if($initial != $comic_data['initial']){
		$initial = $comic_data['initial'];
		echo($initial);
	}
?>
	<div class='series-tytle'>
		<a href='./ComicVolumeList?series_id=<?=$comic_data['series_id']?>'> 
		<?=$comic_data['title']?>
		</a>
	</div>

<?php endforeach; ?>

</ul>