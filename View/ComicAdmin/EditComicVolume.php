<script>
  $(function() {
  	$(".datepicker").each(function(){
		setDatePicker($(this));
		/*$(this).datepicker()
    	.datepicker("option", "dateFormat", 'yy-mm-dd' )
    	.datepicker("option", "showOn", 'both')
    	.datepicker("option", "buttonImage", '../Images/calendar_icon.png');
    	if($(this).attr("date") != "0000-00-00"){
  			$(this).datepicker("setDate", $(this).attr("date"));
  		}*/
  	});

  	function setDatePicker(element){
  		element.datepicker()
    	.datepicker("option", "dateFormat", 'yy-mm-dd' )
    	.datepicker("option", "showOn", 'both')
    	.datepicker("option", "buttonImage", '../Images/calendar_icon.png');
    	if(element.attr("date") != "0000-00-00"){
  			element.datepicker("setDate", element.attr("date"));
  		}
  	}

  	$("#add-volume-data").click(function(){
  		$("table.volume-data").append(
  			"<tr>"
			+"<td>new</td>"
			+"<td><input type='text' name=''" 
				+"value=''></td>"
			+"<td><input type='text' name='' value='' class='datepicker' date=''></td>"
			+"<td><input type='submit' name='' value='send'></td>"
			+"<td class='delete-element'><input type='button' class='delete-element-button' value='delete'></td></tr>"	
			);
  		var date_element = $("table",".volume-data tr").last().find(".datepicker");
  		setDatePicker(date_element);
  	});

  	//$(".delete-element-button").on('click', function(){
  	$(document).on("click", ".delete-element", function(){
  	  		console.log("bu");
  		$(this).parent().remove();
  	});
  	
  });
</script>

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
				value="<?=$volume['release_date']?>" class="datepicker" date="<?=$volume['release_date']?>"></td>
			<td><input type="submit" name="volume_submit_list[<?=$volume['book_id']?>]" 
				value="send"></td>
		</tr>	
	<?php endforeach; ?>
	</table>

	<input type="button" id="add-volume-data" name="" value="add one record">
	<input type="submit" name="all_submit" value="all send">
	</form>

</div>
