$(document).ready(function() {
	
	$(".shared-content-dropdown-background").hide();
	$("#Geeky").css("margin-top","20px");
	$("#FTP").click(function(){
		$("#FTP-Dropdown").slideToggle(1500);
    		
});
$("#Geeky").click(function(){
		$("#Geeky-Dropdown").slideToggle(1500);
});
});