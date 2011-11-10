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

<!-- Block Newsletter module-->


	<div class="block_content">
		<h4>{l s='Đăng ký email để nhận khuyến mãi.' mod='blocknewsletter'}</h4>
	{if isset($msg) && $msg}
		<p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
	{/if}
		<form id="newletter_form" action="http://kiwi99.com/thoitrang" method="post">
			<fieldset>
			<input type="text" name="email" id="email_newletter" class="validate[custom[email]]" size="18" value="{if isset($value) && $value}{$value}{else}{l s='E-mail của bạn...' mod='blocknewsletter'}{/if}" onfocus="javascript:if(this.value=='{l s='E-mail của bạn...' mod='blocknewsletter'}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='E-mail của bạn...' mod='blocknewsletter'}';" />
			<input type="submit" value="OK" class="button_newletter" name="submitNewsletter" />
				</fieldset>
				<select name="action" style="visibility:hidden;">
					<option value="0"{if isset($action) && $action == 0} selected="selected"{/if}>{l s='Subscribe' mod='blocknewsletter'}</option>
					<option value="1"{if isset($action) && $action == 1} selected="selected"{/if}>{l s='Unsubscribe' mod='blocknewsletter'}</option>
				</select>
			</p>
		</form>
	</div>

<!-- /Block Newsletter module-->
