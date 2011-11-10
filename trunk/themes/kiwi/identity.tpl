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
*  @author PrestaShop SA 
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6599 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	var baseDir = '{$base_dir_ssl}';
//]]>
</script>
	{if !isset($ajaxload)}
	<div class="menu"><div class="brand-top"><img src="../img/my-account.png"	alt="My account" /></div></div>
<div class="main-content">
	
	<!-- LEFT MENU -->

    {include file="$tpl_dir./flow-account.tpl" intCurrent=2}
   
    <!-- END LEFT MENU -->
    <!-- DATA -->
    <div class="data">  
    	 {/if}      
    	 <div class="ajax-content">
        {capture name=path}<a href="{$link->getPageLink('my-account.php', true)}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Your personal information'}{/capture}
        {include file="$tpl_dir./breadcrumb.tpl"}
		<div class="box-identity">
        	<div class="header-title"><h3>{l s='Your personal information'}</h3></div>
            <div class="identity-item">
            	{include file="$tpl_dir./errors.tpl"}            
                {if isset($confirmation) && $confirmation}
                    <p class="success">
                        {l s='Your personal information has been successfully updated.'}
                        {if isset($pwd_changed)}<br />{l s='Your password has been sent to your e-mail:'} {$email|escape:'htmlall':'UTF-8'}{/if}
                    </p>
                {else}
                    <h4>{l s='Please do not hesitate to update your personal information if it has changed.'}</h4>
                    <div class="user-input required"><sup>*</sup>{l s='Required field'}</div>
                    <form action="{$link->getPageLink('identity.php', true)}" method="post" class="std">
                            <div class="user-input">
                                <label>{l s='Xưng danh'}:</label>
                                <div class="radio">
                                	<input type="radio" id="id_gender1" name="id_gender" value="1" {if $smarty.post.id_gender == 1 OR !$smarty.post.id_gender}checked="checked"{/if} class="radio" />
                                	<label class="radio" for="id_gender1">{l s='Anh'}</label>
                             	</div>
                                <div class="radio">
                                    <input type="radio" id="id_gender2" name="id_gender" value="2" {if $smarty.post.id_gender == 2}checked="checked"{/if} class="radio" />
                                    <label class="radio" for="id_gender2">{l s='Chị'}</label>
                               	</div>
                            </div>
                            <div class="user-input text">
                                <label for="firstname">{l s='Họ & tên'} <span class="required">*</span>:</label>
                                <input type="text" id="firstname" class="validate[required,custom[onlyLetterSp]]" name="firstname" value="{$smarty.post.firstname}" />
                            </div>
                            {*
                            <div class="user-input text">
                                <label for="lastname">{l s='Last name'} <span class="required">*</span>:</label>
                                <input type="text"  class="validate[required,custom[onlyLetterSp]]"  name="lastname" id="lastname" value="{$smarty.post.lastname}" />
                            </div>
                            *}
                            <div class="user-input text">
                                <label for="email">{l s='E-mail'} <span class="required">*</span>:</label>
                                <input type="text"  class="validate[required,custom[email]]"  name="email" id="email" value="{$smarty.post.email}" />
                            </div>
                            <div class="user-input text">
                                <label for="old_passwd">{l s='Mật khẩu cũ'} <span class="required">*</span>:</label>
                                <input type="password" class="validate[required] minSize[5]" name="old_passwd" id="old_passwd" />
                            </div>
                            <div class="user-input password">
                                <label for="passwd">{l s='Mật khẩu mới'}:</label>
                                <input type="password"  name="passwd" id="passwd" />
                            </div>
                            <div class="user-input password">
                                <label for="confirmation">{l s='Xác nhận mật khẩu mới'}:</label>
                                <input type="password" class="validate[equals[passwd]]" name="confirmation" id="confirmation" />
                            </div>
                            <div class="user-input select">
                                <label>{l s='Ngày sinh'}:</label>
                                <select name="days" id="days">
                                    <option value="">-</option>
                                    {foreach from=$days item=v}
                                        <option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_day == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                                    {/foreach}
                                </select>
                                {*
                                    {l s='January'}
                                    {l s='February'}
                                    {l s='March'}
                                    {l s='April'}
                                    {l s='May'}
                                    {l s='June'}
                                    {l s='July'}
                                    {l s='August'}
                                    {l s='September'}
                                    {l s='October'}
                                    {l s='November'}
                                    {l s='December'}
                                *}
                                <select id="months" name="months">
                                    <option value="">-</option>
                                    {foreach from=$months key=k item=v}
                                        <option value="{$k|escape:'htmlall':'UTF-8'}" {if ($sl_month == $k)}selected="selected"{/if}>{l s="$v"}&nbsp;</option>
                                    {/foreach}
                                </select>
                                <select id="years" name="years">
                                    <option value="">-</option>
                                    {foreach from=$years item=v}
                                        <option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_year == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                                    {/foreach}
                                </select>
                            </div>
                            {if $newsletter}
                            <div class="user-input">
                                <input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if} class="chk"/>
                                <label for="newsletter" class="chk">{l s='Đăng ký nhận tin mới từ kiwi99.com'}</label>
                            </div>
                            <div class="user-input">
                                <input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if} class="chk" />
                                <label for="optin" class="chk">{l s='Receive special offers from our partners'}</label>
                            </div>
                            {/if}
                            <div class="user-input submit">
                                <input type="submit" name="submitIdentity" value="{l s='Save'}" class="btn" id="submitUpdateinfo"/>
                            </div>
                        
                    </form>
                    <p id="security_informations">
                        {l s='[Insert customer data privacy clause or law here, if applicable]'}
                    </p>
                {/if}                
          	</div>
           {* <ul class="footer_links">
                    <li><a href="{$link->getPageLink('my-account.php', true)}"><img src="{$smarty.const._THEME_DIR_}images/icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account.php', true)}">{l s='Back to Your Account'}</a></li>
                    <li><a href="{$base_dir}"><img src="{$smarty.const._THEME_DIR_}images/icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home'}</a></li>
                </ul>*}
      	</div>
      	</div>
      	{if !isset($ajaxload)}
  	</div>
  	</div>
  	{/if}

<script type="text/javascript" language="javascript">
	$('.identity-item').corner();		
</script>

