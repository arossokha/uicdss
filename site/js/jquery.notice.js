$(document).ready(function() {

	$(".bar-cross").click(function(){
		$("#promotion-bar").fadeOut("slow");
		$("#bar-invisible-wrapper").delay(500).hide("slow");
	});
	
	$(".twitter-cross").click(function(){
		$("#twitter-wrapper").fadeOut("slow");
		$("#twitter-invisible-wrapper").delay(500).hide("slow");	
	});

});