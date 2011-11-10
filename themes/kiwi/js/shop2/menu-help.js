$(document).ready(function() {
	 
	//ACCORDION BUTTON ACTION (ON CLICK DO THE FOLLOWING)
/*		function accordionButtonover() {

		//REMOVE THE ON CLASS FROM ALL BUTTONS
		$(this).addClass('on');
		  
		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
	 	$('.accordionContent',this).slideDown(500);
   
		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
		 }; 
	function accordionButtonout(){
		 	$('.accordionContent',this).slideUp(500);
		 	$(this).removeClass('on');
		 }
		  
	
	 var config = {
        sensitivity : 50,        // number = sensitivity threshold (must be 1 or higher)
        interval    : 350,      // number = milliseconds for onMouseOver polling interval
        over        : accordionButtonover, // function = onMouseOver callback (REQUIRED)
        timeout     : 0,      // number = milliseconds delay before onMouseOut
        out         : accordionButtonout, // function = onMouseOut callback (REQUIRED)
		img_block   : ".accordionContent",
		timefirst    :100
		
     };
    $(".accordionButton").hoverDelay(config);

*/
		$('.real_cat a').hover(function(){
			
			var id_img = "." + $(this).attr('id_img');
			$('.all_right_image').removeClass('current_right_image');
			$(id_img).addClass('current_right_image');
		});
		$('.selected').parents('li').css('background-color','#CECECE')
		$('.top_menu_help, .top_menu_help_a').hover(function(){
			$('.all_right_image').removeClass('current_right_image');
			$('.top-menu-current').find('.right_image img:first-child').addClass('current_right_image');
		});
	   $(".accordionButton").hoverIntent (function() {

		//REMOVE THE ON CLASS FROM ALL BUTTONS
		$(this).addClass('on-help');
		  
		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
	 	$('.accordionContent',this).slideDown('normal');
   
		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
		 } ,	function(){
		 	$('.accordionContent',this).slideUp('normal');
		 	$(this).removeClass('on-help');
		 });

	
	/*** REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
	
	//ADDS THE .OVER CLASS FROM THE STYLESHEET ON MOUSEOVER 
		
	//ON MOUSEOUT REMOVE THE OVER CLASS
	
	/*** END REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
	
	
	/********************************************************************************************************************
	CLOSES ALL S ON PAGE LOAD
	********************************************************************************************************************/	
	$('.accordionContent').hide();

});