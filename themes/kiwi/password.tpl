{*
* 2007-2011 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- MAIN CONTENT WEBSITE -->
<div class="main-content">
    <!-- LEFT MENU -->
    {include file="$tpl_dir./flow-account.tpl" intCurrent=1}
    <!-- END LEFT MENU -->
    <!-- DATA -->
    <div class="data">
    	{capture name=path}{l s='Forgot your password'}{/capture}
        {include file="$tpl_dir./breadcrumb.tpl"}
    	<div class="box-password">                	
            <div class="header-title"><h3>{l s='Forgot your password'}</h3></div>
            <div class="password-item">         
        		{include file="$tpl_dir./errors.tpl"}        
                {if isset($confirmation) && $confirmation == 1}
                <p class="success">{l s='Your password has been successfully reset and has been sent to your e-mail address:'} {$email|escape:'htmlall':'UTF-8'}</p>
                {elseif isset($confirmation) && $confirmation == 2}
                <p class="success">{l s='A confirmation e-mail has been sent to your address:'} {$email|escape:'htmlall':'UTF-8'}</p>
                {else}
                <p>{l s='Please enter the e-mail address used to register. We will e-mail you your new password.'}</p>
                <form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std">
                    <div class="user-input">
                    	<label for="email">{l s='E-mail:'}</label>
                            <input type="text" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall':'UTF-8'|stripslashes}{/if}" />                    
                    </div>
                    <div class="user-input">
                    	<input type="submit" class="button" value="Lấy lại mật khẩu" />
                    </div>
                </form>
                {/if}
                <p class="clear">
                    <a href="{$link->getPageLink('authentication.php', true)}" title="{l s='Return to Login'}"><img src="{$img_dir}icon/my-account.gif" alt="{l s='Return to Login'}" class="icon" /></a><a href="{$link->getPageLink('authentication.php')}" title="{l s='Back to Login'}">{l s='Back to Login'}</a>
                </p>
          	</div>
       	</div>
  	</div>
</div>
