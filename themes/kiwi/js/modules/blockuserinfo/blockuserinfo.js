

var openlogin=false;

	$('#login_button').live('click',function() {
		if (!openlogin) {
			$('#login_button').addClass('menu-open');
			$('#overlay').addClass('overlay2');
			$('#header_user_info #login_form').fadeIn(200);
			$('.formErrorContent').css('opacity',1);
			$('.formErrorArrow').css('opacity',1);
			$('#header_user_info').addClass('zindex200');
			openlogin = true;
			$("#header_user_info #login_form").validationEngine({showpromt: false});
			if($("#header_user_info #login_form").validationEngine('validate'))
			{
			$('#SubmitLogin').addClass('SubmitLoginsucces');
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};
		}
		else{
			$('#kiwi').removeClass('kiwi_click');
			$('#login_form').removeClass('login_form_click');
			$('#login_form_bottom').css('display','block');
			$('.login_message').hide();
			$('#login_button').removeClass('menu-open');
			$('#overlay').removeClass('overlay2');
			$('#header_user_info #login_form').hide();
			$('.formError').remove();
			$('#header_user_info').removeClass('zindex200');
			openlogin=false;
		};
		return false;
	

	});

$('#overlay').live('click',function() {
		$('#kiwi').removeClass('kiwi_click');
		$('#login_form').removeClass('login_form_click');
		$('#login_form_bottom').css('display','block');
		$('.login_message').hide();
		$('#overlay').removeClass('overlay2');
		$('#header_user_info #login_form').hide();
		$('#login_button').removeClass('menu-open');
		$('.formError').remove();		
		$('#header_user_info').removeClass('zindex200');
		openlogin=false;	
});
		
$(document).ready(function(){
	$('.login_message').hide();
	$('#popup_FB').mousedown(function(){
		$(this).addClass('popup_FB_click');	
	});
	$('#popup_FB').mouseup(function(){
		$(this).removeClass('popup_FB_click');	
	});
	$('#popup_GG').mousedown(function(){
		$(this).addClass('popup_GG_click');	
	});
	$('#popup_GG').mouseup(function(){
		$(this).removeClass('popup_GG_click');	
	});
	$('#popup_YH').mousedown(function(){
		$(this).addClass('popup_YH_click');	
	});
	$('#popup_YH').mouseup(function(){
		$(this).removeClass('popup_YH_click');	
	});
	$('#kiwi').click(function(){
		$('#kiwi').addClass('kiwi_click');
		$('#login_form').addClass('login_form_click');
		$('#login_form_bottom').css('display','none');
	});	
})
function shopMessage(){
	$('.login_message').hide();
	$('#login_form_kiwi').fadeIn(500);
}
function openPopup(link,num){
		$('#kiwi').removeClass('kiwi_click');
		$('#login_form').addClass('login_form_click');
		$('#login_form_bottom').css('display','none');
		//$('#login_form').removeClass('login_form_click');
		$('.login_message').hide();
		$('.formError').remove();
		if(num == '1'){
			$('#GG_message').fadeIn(200);
		}
		if(num == '2'){
			$('#FB_message').fadeIn(200);
		}
		if(num == '3'){
			$('#YH_message').fadeIn(200);
		}		
					
		var _width = 580;
		var Xpos = ((screen.availWidth - _width) / 4);
		var _height = 620;
		var Ypos = ((screen.availHeight - _height) / 2);	
						    												
		window.popup_window = window.open(link,"",'width=' + _width + ',height=' + _height + ',toolbar=no,resizable=fixed,status=no,scrollbars=yes,menubar=no,screenX=' + Xpos + ',screenY=' + Ypos);
		/*
		$.ajax({
				  url: 'http://kiwi99.com/providers.php',
				  type: 'GET',
				  dataType: 'json',	
				  data: 'popupGG',
				  /*
				  success: function(jsondata) {
				    if (!jsondata.success) {
				    	$('.errors_display').html(jsondata.errors[0]);
				    	$('.errors_display').fancyshow({count:2,speed:200});
				    }
				    else{
				    	 $('.header_user_login').html(jsondata.html);
				    	 $('.errors_display').html('');
				    	 $('#overlay').removeClass('overlay2');
						$('#header_user_info #login_form').fadeOut(500);
						$('#header_user_info').removeClass('zindex200');
						window.static_token = jsondata.token;
						window.token =jsondata.token;
					
						
				    };
				    
				 },
				
				  complete: function() {
				  	isLogged=1;
				  	$('#SubmitLogin').removeAttr('disabled');
				  	  if (!$('body').attr('id')=='order-opc')
						{
						    $.ajax({
						            type: 'GET',
						            url: baseDir + 'cart.php',
						            async: true,
						            cache: false,
						            dataType : "json",
						            data: 'ajax=true&token=' + static_token,
						            success: function(jsonData)
						            {
						                ajaxCart.updateCart(jsonData);
						            },
						            error: function(XMLHttpRequest, textStatus, errorThrown) {
						            }
						        });	 
				         };
				        if ((!$('div.item p.warning1').text())&&($('body').attr('id')=='order-opc'))
						{
							  updateNewAccountToAddressBlock();
							  	
						 };	  
						  
				       
				  }
				  
				});*/
}
