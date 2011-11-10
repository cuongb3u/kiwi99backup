$(document).ready(function() {
	$('.main-menu li ').hover(function() {
		$(this).addClass('top-menu-current');		
		$('div',this).show();
	}, function() {
		$('div',this).hide();
		$(this).removeClass('top-menu-current');
	});
$('.tree-menu a[href='+window.location.href+'],.main-menu a[href='+window.location.href+']').addClass('tree-menu-selected');
		
});