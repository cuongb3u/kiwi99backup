$(document).ready(function() {
	$('.main-menu li ').hover(function() {
		$(this).addClass('top-menu-current');		
		$('div',this).show();
	}, function() {
		$('div',this).hide();
		$(this).removeClass('top-menu-current');
	});
	$('.rphZoomItems li').hover(function() {
		$('img',this).addClass('opacity_7');
		$('div',this).fadeIn('slow');
		$('img',this).animate({
			top: '-22', 
			left: '-9',
			width:458,
			height:288
			}, 'fast');
	}, function() {
		$('div',this).fadeOut('slow');
		$('img',this).removeClass('opacity_7');
		$('img',this).animate({
			top: '-40', 
			left: '-30',
			width:500,
			height:320
			}, 'fast');
	});
$('.tree-menu a[href='+window.location.href+'],.main-menu a[href='+window.location.href+']').addClass('tree-menu-selected');
		
});
