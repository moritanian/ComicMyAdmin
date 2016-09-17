window.onload = function(){

	var settings = {
		interval : 3000, // cubeを出す間隔
		dur : 30000, // 	cubeが持続する時間
		windowPos : $("#fly-anim-area").position(),
		windowSize : {width : $("#fly-anim-area").width(),
						height :  $("#fly-anim-area").height() - 20
						}
	}

	var img_width = 70;
	var img_height = 32;
	
	// cubeの最終スタイル設定
	var styleFor = {
		height : img_height, // cubeの高さ
		width : img_width, // cubeの幅
		'border-width' : 10 // cubeの罫線幅
	}
	
	// cubeの初期スタイル設定
	var styleInit = {
		position:'absolute',
		'z-index' : 1,
		display : 'inline-block',
		height : img_height,
		width : img_width,
		adj : 0,
		//'background-image': "url('../Images/butterfly.png')",
		'background-size': img_width + 'px ' + img_height + 'px'
	}
	
	var cube = {
		init : function(){
			$("#fly-anim-area").wrapInner('<div style="position:relative;z-index:2"></div>')
			styleInit.adj = styleFor['border-width'] + styleFor['width'];
		},
		add : function(){
			var initX = cube.getRandomX();
			var initY = cube.getRandomY();
	
			var endX = cube.getRandomX();
			var endY = cube.getRandomY();
			
			styleFor.left = endX;
			styleFor.top = endY;
			
			$("#fly-anim-area").append($('<div class="block butterfly" />')
				.css(styleInit) // end init
				.css({
					top : initY,
					left : initX,
				})
				.animate(styleFor,{
						easing : 'linear',
						duration : settings.dur,
						complete : function(){
							$(this).fadeOut(300,function(){
								$(this).remove();
							});
						}
					}
				)
			);
		},
		getRandomX : function(){
			return Math.floor( settings.windowPos.left + Math.random() * settings.windowSize.width - styleInit.adj);
		},
		getRandomY : function(){
			return Math.floor( settings.windowPos.top + Math.random() * settings.windowSize.height);
		}
	}

//console.log(settings.windowPos);
//console.log(settings.windowSize);
	// execute
	cube.init();
	setInterval(function(){
		cube.add();
	},settings.interval);
}