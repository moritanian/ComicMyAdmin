<script>
	function go_home(){
		linkWithTimeStamp("<?=$v->app_pos?>/ComicAdmin");
	}
</script>

<div class="footer">
<hr class="separator" />
<ul>
	<li><button class="return-button" type="button" onclick="backWithTimeStamp()"><div class="ui-icon ui-icon-arrowreturnthick-1-w"></div>戻る
	</button></li>
	<li><button class="home-button" type="button" onclick="go_home()">
	<div class="ui-icon ui-icon-home "></div>homeへ</button></li>
</div>
</div>
</html>