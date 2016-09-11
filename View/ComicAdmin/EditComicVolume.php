<script>
  $(function() {
  	$(".datepicker").each(function(){
		$(this).datepicker()
    	.datepicker("option", "dateFormat", 'yy-mm-dd' )
    	.datepicker("option", "showOn", 'both')
    	.datepicker("option", "buttonImage", '../Images/calendar_icon.png')
  		.datepicker("setDate", "2014-02-15");
  	});
  	
  });
</script>

<?php var_dump($_POST);?>

<div class="edit-comic-volume"> 

<h3>EditComicVolume</h3>

	<?php if($v->ret->result): ?> 
	<div class="warn">
		マスタデータを書き換えました[<?= $v->ret->edit?>] 
	</div>
	<?php endif; ?>
	
	<form method="post" action="<?=$v->app_pos?>/ComicAdmin/EditComicVolume?seriesId=<?=$v->series_data['series_id']?>">

	<div class="series-box">
		<h3>series</h3> id : <?=$v->series_data['series_id'] ?> <input type="submit" name="series_submit" value="send series">
		<div class="series-data">
			<table class="series-data">
			 	<tr>
				 	<td class="element-key"> title </td>
				 	<td class="element-value"><input type="text" name="series_data[title]" value="<?=$v->series_data['title']?>"></td>
				
				 	<td class="element-key">kana</td>
				 	<td class="element-value"><input type="text" name="series_data[kana]" value="<?=$v->series_data['kana']?>"></td>
					<td class="element-key"> is_end </td>
					<td><select name="series_data[is_end]">
						<option value="0" <?php if(!$v->series_data['is_end']):?>selected="selected"<?php endif;?>>not end</option>
						<option value="1" <?php if($v->series_data['is_end']):?>selected="selected"<?php endif;?>>ended </option>
					</select></td>
					
				</tr>
				<tr>
					<td class="element-key">author</td>
					<td class="element-value"><input type="text" name="series_data[author]" value="<?=$v->series_data['author']?>"></td>
					
					<td class="element-key">press</td>
					<td class="element-value"><input type="text" name="series_data[press]" value="<?=$v->series_data['press']?>"></td>
					<td class="element-key">explain</td>
					<td class="element-value"><input type="text" name="series_data[explain_text]" 
						value="<?=$v->series_data['explain_text']?>"></td> 
				</tr>
			</table>
			<table>
				<tr>
				 	<?php foreach ($v->series_data['category'] as $key => $category):?>
				 		<td <?php if($key%4==0):?> class="category-left" <?php endif; ?>><input type="checkbox" name="series_data[category][<?=$key?>]" value="<?=$category['category_id']?>" 
				 			<?php if($category['selected'] == 1):?> checked <?php endif; ?>>
				 			<?=$category['category_name']?></td>
				 		<?php if($key %4 == 3): ?></tr><tr> <?php endif; ?>		
				 	<?php endforeach; ?>
				 	
				</tr>
			</table>
		</div>
	</div>

	<div class="volume-head"><h3>volume</h3> <span><?=count($v->volume_list)?> records </span></div>
	<table class="volume-data">
		<tr>
			<th>book_id</th>
			<th>book_name</th>
			<th>release_date</th>
			<th></th>
		</tr>
	<?php foreach ($v->volume_list as $key => $volume):  ?>
	
		<tr>
			<td><?=$volume['book_id']?></td>
			<td><input type="text" name="volume_list[<?=$volume['book_id']?>][book_name]" 
				value="<?=$volume['book_name']?>"></td>
			<td><input type="text" name="volume_list[<?=$volume['book_id']?>][release_date]" 
				value="<?=$volume['release_date']?>" class="datepicker"></td>
			<td><input type="submit" name="volume_submit_list[<?=$volume['book_id']?>]" 
				value="send"></td>
		</tr>	
	<?php endforeach; ?>
	</table>

	<input type="submit" name="all_submit" value="all send">
	</form>

</div>
