<link href="<?= $v->app_pos?>/Plugins/jquery.bxslider.css" rel="stylesheet" />
<script src="<?= $v->app_pos?>/Plugins/jquery.bxslider.min.js">

</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.box-slider').bxSlider({
			auto:true,
			speed:1000,
			pause:2000
		});
	})
</script>
<div class="top-slider-container">
	<div class="box-slider">
		<?php foreach($v->topSlideImgs as $key => $img): ?>
		<div class="slide-img"><img src="<?=$img ?>"></div>
		<?php endforeach; ?>
	</div>
</div>
<h3> 今月の新刊情報</h3>


