$(document).ready(function() {
	
$("#index-shared-hover").hide().css({bottom : -304}, 700);
$("#index-reseller-hover").hide().css({bottom : -304}, 700);
$("#index-domains-hover").hide().css({bottom : -304}, 700);

$("#index-shared-footer").hover(function(){	
$("#index-shared-hover").show().animate({bottom : 0}, 900);}
,function(){
$("#index-shared-hover").stop();
$("#index-shared-hover").show().animate({bottom : -304}, 900);}
);

$("#index-reseller-footer").hover(function(){	
$("#index-reseller-hover").show().animate({bottom : 0}, 900);}
,function(){
$("#index-reseller-hover").stop();
$("#index-reseller-hover").show().animate({bottom : -304}, 900);}
);

$("#index-domains-footer").hover(function(){	
$("#index-domains-hover").show().animate({bottom : 0}, 900);}
,function(){
$("#index-domains-hover").stop();
$("#index-domains-hover").show().animate({bottom : -304}, 900);}
);
});