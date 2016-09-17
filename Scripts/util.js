var linkWithTimeStamp = function(href){
	var timeStamp = getUnixTime();
	$("body").append("<form method='post' name='auto_form' id='auto_form' action='"
		+ href
		+ "'>"
		+"<input type='hidden' name='time' value='"
		+ timeStamp  
	 	+ "'></form>");
	document.auto_form.submit();
}

var backWithTimeStamp = function(){
	var ref = document.referrer;
	linkWithTimeStamp(ref);
}


var getUnixTime = function(){
	var date = new Date() ;
	return Math.floor( date.getTime() / 1000 ) ;
}