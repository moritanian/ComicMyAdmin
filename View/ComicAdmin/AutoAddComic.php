<div class="add-series-title-box">

<h3> AutoAddComic </h3> 

<div class="result warn">

</div>

<form method="post" action="<?=$v->app_pos?>/ComicAdmin/AutoAddComic">
<input type="text" name="search_title" <?php if(isset($v->search_title)):?> value="<?=$v->search_title?>" <?php endif; ?>>
<input type="submit" value="検索する" name="add_series_title">
</form>
<div class="result">
	<?php foreach ($v->itemInfo as $key => $item): ?>

	<div class="volume-container">
		<div class="title">
			<div class="name" ><h2> <?= $item['title']?> </h2></div>
		</div>
		<div class="series-detail">
			<div class="series-img">
				<a href=<?= $item["amazonItemURL"]?> target="_blank"><img src="<?=$item['imgURL']?>"></a>
			</div>
			<div class="series-profile">
				<div class="prof-element">
					<div class="element-key">著者</div>
					<div class="element-value"><?=$item['author']?></div>
				</div>
				<div class="prof-element">
					<div class="element-key">連載</div>
					<div class="element-value"><?= $item['recordLabel']?></div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>