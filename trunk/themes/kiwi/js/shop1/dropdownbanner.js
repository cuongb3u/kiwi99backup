// JavaScript Document
$(document).ready(function(){
						   
						 
    $(".ad_block").bind('mouseover', function(){
        $(this).stop().animate({height:'119px'},{queue:false, duration:700, easing: 'jswing'})
    });

     $(".ad_block").bind('mouseout', function(){
        $(this).stop().animate({height:'0px'},{queue:false, duration:700, easing: 'jswing'})
    });

    $(".brand_logo").click(function(){
        $(this).blur();
    });
    function brand_logo_mouse_on(){
	
        $(this).toggleClass('current');
        block = '#'+$(this).attr('block');
        $(block).stop().animate({height:'119px'},{queue:false, duration:600, easing: 'jswing'})
    }
    function brand_logo_mouse_out(){
        $(this).toggleClass('current');
        block = '#'+$(this).attr('block');
        $(block).stop().animate({height:'0px'},{queue:false, duration:600, easing: 'jswing'});
	
    }
	
    var config = {
        sensitivity : 50,        // number = sensitivity threshold (must be 1 or higher)
        interval    : 450,      // number = milliseconds for onMouseOver polling interval
        over        : brand_logo_mouse_on, // function = onMouseOver callback (REQUIRED)
        timeout     : 0,      // number = milliseconds delay before onMouseOut
        out         : brand_logo_mouse_out, // function = onMouseOut callback (REQUIRED)
		img_block   : "#img_block",
		timefirst    :150
		
     };
    $(".brand_logo").hoverDelay(config);

    function fixwidthul(uldata){
    	widthfix = 0;
    	$('li',uldata).each(function(){
    		widthfix += $(this).outerWidth();
    		$(uldata).width(widthfix);
    	})
    }


						  
						  
})