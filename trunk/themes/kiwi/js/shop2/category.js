$(document).ready(function() {
	function fixwidth(uldata){
		widthfix	=	0;
		$(uldata).children('li').each(function(){
			widthfix += $(this).outerWidth();
		})
		$(uldata).css('width',widthfix);
	}
	$('.pro-thumb').live({
	 mouseover: function() {
    $('.quick_view',this).show();
 	 },
 	 mouseout: function() {
     $('.quick_view',this).hide();
  }
	});
	var picture;
	var firstajax=1;
	$('.quick_view').live('click',function(){
		that=$(this);
		title=$(this).siblings('a').attr('title');
		picture=$(this).siblings('a').find('.mainpic');
		if (that.data('html')) {
				var option={
			
				        tittle: title,
				        widthh:720,
				        heightt:600,
				        show:1,
				        speed:100
					};
		$(this).showContent('add','<div id="content_product" style="width:100%;height:100%;" />',option);
			$('#content_product').html(that.data('html'));
			 $('#views_block li a').hover(
				function(){displayImage($(this));},
				function(){}
			);		
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
		     
		 //    alert(color);
		    $('#color_to_pick_list a[id_attribute='+color+']').click();
		     $('.show_content #thumbs_list_frame').fixWidthul();
			//alert(that.data('html'));
			return false;
		};
		data='ajaxload=1&quick_view=1';
		link=$(this).siblings('a').attr('href');
		nparent=$(this).parent('li');
		//parent.ajaxloading();
		title=$(this).siblings('a').attr('title');
		picture=$(this).siblings('a').find('.mainpic');
		
		var option={
			
				        tittle: title,
				        widthh:720,
				        heightt:600,
				        show:1,
				        speed:100
					};
		$(this).showContent('add','<div id="content_product" style="width:100%;height:100%;" />',option);
		$('#content_product').ajaxloading();
		if (firstajax) {
			data+='&first=1';
			firstajax=0;
		};
		//return false;
		color=$(this).siblings('div#color-images').find('a.shown').attr('id_color');
		//color=$('ul li a.shown',colorparent).attr('id_color');

	
		$.ajax({
		  url: link	,
		  type: "GET",
		  dataType: "html",
		  data: data,
		
		  complete: function() {
		  
		    $('#views_block li a').hover(
				function(){displayImage($(this));},
				function(){}
			);		
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
		     
		 //    alert(color);
		    $('#color_to_pick_list a[id_attribute='+color+']').click();
		$('.show_content').find('#thumbs_list_frame').fixWidthul();
		    
		  },
		
		  success: function(html) {
		  	$('#content_product').ajaxloading('remove');
		    $('#content_product').html(html);
		    //alert(1);
		    that.data('html',html);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
		
		
	});
	$('#more_info_tabs > li').live('click',function(){
		$('.selected').removeClass('selected');
		$('a',this).addClass('selected');
		 $('div#more_info_sheets > div').addClass('block_hidden_only_for_screen');
		 block	= $(' a',this).attr('href');
		 $(block).removeClass('block_hidden_only_for_screen');
		 
		return false;
		
	});
		 $('p#add_to_cart input').live('click',function(){
           
		 	product_page_product_id_tmp = $('#product_page_product_id').val();
		 	idCombination_tmp = $('#idCombination').val();
		 	quantity_wanted_tmp=$('#quantity_wanted').val();
		 	html_temp=$('#image-block').html();
		 	$('body').showContent('remove');
          // $('#image-block').html();
          	$('body').showContent('add',html_temp,{showclose:0});
            $('.show_content').css({'width':picture.width() ,'height':picture.height()});
            $('.show_content').animate({'width':picture.width(),'height':picture.height(),'top':picture.offset().top,'left':picture.offset().left,'opacity':0.7}, 300,function(){
            	$('body').showContent('remove');
            	
            });
          ajaxCart.add( product_page_product_id_tmp, idCombination_tmp, true, null,quantity_wanted_tmp, null,picture);
           //$(window).scrollTop(0);

        
           // $('body').scrollTo( '#view_mini_baskets', 1200 );
            return false;
        });
		  $('ul#color_to_pick_list a').live('click',function(){
        	$('#thumbs_list_frame').fixWidthul();
        });
         $('ul#size_to_pick_list a').live('click',function(){
        	$('#thumbs_list_frame').fixWidthul();
        });
        	$('#more_info_tabs > li').live('click',function(){
        		//alert('adw');
		$('.selected').removeClass('selected');
		$('a',this).addClass('selected');
		 $('div#more_info_sheets > div').addClass('block_hidden_only_for_screen');
		 block	= $('a',this).attr('hreff');
		
		 $(block).removeClass('block_hidden_only_for_screen');
		 
		return false;
		
	});
});
