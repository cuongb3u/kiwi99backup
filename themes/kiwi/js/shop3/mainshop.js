$(document).ready(function() {
	$('.main-menu > li ').hoverIntent(function() {
		$(this).addClass('top-menu-current');
		image=$('a',this).attr('img_link');
			link=$('a',this).attr('href');
			box=$('div',this);
			$('.dropdown-menu img',this).attr('src',image);
		  $('.dropdown-menu > a',this).attr('href',link);	
		  	$('.dropdown-menu').stop().hide();	
			$(box).slideDown('500');
	}, function() {
		$('div',this).hide();
		$(this).removeClass('top-menu-current');
	});
$('.tree-menu a[href='+window.location.href+'],.main-menu a[href='+window.location.href+']').addClass('tree-menu-selected');
		$('.dropdown-menu  li a').hover(function() {
			image=$(this).attr('img_link');
			link=$(this).attr('href');
		 $('.dropdown-menu img').attr('src',image);
		  $('.dropdown-menu > a').attr('href',link);
		}, function() {
			// Stuff to do when the mouse leaves the element;
		});
});