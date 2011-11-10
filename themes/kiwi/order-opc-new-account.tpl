<div id="opc_new_account" class="opc-main-block">
		<form action="{$link->getPageLink('authentication.php', true)}?back=order-opc.php" method="post" id="login_form" class="std">
		<div class="already-registered">
			 <a href="#login_button" id="openLoginFormBlock">Đăng nhập tại đây</a>      
		{*	<div id="login_form_content" style="display:none;">            	 
				<!-- Error return block -->
				<div id="opc_login_errors" class="error" style="display:none;"></div>
				<!-- END Error return block -->
				<div class="user-input clear">
					<label for="login_email">{l s='E-mail address'}:</label>
					<input type="text" id="login_email" name="email" />
				</div>
				<div class="user-input">
					<label for="login_passwd">{l s='Password'}:</label>
					<input type="password" id="login_passwd" name="passwd" />
				</div>
				<div class="user-input submit">
					{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
					<input type="submit" id="SubmitLogin" name="SubmitLogin" class="btn" value="{l s='Log in'}" />
                    <span class="lost_password"><a href="{$link->getPageLink('password.php', true)}">Quên mật khẩu?</a></span>
				</div>
				
			</div>
			*}
		</div>
	</form>
    <form action="#" method="post" id="new_account_form" class="std">
    	<input type="hidden" id="idLogin" value="0" />
    	 {include file="$tpl_dir./form-register.tpl" type=2} 
	</form>
    
	<div class="clear"></div>
</div>
