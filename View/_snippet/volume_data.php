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
			<div class="prof-element">
				<div class="element-key">発売日</div>
				<div class="element-value"><?= $item['salesDate']?></div>
			</div>
		</div>
	</div>
</div>