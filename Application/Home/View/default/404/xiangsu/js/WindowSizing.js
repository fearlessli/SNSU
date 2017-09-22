/*窗体大小适应*/
height = $(window).height();
width = $(window).width();
$(window).resize(function() {
	height = $(window).height();
	width = $(window).width();
	$("body").css('height', height + "px");
	$(".sidebar").css('height', height - 120 + "px");
	$(".sideNav").css('height', height - 120 + "px");
	$(".content").css('height', height - 120 + "px");
	$(".designIndex").css('height', height - 180 + "px");
	$(".carousel-inner").css('max-height', height - 210 + "px");
	$(".designNavModal").css('height', height - 120 + "px");
	$(".designNavModal").css('width', width - 185 + "px");
	$(".designNavIndex").css('height', height - 120 + "px");
	$(".designNavGallery").css('height', height - 120 + "px");
	$(".designIndex").css('height', height - 180 + "px");
	$(".designGallery").css('height', height - 180 + "px");
	$(".carousel-inner").css('max-height', height - 210 + "px");
	$(".designNavMenu").css('height', height - 120 + "px");
});
$("body").css('height', height + "px");
$(".sidebar").css('height', height - 120 + "px");
$(".sideNav").css('height', height - 120 + "px");
$(".content").css('height', height - 120 + "px");
