//Bạn cần đặt function quanh $ để tránh trường hợp bị trùng lặp
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin

 		ajaxloading: function(method,options) {
 				
 				var options = jQuery.extend({
				        content: 'div.ajax-content',
				        blurcontent: 'div.ajax-content',
				        img:'../images/ajaxloading.gif'
				    },
				    options);
				    var loading ='<img class="ajaxloadingimg" src="'+options.img+'" />';
				   itop=$(this).outerHeight()/2-$('.ajaxloadingimg').outerHeight();
				   ileft=$(this).outerWidth/2-$('.ajaxloadingimg').outerWidth();
				   $('.ajaxloadingimg').css('top',itop);
				     $('.ajaxloadingimg').css('left',ileft);
 			if (method=='remove') {
 				$('.opacity_5',this).removeClass('opacity_5');
 				html=$('.ajax-content',this).html();
 				$(this).html(html);
 				$('.ajaxloadingimg',this).remove();
 				$('.posiRelative').removeClass('posiRelative');
 				return;
 			};
			// Kiểm tra từng element và xử lý
    		return this.each(function() {
    		$(this).wrapInner('<div class="ajax-content"  />')
    		$(options.blurcontent,this).addClass('opacity_5');
    		$(this).addClass('posiRelative');
			$(this).append(loading);
				
			
    		});
    	}
	});
})(jQuery);
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin

 		boxquestion: function() {
    		return this.each(function() {
    			$(this).addClass('posiRelative');
    		$('.question-content',this).wrap('<div class="wrapperb-question-content"  />');
    		$('.wrapperb-question-content',this).wrap('<div class="wrappert-question-content"  />');	
    		$(this).hoverIntent(function() {
    			$('> div',this).show();
    		}, function() {
    			$('> div',this).hide();
    		});		
    		});
    		
    	}
	});
})(jQuery);
	(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin

 		showattr: function(attr,options) {
    		return this.each(function() {
    			$(this).addClass('posiRelative');
				box='<div class="attr_box attr_box'+options+'" >'+$(this).attr(attr)+'</div>';
    			$(this).append(box);
    			$(this).hoverIntent(function() {
    				$('.attr_box',this).show();
    			}, function() {
    				$('.attr_box',this).hide();
    		});		
    		});
    		
    	}
	});
})(jQuery);
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin
 		fixWidthul: function() {
			// Kiểm tra từng element và xử lý
			
    		widthfix	=	0;
    		//alert($(this).attr('id'));
				$('li',this).each(function(){
					widthfix += $(this).outerWidth();
					
				});
				
				$(this).css('width',widthfix);
			}
    		});
})(jQuery);
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin
 		fancyshow: function(options) {
 			var options = jQuery.extend({
				        count: 2,
				        speed: 200
				    },
				    options);
				    return this.each(function() {
				    	
						for (var i=0; i < options.count; i++) {
							$(this).fadeOut(options.speed).fadeIn('1');
		    		}
			})		
			
		}
    		});
})(jQuery);
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin
 		showContent: function(method,html,options) {
 			var options = jQuery.extend({
				        tittle: 'Cập nhật',
				        widthh:500,
				        heightt:500,
				        show:0,
				        speed:100,
				        close:'<span class="close_content_btn">close</span>',
				        method:'easeInCubic',
				        showclose:1
				    },
				    options);
				    if (options.showclose==1) {
				    	html_close='<p class="close_content"><span class="title">'+options.tittle+'</span>'+options.close+'</p>';
				    }
				    else{
				    	html_close='';
				    }
			htmlcontent = '<div class="dim_content"></div><div class="show_content">'+html_close+html+'</div>';
    		if (method	==	'add') {
    			$('body').append(htmlcontent);
    		
	    		
	    		if (options.show==1) {
	    			var top = (($(window).height() / 2) - (options.heightt / 2))+$(window).scrollTop();
					var left = (($(window).width() / 2) - (options.widthh / 2))+$(window).scrollLeft();
					var otop = $(this).offset().top;
					var oleft = $(this).offset().left;
	    			$('.show_content').css('left',oleft);
	    			$('.show_content').css('top',otop);
	    			$('.show_content').css('width',0);
	    			$('.show_content').css('width',0);
	    			$('.show_content').animate({width:options.widthh,
	    										height:options.heightt,
	    										top:top,
	    										left:left
	    											},options.speed,
  			 									options.method
   					 
  												  
  												  
										 	);
	    		}
	    		else
	    		{
	    			var top = (($(window).height() / 2) - ($('.show_content').outerHeight() / 2))+$(window).scrollTop();
					var left = (($(window).width() / 2) - ($('.show_content').outerWidth() / 2))+$(window).scrollLeft();
	    			$('.show_content').css('left',left);
	    			$('.show_content').css('top',top);
				}
    		}
    		else
    		{
    			$('body div.dim_content,body div.show_content').remove();	
    		}
			}
    		});
    		$('.close_content span.close_content_btn').live('click',function(){
    			$('body div.dim_content,body div.show_content').remove();
    			$('.ajax-content').parent().ajaxloading('remove');
    		});
    		$('.dim_content').live('click',function(){
    			$('body div.dim_content,body div.show_content').remove();
    			$('.ajax-content').parent().ajaxloading('remove');
    		});
    		
    		
    		//$('.show_content').css('width',);
})(jQuery);
(function($){
	// Thông báo phương thức tới jQuery
 	$.fn.extend({ 
 		
 		//Đây là phần bạn viết tên plugin
 		prickle: function(options,callback) {
 		//	debugger;
 			var options = jQuery.extend({
				        distance: 65,
				        speed: 80,
				        endp:3800,
				        beginp:70,
				        gh:0
				    },
				    options);
			
				  
				    var block=$(this);
				    block.stop();
				//alert($(this).css('background-position'))
				y=options.beginp;
					options.beginp+=options.distance;
				    if (options.beginp	> options.endp ) {
				    	//alert(options.begin)
				    	 if(typeof callback == 'function'){
				    	
    						callback();
  						  }
				    	return false;
					   };
	
				    	
				    	//count=options.imgw/options.distance;
				    	
				    	//alert(options.begin);
				    	x=options.beginp;
				    	value='-'+x+'px 0px';
				    	if(options.gh==1)
				    	{
				    	//console.log(options.beginp);
				 //   	debugger;
				    	}
				    //	console.log(value);
				    	//alert(value)
				    	$(this).css('background-position',value);	
				    	setTimeout(function() {return	block.prickle(options,callback)}, options.speed);
				   		
				    	
			
		}
    		});
})(jQuery);