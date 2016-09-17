
	<div class="top-bar">
		<ul>
			<li class="top-icon"><a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/ComicAdmin/')">Top</a></li>
			<li class=""><a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/User')">Mypage</a></li>
			<li><a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/ComicAdmin/SeriesMyList')">MyList</a></li>
			<li><a href="#" onclick="linkWithTimeStamp('<?= $v->app_pos?>/ComicAdmin/SeriesAllList')">AddTitle</a></li>
			<?php if($v->authority > 1): ?>
			<li><a href="#" onclick="linkWithTimeStamp('<?=$v->app_pos?>/ComicAdmin/AddComicSeries')">AddSeries</a></li>
			<?php endif; ?>
		
			
			<li><a href="<?= $v->app_pos?>/Login/Logout">Logout</a></li>
		</ul>
	</div>

