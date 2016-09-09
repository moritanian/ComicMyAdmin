<div class="warn">
	<?php if($v->is_add): ?> マイリストに追加されました <?php endif; ?>
</div>

<div class="all-comic-list">
<form method="get" action="<?=$v->app_pos?>/ComicAdmin/SeriesAllList">

<!-- 検索フォーム !-->
<div class="condition-select-space">
	<div class="is-contain">
		マイリストに含まれるか
		<select name="is_contain_my_list" value="is_contain_my_list">
			<option value="0"> - </option>
			<option value="1" <?php if($v->cond['mylist_contain_cond'] == 1):?> selected = "selected"<?php endif;?>> 含まれる </opion>
			<option value="2" <?php if($v->cond['mylist_contain_cond'] == 2):?> selected = "selected"<?php endif;?> > 含まれない </option>
		</select>
	</div>
	<div class="search-text">
		<input type="text" name="search_text" <?php if($v->cond['search_text']):?> value = <?= $v->cond['search_text']?><?php endif; ?>>
		<input type="submit" name='search' value='検索する'>
	</div>
</div>

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
	<div class='series-title <?php if($comic_data['is_contain_my_list']) echo('contain') ?>'>
		<div class="series-select-box">
			<input type="checkbox" name="id<?=$comic_data['series_id']?>" value="id<?=$comic_data['series_id']?>" <?php if($comic_data['is_contain_my_list']): ?> disabled = 'disabled' <?php endif; ?>>
		</div>
		<a href='./ComicVolumeList?series_id=<?=$comic_data['series_id']?>'> 
		<?=$comic_data['title']?>
		</a>
	</div>

<?php endforeach; ?>

</ul>
<input type="submit" name="add" value="mylistに追加">
</form>
</div>