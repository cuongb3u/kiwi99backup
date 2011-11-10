$(document).ready(function() {
	function fixwidth(uldata){
		widthfix	=	0;
		$(uldata).children('li').each(function(){
			widthfix += $(this).outerWidth();
		})
		$(uldata).css('width',widthfix);
	}
			$('#thumbs_list_frame').fixWidthul();
	var width_truec =0;
	$('#trueContainer li').each(function() {
		width_truec += $(this).outerWidth();
	});
	$('#trueContainer').css('width',width_truec);
	$('#motioncontainer').motionGallery({
			startpos: 0, 
			maxspeed: 25,
			maxwidth: 470,
			restarea: 0
			
		});
	$('#productscategory_list li a,#trueContainer li a').live('click',function() {
		var account_link = $(this).attr('href');
		$('.main-content').ajaxloading('add');
		$.ajax({
		  url: account_link,
		  type: "GET",
		  dataType: "html",
		  data: "ajaxload=1",
		
		  complete: function() {
			$('#thumbs_list_frame').fixWidthul();
		    fixwidth('#trueContainer');
		    $('#views_block li a').hover(
				function(){displayImage($(this));},
				function(){}
			);
		    $('#motioncontainer').motionGallery({
				startpos: 0, 
				maxspeed: 25,
				maxwidth: 470,
				restarea: 0
			
			});
			
					$('img.jqzoom').jqueryzoom({
					xzoom: 360, //zooming div default width(default width value is 200)
					yzoom: 450, //zooming div default width(default height value is 200)
					offset: 21 //zooming div default offset(default offset value is 10)
					//position: "right" //zooming div position(default position value is "right")
				});
				$('.thickbox').fancybox({
					'hideOnContentClick': true,
					'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic'
				});
				$('#fancybox-content')
				.mouseenter(function(){

				})
					.live("mousemove",function(e) {

						galleryImageHeight = $('#fancybox-img').height();						
						
						galleryImageWrap = $("#fancybox-content");
						galleryImageWrapHeight = galleryImageWrap.height();
						galleryImageWrapOffset = $('#fancybox-wrap').offset().top;
					
						var posY = (Math.round(( (e.pageY - galleryImageWrapOffset)/galleryImageWrapHeight)*100)/100)  * (galleryImageHeight-galleryImageWrapHeight);
						
						
						if(posY + galleryImageWrapHeight > galleryImageHeight)
								posY =	galleryImageHeight - 	galleryImageWrapHeight;			
						
						//IE makes it necessary to check that galleryImage exists...
						if ($("#fancybox-img")) $('#fancybox-img').css({'margin-top': -posY});
					});
		     $('#thumbs_list').serialScroll({
		        items:'li:visible',
		        prev:'a#view_scroll_left',
		        next:'a#view_scroll_right',
		        axis:'x',
		        offset:0,
		        start:0,
		        stop:true,
		        onBefore:serialScrollFixLock,
		        duration:700,
		        step: 2,
		        lazy: true,
		        lock: false,
		        force:false,
		        cycle:false
		    });
		    $('#idTab1').addClass('product-info');
		     $('#idTab2').addClass('block_hidden_only_for_screen');
		     $('a[href="#idTab1"]').addClass('selected');
		  },
		
		  success: function(html) {
		  	$('.main-content').ajaxloading('remove');
		  $('.main-content').html(html);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		return false;
	});
	$('#more_info_tabs > li').live('click',function(){
		$('.selected').removeClass('selected');
		$('a',this).addClass('selected');
		 $('div#more_info_sheets > div').addClass('block_hidden_only_for_screen');
		 block	= $(' a',this).attr('href');
		 $(block).removeClass('block_hidden_only_for_screen');
		 
		return false;
		
	});
	 $('body#product p#add_to_cart input').live('click',function(){
            ajaxCart.add( $('#product_page_product_id').val(), $('#idCombination').val(), true, null,$('#quantity_wanted').val(), null);
            return false;
        });
		  $('ul#color_to_pick_list a').live('click',function(){
        	$('#thumbs_list_frame').fixWidthul();
        });
         $('ul#size_to_pick_list a').live('click',function(){
        	$('#thumbs_list_frame').fixWidthul();
        });
});
