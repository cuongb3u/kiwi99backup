
$(document).ready(function() {

//	openid.init('openid_identifier');
//	openid.setDemoMode(true); //Stops form submission for client javascript-only test purposes
//	var currenthref		=	window.location.href;
//		$('.flow-account ul a[href='+currenthref+']').addClass('current');
//		$('.flow-cms ul a[href='+currenthref+']').addClass('current');
	var web_url='www.kiwi99.com/'
	var nproduct = 1;
	
	//ajax login
	var isLogged = 1;
	
	$('#emaillogin').focus(function(){
		$('#emaillogin').validationEngine('hide');
		$('#emaillogin').validationEngine('attach',{showpromt: false});
	});
	
	$('#emaillogin').keyup(function(){
			$('#SubmitLogin').removeAttr('disabled');
			if(!$("#emaillogin").validationEngine('validateField', '#emaillogin'))
			{
				if (!$("#passwdlogin").validationEngine('validateField',"#passwdlogin")) {
				$('#SubmitLogin').addClass('SubmitLoginsucces');
				};
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};	
	});
	$('#emaillogin').blur(function(){
			$("#emaillogin").validationEngine('attach',{showpromt: true});
			$("#emaillogin").validationEngine('validateField', '#emaillogin');
			if(!$("#emaillogin").validationEngine('validateField', '#emaillogin'))
			{
				if (!$("#passwdlogin").validationEngine('validateField',"#passwdlogin")) {
				$('#SubmitLogin').addClass('SubmitLoginsucces');
				};
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};	
			
			
	});
	$('#passwdlogin').blur(function(){
			$("#passwdlogin").validationEngine('attach',{showpromt: true});
			if(!$("#passwdlogin").validationEngine('validateField', '#passwdlogin'))
			{
				if (!$("#emaillogin").validationEngine('validateField',"#emaillogin")) {
				$('#SubmitLogin').addClass('SubmitLoginsucces');
				};
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};	
			
	});
	$('#passwdlogin').focus(function(){
		$("#passwdlogin").validationEngine('hide');
		$("#passwdlogin").validationEngine('attach',{showpromt: false});
	});
	$('#passwdlogin').keyup(function(){
			$('#SubmitLogin').removeAttr('disabled');
		if(!$("#passwdlogin").validationEngine('validateField','#passwdlogin'))
		{
			if (!$("#emaillogin").validationEngine('validateField','#emaillogin')) {
			$('#SubmitLogin').addClass('SubmitLoginsucces');
		};
		}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};
		
		});
	/*
	$('#emaillogin').blur(function(){
		if($("#header_user_info #login_form").validationEngine('validate')){
			
			$('#SubmitLogin').addClass('SubmitLoginsucces');
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			}
		
	});
	$('#passwdlogin').blur(function(){
		if($("#header_user_info #login_form").validationEngine('validate')){
			$('#SubmitLogin').addClass('SubmitLoginsucces');
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			}
		
	});
	*/
	$('#SubmitLogin').live('click',function(){
					$("#header_user_info #login_form").validationEngine('attach',{showpromt: true});
					$('#SubmitLogin').removeClass('SubmitLoginsucces');
					$('#SubmitLogin').attr('disabled','disabled');
			if ($("#header_user_info #login_form").validationEngine('validate')) {
				$.ajax({
				  url: 'http://kiwi99.com/authentication.php',
				  type: 'GET',
				  dataType: 'json',	
				  data: "Submitajax=1&email="+$('#emaillogin').val()+"&passwd="+$('#passwdlogin').val(),
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
				});
				};
				  return false;
	});
	
	
	$('#log_out').live('click',function(){
				texts	=	'<a class="sign_up"';
			texts+=	'href="http://kiwi99.com/en/authentication.php?SubmitCreate=1&amp;back=my-account.php"> Tôi chưa có tài khoản?</a><div id="login_button"> <a class="login-button" href="http://kiwi99.com/en/my-account">Đăng nhập</a></div>';
			   $('.header_user_login').html(texts);
			   isLogged = 0;
			   if (true) {};
			   if ($('body').attr('id')=='my-account')
				{
				   $('.current').removeClass('current');
					texlogout='<p class="warninglog">Bạn chưa đăng nhập.</p>';
					 $('.main-content .data').html(texlogout);
					}; 
			
		$.ajax({
		  url: "http://kiwi99.com/my-account.php",
		  type: "GET",
		  dataType: "json",
		  data: "mylogout=1&ajaxLogout=1" ,
		
		  complete: function() {	
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
		                //alert("TECHNICAL ERROR: unable to refresh the cart.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
		            }
		        });	 
		         if ($('body').attr('id')=='order-opc')
						{
							 $('div.item').html('<p class="warning warning1">Your shopping cart is empty.</p>');
							 $('.right-cart').remove();
							  	
						 };	  
		        openlogin=false;  
		 },	
		  success: function(jsondata) {
		  		window.static_token = jsondata.token;
				
		  },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
		return false;
	});
	//end ajax login
	$('#email_newletter').blur(function() {
		$('#newletter_form').validationEngine({showpromt: true,promptPosition: "topLeft"});
		$("#newletter_form").validationEngine('validateField', "#email_newletter");
	});
	$('#email_newletter').focus(function(){
		$("#email_newletter").validationEngine('hide');
	});
	$('.button_newletter').click(function() {
		$('.formError').remove();
		$('#newletter_form').validationEngine({showpromt: true});
		if (!$('#newletter_form').validationEngine('validateField', "#email_newletter")) {
		var	email_newletter	= $('#email_newletter').val();
			$('.button_newletter').addClass('loadinginfo');
			$.ajax({
			  url: "http://kiwi99.com/thoitrang",
			  type: "POST",
			  dataType: "html",
			  data: 'action=0&submit=1&email=' + email_newletter ,
			
			  complete: function() {
			   $('.button_newletter').removeClass('loadinginfo');
			   setTimeout(function(){$('.formError').fadeOut()},'2000');
			  },
			
			  success: function(html) {
			    $('#email_newletter').validationEngine('showPrompt', html, 'load')
			 },
			
			  before: function() {
			  }
			});
			
			
		};
		return false;
	});
	$('#openLoginFormBlock').live('click',function() {
		if (!openlogin) {
			$('#login_button').addClass('menu-open');
			$('#overlay').addClass('overlay2');
			$('#header_user_info #login_form').fadeIn(500);
			$('.formErrorContent').css('opacity',1);
			$('.formErrorArrow').css('opacity',1);
			$('#header_user_info').addClass('zindex200');
			openlogin = true;
			$("#header_user_info #login_form").validationEngine('attach',{showpromt: false});
			if($("#header_user_info #login_form").validationEngine('validate'))
			{
			$('#SubmitLogin').addClass('SubmitLoginsucces');
			}
			else{
				$('#SubmitLogin').removeClass('SubmitLoginsucces');
			};
		}
		else{
			$('#login_button').removeClass('menu-open');
			$('#overlay').removeClass('overlay2');
			$('#header_user_info #login_form').hide();
			$('.formErrorContent').css('opacity',0);
			$('.formErrorArrow').css('opacity',0);
			$('#header_user_info').removeClass('zindex200');
			openlogin=false;
		};
	});
	$('.flow-account li h3 a,div.information-item ul li a').bind('click',function() {
		if (!isLogged) {
			$('.current').removeClass('current');
			texlogout='<p class="warninglog">Bạn chưa đăng nhập.</p>';
			 $('.main-content .data').html(texlogout);
			 return false;
		};
		$('div.formError').remove();
		var account_link = $(this).attr('href');
		$('.current').removeClass('current');
		$(this).addClass('current');
		$('.main-content .data').ajaxloading('add');
		$('.flow-account li h3 a[href='+account_link+']').addClass('current');
		
		$.ajax({
		  url: account_link,
		  type: "GET",
		  dataType: "html",
		  data: "ajaxload=1",
		
		  complete: function() {
		   
		  },
		
		  success: function(html) {
		  	 $('.main-content .data').ajaxloading('remove');
		   $('.main-content .data').html(html);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		return false;
	});
		$('form.std input').live('blur',function(){
		$(this).validationEngine('validateField',this);
	});
	$('form.std input').live('focus',function(){
		$(this).validationEngine('hide');
	});
	$('#old_passwd').live('focus',function(){
		$('input#old_passwd').validationEngine('validate');
		
		 $('input#old_passwd').validationEngine('hideAll');
	});
	$('#old_passwd').live('blur',function(){
		var	currentpawd =true;
		link=$(this).parents('form').attr('action');
		data=$(this).parents('form').serialize();
		data+='&checkPaswd=1&ajaxupdate=1';
		$.ajax({
		  url: link,
		  type: "POST",
		  dataType: "json",
		  data:data,
		
		  complete: function() {
		    //called when complete
		  },
		
		  success: function(json) {
		   if (!json) {
		   	currentpawd= false;
		   	 $('#old_passwd').validationEngine('showPrompt', 'Mật khẩu hiện tại không đúng', 'load');
		   }
		   else{
		   	currentpawd= true;
		   }
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
	});
	$('input#submitUpdateinfo').live('click',function(){
		if (($(this).parents('form').validationEngine('validate')) && (currentpawd= true)) {
			if ($('body').attr('id')=='order-opc'){
			$('.main-content .data').ajaxloading();};
		$('#header_user_info').ajaxloading();
			link=$(this).parents('form').attr('action');
			data=$(this).parents('form').serialize();
			var currentpasswd=$('#confirmation').val();
			data+='&ajaxupdate=1';
				$.ajax({
		  url: link,
		  type: "POST",
		  dataType: "json",
		  data:data,
		
		  complete: function() {
		  	$('input#old_passwd').val(currentpasswd);
		  	$('input#passwd').val('');
		  	$('input#confirmation').val('');
		  	fulname=$('input#firstname').val()+' '+$('input#lastname').val();
		  	$('.header_user_login a.user_name').text(fulname);
		  	$('p.success').fancyshow({count:2});
		  	
		  },
		
		  success: function(json) {
		   if (json==1) {
		  		$('#header_user_info').ajaxloading('remove');
		   	 $('div.identity-item').before('<p class="success">Your personal information has been successfully updated.</p>');
		   }
		   else{
		  		$('#header_user_info').ajaxloading('remove');
		   	$('div.identity-item').before('<p class="success">'+json+'</p>');
		   }
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		};
		return false;
	});
	$('div#add_address #submitAddress').live('click',function(){
		
		if ($(this).parents('form').validationEngine('validate'))
		 {
		 		
		 		$('.box-address').ajaxloading();
				link =	$(this).parents('form').attr('action');
				data =	$(this).parents('form').serialize();
				data += '&ajaxadd=1';
			
				$.ajax({
				  url: link,
				  type: "GET",
				  dataType: 'json',
				  data: data,
				
				  complete: function() {
				  	updateAddreslist();
				  	
				    $(this).parents('form').validationEngine('hide');
				    
				  },
				
				  success: function(json) {
				    if (json=='1') {
				    	jAlert('Thêm mới thành công', 'Alert Dialog');
				    	
				    }
				    else
				    {
				    	jAlert('Thêm mới thất bại', 'Alert Dialog');
				    }
				 },
				
				  error: function() {
				    //called when there is an error
				  }
				});
			
				 $('.btn_add_address').showContent('remove');
			};
			
		return false;
	});
	var objectul;
	$('li.address_update a').live('click',function(){
		link=$(this).attr('href');
		
		if ($('body').attr('id')=='order-opc'){
		$('.box-address').ajaxloading();}
		object=$(this);
		$.ajax({
		  url: link,
		  type: "GET",
		  dataType: "html",
		  data: 'ajaxload=1&ajaxladd=1',
		
		  complete: function() {
		  	
		  },
		
		  success: function(html) {
		  
		  	$('.box-address').ajaxloading('remove');
		  	 content='<div id="update_address">'+html+'</div>';
		    $(object).showContent('add',content);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
		return false;
	});
	$('div#update_address #submitAddress').live('click',function(){
		
		if ($(this).parents('form').validationEngine('validate'))
		 {
		 	if ($('body').attr('id')!='order-opc') {
		 		$(' span.address_name:first',objectul).text($('input#firstname').val());
		 		$(' span.address_name:last',objectul).text($('input#lastname').val());
		 		$(' span.address_address1',objectul).text($('input#address1').val());
		 		idcity=$('select#city').val();
		 		city=$('select#city option:[value='+idcity+']').text();
		 		$(' span.address_city',objectul).text(city);
		 		$(' span.address_phone',objectul).text($('input#phone').val());
		 		$(' li.address_title',objectul).text($('input#alias').val());
		 		
	 		}
	 		if ($('body').attr('id')=='order-opc') {
		 		$('.box-address').ajaxloading();
	 		}
				link =	$(this).parents('form').attr('action');
				data =	$(this).parents('form').serialize();
				data += '&ajaxupdate=1&submitAddress=1';
			
				$.ajax({
				  url: link,
				  type: "GET",
				  dataType: 'json',
				  data: data,
				
				  complete: function() {
				  	if ($('body').attr('id')=='order-opc') {
				  	updateAddreslist();
				  	}
				  	else{
				  	
				  	}
				  	
				    $(this).parents('form').validationEngine('hide');
				    
				  },
				
				  success: function(json) {
				    if (json=='1') {
				    	$('.ajax-content').parent().ajaxloading('remove');
				    	jAlert('Cập nhật thành công', 'Alert Dialog');
				    }
				    else
				    {
				    	$('.ajax-content').parent().ajaxloading('remove');
				    	jAlert('Cập nhật thất bại', 'Alert Dialog');
				    }
				 },
				
				  error: function() {
				    //called when there is an error
				  }
				});
			
				$('.btn_add_address').showContent('remove');
			};
		return false;
	});
	$('a.address_update').live('click',function(){
		link=$(this).attr('href');
		objectul = $(this).parents('ul.address_box');
		//	alert(objectul);return false;
		objectul.ajaxloading();
			
		objectcontent=$(this);
		
		$.ajax({
		  url: link,
		  type: "GET",
		  dataType: "html",
		  data: 'ajaxload=1&ajaxladd=1',
		
		  complete: function() {
		  	
		  },
		
		  success: function(html) {
		  	 content='<div id="update_address">'+html+'</div>';
		    $(objectcontent).showContent('add',content);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		
		return false;
	});
	$('a.address_delete').live('click',function(){
		link=$(this).attr('href');
		objectcontent=$(this).parents('ul');
		$.ajax({
		  url:link,
		  type: "GET",
		  dataType: "json",
			data:'delete=1',
		  complete: function() {
		  
		  },
		
		  success: function(json) {
		    if (json) {
		    	jAlert('Xóa địa chỉ thành công', 'Alert Dialog');
		    	  $(objectcontent).fadeOut('500');
		    	  setTimeout(function() {$(objectcontent).remove();}, '500');
		    };
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		return false;
		
	});
	$('.nav_sers li a').live('click',function(){
		link=$(this).attr('href');
		$('div.data').ajaxloading();
		$('.current').removeClass('current');
		$(this).addClass('current');
		$.ajax({
		  url: link,
		  type: "GET",
		  dataType: "html",
		  data: "ajaxload=1",
		
		  complete: function() {
		    //called when complete
		  },
		
		  success: function(html) {
		  	$('div.data').ajaxloading('remove');
		    $('div.data').html(html);
		 },
		
		  error: function() {
		    //called when there is an error
		  }
		});
		return false;
		
	});
		$('#view_mini_basket').hoverIntent(function(){					
					//$('#overlay').addClass('overlay');
						$('#cart_block').slideDown('fast');	
						$('#overlay').addClass('overlay1');
											
					},
					function(){
					
						$('#cart_block').hover(function() {
						
						}, function() {
							$('#overlay').removeClass('overlay1');
						$('#cart_block').slideUp('fast');
						$('#overlay').removeClass('overlay1');
						});
						
						
					}
			);	
			
			$('.overlay1').hover(function() {
				$('#cart_block').slideUp('fast');
				$('#overlay').removeClass('overlay1');
			}, function() {
				// Stuff to do when the mouse leaves the element;
			});;	
		$('.question-box').boxquestion();	
		//$('#phone').mask("(999) 999-9999? x99999");
		 	//$('#logo-prickle').prickle();
		 		var options = {
				        distance: 65,
				        speed: 80,
				        endp:2735,
				        beginp:70,
				        clear:0
				    };
				    var gstatus=0;
		 function callback () {
		 	if (gstatus) {
		 	
		 		
				 options = {
				        distance: 65,
				        speed: 80,
				        endp:2735,
				        beginp:2151,
				        clear:0
				    };			    
				    $('#logo-prickle').prickle(options,callback);
			 }	
			 else{
				$('#logo-prickle').stop().css('background-position','-2216px 0px');
				 options = {
				        distance: 65,
				        speed: 80,
				        endp:149580,
				        beginp:2216,
				        gh:1
					    };	
			 	  $('#logo-prickle').prickle(options);
			 }
	 	}
	 
		 	$('#logo-prickle').click(function() {
		 		
		 			if (gstatus) {
		 			//debugger;
		 			//	$(this).stop().css('background-position','-266px 0');
		 			//	$(this).css('background-position','-400px 0');
		 			gstatus=0;
		 			}else{
		 			gstatus=1; 			
		 			$(this).prickle(options,callback);
	 			}
	 			//console.log(gstatus);
		 	});
		 	$('#logo-goo').click(function() {
		 			 options = {
				        distance: 66.985,
				        speed: 80,
				        endp:32952,
				        beginp:400
				    };
		 			$(this).prickle(options);
		 	});
		 		$('#email').blur(function() {
		
		var email=$(this).val();
		if (!$('#email').validationEngine('validateField',"#email")) {
			link='authentication';
			
			$.ajax({
			  url: link,
			  type: "GET",
			  dataType: "html",
			  data:'checkemail=1&email='+email,
			
			  complete: function() {
			    $('.check_email').fancyshow({count:1,speed:200});
			  },
			
			  success: function(html) {
			   if (html!=1) {
			   	$('.check_email').hide();
			   		  	$('.check_email').removeClass('check_email');
			   		$('#error_email').addClass('check_email');
			   		$('#email').data('check',0);
			   }else
			   {
			   	$('#email').data('check',1)
			    	$('.check_email').hide();
			   	$('.check_email').removeClass('check_email');
			   		$('#success_email').addClass('check_email');
			   }
			 },
			
			  error: function() {
			    //called when there is an error
			  }
			});
			
			
			
		};
	});
	var month = [ '2', '4', '6', '9','11' ];
	$('#months').change(function() {
		value=$(this).val();
		
		if ($.inArray(value,month) != -1){
			if ($('#days option[value=31]').attr('selected')==1) {
				$('#days option[value=31]').removeAttr('selected');
				$('#days option[value=30]').attr('selected','selected');
				
			}
			$('#days option[value=31]').attr('disabled', 'disabled');
			if (value==2) {
					$('#days option[value=30]').attr('disabled', 'disabled');
					$('#days option[value=29]').attr('disabled', 'disabled');
					if ($('#days option[value=30]').attr('selected')==1) {
						$('#days option[value=29]').attr('selected','selected');
						};
					
				}
				else{
					$('#days option[value=30]').removeAttr('disabled');
					$('#days option[value=29]').removeAttr('disabled');
				}
			if((($('#years').val()%4==0&&$('#years').val()%100!=0)||($('#years').val()%400==0))&&value==2)
				{
				$('#days option[value=29]').removeAttr('disabled');
	
				}
				if ((($('#years').val()%4!=0&&$('#years').val()%100==0)||($('#years').val()%400!=0))&&value==2) {
					if ($('#days option[value=29]').attr('selected')==1) {
							$('#days option[value=28]').attr('selected','selected');
							$('#days option[value=29]').attr('disabled', 'disabled');
						}
				};
			
			
		}
		else{
			$('#days option[value=29]').removeAttr('disabled');		
			$('#days option[value=30]').removeAttr('disabled');
			$('#days option[value=31]').removeAttr('disabled');
		}
	});
	$('#years').change(function() {
		if((($('#years').val()%4==0&&$('#years').val()%100!=0)||($('#years').val()%400==0))&&($('#months').val()==2))
				{
					
					$('#days option[value=29]').removeAttr('disabled');
				}
				else{
					$('#days option[value=29]').attr('disabled', 'disabled');
				}
				if((($('#years').val()%4!=0&&$('#years').val()%100==0)||($('#years').val()%400!=0))&&($('#months').val()==2))
				{
					
						if ($('#days option[value=29]').attr('selected')==1) {
							$('#days option[value=28]').attr('selected','selected');
							$('#days option[value=29]').attr('disabled', 'disabled');
						}
				}
				
	});
	$('.view_large').live('click',function(){
		$('.jqzoom').click();
		return false;
	});	 		
	
});
