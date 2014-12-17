$(function() {
	//check for a cookie specifying the current stylesheet as soon as we are ready
	if ($.cookie('css_darkside') ) {
		//if found append this to the stylesheet href
		$('#stylesheet').attr({'href' : $.cookie('css_darkside') });
	}
	
	$("#colour-changer-blank").hide();
	$(".colour-changer-link").click(function(){
		$(".colour-changer-link").fadeOut("slow");
		$("#colour-changer-blank").delay(500).fadeIn("slow");
	});
	
	$(".colour-changer-cross").click(function(){
		$("#colour-changer-blank").fadeOut("slow");
		$(".colour-changer-link").delay(500).fadeIn("slow");
	});
	//check when switching the colour scheme
	$('.colour-changer-blue').click(function() {
		//assign the stylesheet path
		var stylePath = $(this).attr('rel');
		$.cookie('css_darkside', stylePath, {
			expires: 365,
			path: '/'
		});
		$('#stylesheet').attr({'href' : $.cookie('css_darkside') });
	});
	$('.colour-changer-green').click(function() {
		//assign the stylesheet path
		var stylePath = $(this).attr('rel');
		$.cookie('css_darkside', stylePath, {
			expires: 365,
			path: '/'
		});
		$('#stylesheet').attr({'href' : $.cookie('css_darkside') });
	});
	$('.colour-changer-red').click(function() {
		//assign the stylesheet path
		var stylePath = $(this).attr('rel');
		$.cookie('css_darkside', stylePath, {
			expires: 365,
			path: '/'
		});
		$('#stylesheet').attr({'href' : $.cookie('css_darkside') });
	});
	$('.colour-changer-yellow').click(function() {
		//assign the stylesheet path
		var stylePath = $(this).attr('rel');
		$.cookie('css_darkside', stylePath, {
			expires: 365,
			path: '/'
		});
		$('#stylesheet').attr({'href' : $.cookie('css_darkside') });
	});
});