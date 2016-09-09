<h3> AddComicSeries</h3> 

<div class="result warn">
<?php if($v->ret['success']):?>
	データが追加されました。
<?php elseif($v->ret['error']):?>
	<?= $v->ret['error']?>
<?php endif; ?>
<div>

<div class="add-series-title-box">
<form method="post" action="<?=$v->app_pos?>/ComicAdmin/AddComicSeries">
<input type="submit" value="追加する" name="add_series_title">
<table>
	<tr>
		<td>title</td>
		<td><input type="text" name="title"></td>
	</tr>
	<tr>
		<td>kana</td>
		<td><input type="text" name="kana"></td>
	</tr>
	
	<tr>
		<td>all volume number</td>
		<td><input type="text" name="all_volume_number"></td>
	</tr>
	<tr>
		<td>is_end</td>
		<td><select name="is_end">
			<option value="0" selected="selected">not end</option>
			<option value="1">ended </option>
		</select></td>
	</tr>
	
	<tr>
		<td>author</td>
		<td><input type="text" name="author"></td>
	</tr>

	<tr>
		<td>press</td>
		<td><input type="text" name="press"></td>
	</tr>

	<tr>
		<td>explain_text</td>
		<td><input type="text" name="explain_text"></td>
	</tr>
	
	<?php for($i=1; $i<=10; $i++): ?>
	<tr>
	
		<td>category<?=$i?></td>
		<td><select name="category<?=$i?>">
			<option value="0" selected="selected">なし</option>
			<?php foreach($v->category_list as $key => $category): ?>
			<option value='<?=$category['category_id']?>'><?=$category['category_name']?></option>
			<?php endforeach; ?>
		</select></td>
	</tr>	
	<?php endfor ?>
</table>
</form>
</div>