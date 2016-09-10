<h3> ComicVolumeList </h3>
<?php  if($v->authority > 1): ?><a href="<?=$v->app_pos?>/ComicAdmin/EditComicVolume?seriesId=<?=$v->series_data['series_id']?>">EditVolumeData</a><?php endif; ?>

<div class="volume-list">
	<div class="series-data-box">
		<div class="title">
			<div class="name" ><h2> <?= $v->series_data['title']?> </h2></div>
			<?php if($v->series_data['is_end']): ?> <span class="end-title">ended</span><?php endif; ?>
		</div>
		<div class="series-detail">
			<div class="series-img">
				<img src="<?=$v->series_data['series_img']?>">
			</div>
			<div class="series-profile">
				<div class="prof-element">
					<div class="element-key">著者</div>
					<div class="element-value"><?=$v->series_data['author']?></div>
				</div>
				<div class="prof-element">
					<div class="element-key">連載</div>
					<div class="element-value"><?= $v->series_data['press']?></div>
				</div>
				<div class="prof-element">
					<div class="element-key"> タグ </div>
					<div class="element-value tag">
					<?php for($i=1; $i<=10; $i++): ?> 
						<?php if($v->series_data["category$i"]): ?>
							<div class="category-name"><?=$v->series_data["category${i}_name"] ?></div>
						<?php endif;?>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
</div>  