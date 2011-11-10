<!-- Block user information module HEADER -->
<div class="account" id="header_user">		
    <ul id="header_user_info">
    	{if $cookie->isLogged()}        	
	            <li class="first header_user_login" >                
	                {*l s='Welcome' mod='blockuserinfo'*}Xin chào,&nbsp;              
	              <a href="{$link->getPageLink('my-account.php', true)}" class ="user_name" title="{l s='Your Account' mod='blockuserinfo'}">  {$cookie->customer_firstname} {*$cookie->customer_lastname*}</a>
	                <a href="{$link->getPageLink('index.php')}index.php?mylogout=1" id="log_out" title="{l s='Log me out' mod='blockuserinfo'}">{*l s='Log out' mod='blockuserinfo'*}&nbsp;|&nbsp;Thoát</a>
	                   
        {else}
     		   <li class="header_user_login">                
              <a class="sign_up" href="{$link->getPageLink('authentication.php?SubmitCreate=1&amp;back=my-account.php', true)}">Tôi chưa có tài khoản?{*l s='Have an account? |' mod='blockuserinfo'*}</a> 
              
                
        {*	<li class="first"><a href="{$link->getPageLink('authentication.php?SubmitCreate=1&amp;back=my-account.php', true)}">Đăng ký</a></li> *}
          <div id="login_button"> <a class="login-button" href="{$link->getPageLink('my-account.php', true)}">Đăng nhập</a></div>
            	
            	  {/if}</li>
            	  <li>
            	  	<div id="login_form" >
            	 		<div id="popup_container_2">
							<div id="popup_content_2">
								<h4 style="float:left; margin: 10px 10px;">Please choose your login services</h4>
								<ul style="clear:both">
									<li  id="kiwi" class="popup_providers" onclick="shopMessage(1)">
									<a href="javascript:void(0)"  >
										</a>		</li>
										<li id="popup_GG" class="popup_providers" onclick="openPopup('{$link->getPageLink('providers.php?popupGG', true)}',1)" >
									<a href=""  >
										</a>		</li>
										<li onclick="openPopup('{$link->getPageLink('providers.php?popupFB', true)}',2)" id="popup_FB" class="popup_providers">
									<a href="" >
										</a>	</li>
										
										<li onclick="openPopup('{$link->getPageLink('providers.php?popupYH', true)}',3)" id="popup_YH" class="popup_providers">
									<a href=""  >
										</a>		</li>	
								</ul>	
							</div>
						</div>		
						<div class="login_message " id="FB_message">You are login via FB</div>
						<div class="login_message" id="GG_message">You are login via GG</div>
						<div class="login_message" id="YH_message">You are login via YH</div> 	        	  	
			                <form class="std login_message" id="login_form_kiwi">
								<fieldset>
										<a id="close_login_form">x</a>
									<p class="errors_display"></p>
									<table class="text">
										<tr>
											<td><label for="email">{l s='E-mail:'}</label></td>
											<td><p><input class="validate[required,custom[email]] account_input" type="text" id="emaillogin" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall':'UTF-8'|stripslashes}{/if}"/></p></td>	
										</tr>	
										<tr>
											<td><label for="passwd">Mật khẩu:{*l s='Password:'*}</label></td>
											<td><p><input type="password" id="passwdlogin" class="validate[required] minSize[6] account_input" name="passwd" value="{if isset($smarty.post.passwd)}{$smarty.post.passwd|escape:'htmlall':'UTF-8'|stripslashes}{/if}"/></p></td>				
										</tr>
									</table>
									<p class="submit">
										{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
										<input type="submit" id="SubmitLogin" name="SubmitLogin" class="button" value="Đăng nhập{*l s='Log in'*}" />
									</p>
									<p class="lost_password" style="text-align:center;"><a href="{$link->getPageLink('password.php')}">*Quên mật khẩu?{*l s='Forgot your password?'*}</a></p>
								
								</fieldset>
								
							</form>
							<div id="login_form_bottom">
							</div>	
            		</div>
              </li>
        
    </ul>
    {if !$PS_CATALOG_MODE}
        <div class="shopping-item" id="shopping_cart">
        	<div id="header_nav">
                <div id="view_mini_basket">
                	<strong><a href="{$link->getPageLink("$order_process.php", true)}" title="{l s='Your Shopping Cart' mod='blockuserinfo'}" id="idShoppingBag" class="{if $cart_qties > 0} have_bag{/if}"></a></strong> 
                    <span class="ajax_cart_quantity{if $cart_qties == 0} hidden{/if}" style="{if $cart_qties == 0}display:none{/if}">{$cart_qties}</span> <span class="ajax_cart_product_txt{if $cart_qties != 1} hidden{/if}" style="{if $cart_qties != 1}display:none{/if}">{l s='sản phẩm' mod='blockuserinfo'}</span><span class="ajax_cart_product_txt_s{if $cart_qties < 2} hidden{/if}" style="{if $cart_qties < 2}display:none{/if}">{l s='sản phẩm' mod='blockuserinfo'}</span> - 
                    {if $cart_qties >= 0}
                        <span class="ajax_cart_total{if $cart_qties == 0} hidden{/if}" style="{if $cart_qties ==0}display:none{/if}">
                            {if $priceDisplay == 1}
                                {assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}
                                {convertPrice price=$cart->getOrderTotal(false, $blockuser_cart_flag)}
                            {else}
                                {assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}
                                {convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}
                            {/if}
                        </span>
                   	{/if}
              		<span class="ajax_cart_no_product{if $cart_qties > 0} hidden{/if}">{l s='(trống)' mod='blockuserinfo'}</span>
              	</div>
            </div>
        </div>
    {/if}
</div>
<!-- /Block user information module HEADER -->
