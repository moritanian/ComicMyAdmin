
<script type="text/javascript">
	jQuery(document).ready(function(){
		$('.box-slider').bxSlider({
			auto:true,
			controls: true,
			speed:1000,
			pause:2000
		});
	})
</script>
<?php //echo ("path" . session_save_path());?>
<div class="top-page">
	<div class="top-slider-container">
		<div class="box-slider">
			<?php foreach($v->topSlideImgs as $key => $img): ?>
			<div class="slide-img"><img src="<?=$img ?>"></div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="month-news">
		<div class="title"><h3> | 今月の新刊情報</h3></div>
		<ul>
			<li>ゼノンの逆襲1</li>
		</ul>
	</div>
	
	<div class="release-news">
		<h3> | アプリ新規機能リリース情報</h3>
		
	</div>
</div>

