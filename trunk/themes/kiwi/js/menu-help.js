// $(document).ready(function() {
// 	 
// 	//ACCORDION BUTTON ACTION (ON CLICK DO THE FOLLOWING)
// /*		function accordionButtonover() {
// 
// 		//REMOVE THE ON CLASS FROM ALL BUTTONS
// 		$(this).addClass('on');
// 		  
// 		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
// 	 	$('.accordionContent',this).slideDown(500);
//    
// 		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
// 		 }; 
// 	function accordionButtonout(){
// 		 	$('.accordionContent',this).slideUp(500);
// 		 	$(this).removeClass('on');
// 		 }
// 		  
// 	
// 	 var config = {
//         sensitivity : 50,        // number = sensitivity threshold (must be 1 or higher)
//         interval    : 350,      // number = milliseconds for onMouseOver polling interval
//         over        : accordionButtonover, // function = onMouseOver callback (REQUIRED)
//         timeout     : 0,      // number = milliseconds delay before onMouseOut
//         out         : accordionButtonout, // function = onMouseOut callback (REQUIRED)
// 		img_block   : ".accordionContent",
// 		timefirst    :100
// 		
//      };
//     $(".accordionButton").hoverDelay(config);
// 
// */
// 	   $(".accordionButton").hoverIntent (function() {
// 
// 		//REMOVE THE ON CLASS FROM ALL BUTTONS
// 		$(this).addClass('on-help');
// 		  
// 		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
// 	 	$('.accordionContent',this).slideDown('normal');
//    
// 		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
// 		 } ,	function(){
// 		 	$('.accordionContent',this).slideUp('normal');
// 		 	$(this).removeClass('on-help');
// 		 });
// 
// 	
// 	/*** REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
// 	
// 	//ADDS THE .OVER CLASS FROM THE STYLESHEET ON MOUSEOVER 
// 		
// 	//ON MOUSEOUT REMOVE THE OVER CLASS
// 	
// 	/*** END REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
// 	
// 	
// 	/********************************************************************************************************************
// 	CLOSES ALL S ON PAGE LOAD
// 	********************************************************************************************************************/	
// 	$('.accordionContent').hide();
// 
// });
$(document).ready(function() {
	$('#select_product').change(function() {
		$('div.block_product').hide();
		$('div.block_product').removeClass('active');
		block=$("option:selected",this).attr('block');
		$('#product_help div[block_product='+block+']').fadeIn('slow');
		$('#product_help div[block_product='+block+']').addClass('active');
	});
$('select[name="id_order"]').live('change',function() {
		select=$(this).val();
		$('#select_product').val(0);
		$('#product_help .active').removeClass('active').fadeOut();
		if ($(this).val()==0) {
			$('#select_product option').removeAttr('disabled');
			return false;
		};
		$('#select_product option').attr('disabled','disabled');
		$('#select_product option[id_order='+select+']').removeAttr('disabled');
	});
	$('#submitMessage').live('click',function() {
		if ($('select#id_contact').val()==0) {
			jAlert('Vui lòng chọn dịch vụ.', 'Thông báo');
			return false;
		};
		if (!$('form.std').validationEngine('validate')) {
			return false;
		};
		if ($('select[name=id_order]').val()==0) {
			jAlert('Vui lòng chọn đơn hàng.', 'Thông báo');
			return false;
		};
		if ($('#select_product').val()==0) {
			jAlert('Vui lòng chọn sản phẩm.', 'Thông báo');
			return false;
		};
		if (!$('div#idNeedHelp1 textarea#message').val()) {
			jAlert('Vui lòng nhập ý kiến của bạn.', 'Thông báo');
			return false;
		};
		$(this).hide();
		subject ='';
		subject+='<p style="margin: 3px 10px;line-height:1.4em">Reference sản phẩm : '+$('#product_help .active').attr('reference_product')+'</p>'; 
		subject+='<p style="margin: 3px 10px;line-height:1.4em">Tên sản phẩm : '+$('.active a.product_help_name').text()+'</p>';
		$('.active .product_detail  li').each(function() {
			subject+='<p style="margin: 3px 10px;;line-height:1.4em">'+$(this).text()+'</p>'; 
		}); 
		subject+='<p style="margin: 3px 10px;margin-top:20px;line-height:1.4em">Lời nhắn : ';
		subject+=$('div#idNeedHelp1 textarea#message').val()+'</p>';
		data=$('#idNeedHelp1 form.std').serialize();
		data+='&submitMessage=1&subject='+subject;
		link=$(this).parents('form').attr('action');
		$(this).parents('.on-help').ajaxloading('add',{content:$(this).parents('#idNeedHelp1'),blurcontent:$(this).parents('#idNeedHelp1')});
		$.ajax({
		  url: link,
		  type: "GET",
		  dataType: "html",
		 data: data,
		
		  complete: function() {
		    $('li.on-help').ajaxloading('remove');
		   $('div#idNeedHelp1 textarea#message').val('');
		   $('select[name=id_order]').val(0);
		   $('#select_product option').removeAttr('disabled');
		   $('#select_product').val(0);
		   $('select#id_contact').val(0);
		   $('div.active').hide().removeClass('active');
		   $('#submitMessage').show();
		  },
		
		  success: function(html) {
		    if (html) {
		    	jAlert('Ý kiến của bạn đã được gửi.', 'Thông báo');
		    }
		    else
		    	jAlert('Gửi ý kiến thất bại.', 'Thông báo');
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
		return false;
	});
});
