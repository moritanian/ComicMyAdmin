<script type="text/javascript">
$(function(){
	$(".dialog" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			"submit": function(){
				var series_id = <?=$v->series_data['series_id']?>;
				var book_id = parseInt($(this).attr("id").substr(6));
				var is_possess = $(this).find(".is-possess > div").attr("state");
				var is_read = $(this).find(".is-read > div").attr("state");
				var assessment = $(this).find(".assessment").attr("star-num");
				var user_comment = $(this).find(".user-comment > input").val(); 

				// ページ内の情報更新
				var volume_data_ele = $(".volume-list-box").find('li[book_id="' + book_id +'"]');
				volume_data_ele.find(".is-possess").text(is_possess == 1 ? "所持している" : "所持していない");
				volume_data_ele.find(".is-read").text(is_read == 1 ? "既読" : "未読");
				volume_data_ele.find(".comment").text("comment : "  + user_comment);
				change_all_star(volume_data_ele.find(".assessment"), assessment);
				
				// dbデータ更新
				var data = {
						book_id : book_id,
						series_id : series_id,
						is_possess : is_possess,
						is_read : is_read,
						assessment : assessment,
						user_comment : user_comment
					};

				$.ajax({
					url: '../API/UserVolumeDataEdit.php',
					type: 'POST',
					contentType : "application/json",
					cache: 'false',
					dataType: 'json',
					data : JSON.stringify(data)
				})
				.done(function (data, textStatus, jqXHR) {
					
				})
				.fail(function(jqXHR, testStatus, errorThrown){
					alert("failed " + errorThrown);
				})
				.always(function(data, textStatus, errorThrown){
					
				});

				$(this).dialog('close');
			}
		}
	});
	
	$(".volume-data").click(function(){
		var book_id = $(this).attr('book_id');
		console.log(book_id);
		
		// check button 設定
		$("#dialog" + book_id).find(".check-button").each(function(){
			var on_name = $(this).attr('on-name');
			var off_name = $(this).attr('off-name');
			var state = $(this).attr('state');
			$(this).text(state == 0 ? off_name : on_name); 
		});
	
		$("#dialog" + book_id).dialog('open');
	});

	$(".check-button").click(function(){
		var on_name = $(this).attr('on-name');
		var off_name = $(this).attr('off-name');
		var state = $(this).attr('state');
		var new_state = state==0 ? 1 : 0;
		$(this).attr("state", new_state);
		$(this).text(new_state == 0 ? off_name : on_name); 
	});

	

	var star_mono_class = 'star-icon mono assess-star';
	var star_class = 'star-icon assess-star';
	$(".dialog-assess-star").click(function(){
		var crt_star_num = parseInt($(this).attr("star-num"));
		console.log("star" + crt_star_num);
		var star_parent = $(this).parent();
		all_star_num = star_parent.attr("star-num");

		var new_star_all_num = crt_star_num + 1; //選択した星までをアクティブに
		if(all_star_num == crt_star_num + 1){ // アクティブになっている星の一番右側選択の場合はそれのみ非アクティブに
			new_star_all_num = all_star_num - 1;
		}
		
		console.log("new-star-num" + new_star_all_num);
		
		change_all_star(star_parent, new_star_all_num );

	});

	function change_all_star(parent, all_star_num){
		parent.attr("star-num", all_star_num);
		for(var i=0;i<5;i++){
			change_star(parent.find('div[star-num=\"' + i + '\"]'), i < all_star_num);
		}
	}

	function change_star(acess_star, state){
		if(state){
			acess_star.attr("class", star_class);
		}else{
			acess_star.attr("class", star_mono_class);
		}
	};

	

	// overlay クリックで閉じる
	$(document).on("click", ".ui-widget-overlay", function(){
		$(".dialog").dialog('close');
	});

	
});

</script>

<div class="volume-list">

<h3> MyVolumeList </h3>
<?php  if($v->authority > 1): ?><a href="<?=$v->app_pos?>/ComicAdmin/EditComicVolume?seriesId=<?=$v->series_data['series_id']?>">EditVolumeData</a><?php endif; ?>

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
					<div class="element-key tag"> タグ </div>
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

	<div class="volume-list-box">
		<ul>
		<?php foreach ($v->volume_list as $key => $volume): ?>
			<li class="volume-data" book_id = <?= $volume['book_id']?>>
				<div class="book-name"><h3><?= $volume['book_name']?></h3></div>
				<div class="is-possess">
					<?php if($volume['is_possess']):?>所有<?php else: ?>所有していない<?php endif;?>
				</div>
				<div class="is-read">
					<?php if($volume['is_read']):?>既読<?php else: ?>未読<?php endif;?>
				</div>

				<div class="assessment">star : 
						<?php for($i=0;$i<5; $i++):?>
							<div class="star-icon 
								<?php if($i >= $volume['assessment']):?>mono <?php endif; ?>
								assess-star" star-num="<?=$i?>"></div>
						<?php endfor; ?>
					
				</div>
				<div class="comment">comment : 
					<?=$volume['user_comment']?>
				</div>

				<div class="dialog" id="dialog<?= $volume['book_id']?>" title="edit volume data">
					<div class="book_name"><h3><?= $volume['book_name']?></h3></div>

					<div class="is-possess">
						<div type="button" class="check-button" on-name="所持" off-name="不所持"  
								state=<?= $volume['is_read']?> >
						</div>
					</div>
					
					<div class="is-read">
						<div type="button" class="check-button" on-name="既読" off-name="未読"  
								state=<?= $volume['is_possess']?>>
						</div>
					</div>

					<div class="assessment" star-num="<?=$volume['assessment']?>">star: 
						<?php for($i=0;$i<5; $i++):?>
							<div class="star-icon 
								<?php if($i >= $volume['assessment']):?>mono <?php endif; ?>
								dialog-assess-star" star-num="<?=$i?>"></div>
						<?php endfor; ?>
					</div>
					
					<div class="user-comment">
						コメント:<input type="text" value="<?=$volume['user_comment']?>">
					</div>
					
						
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>  

