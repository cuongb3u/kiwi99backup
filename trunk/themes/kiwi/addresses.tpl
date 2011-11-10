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
*  @version  Release: $Revision: 6664 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	var baseDir = '{$base_dir_ssl}';
	{literal}
	$(document).ready(function()
	{
			resizeAddressesBox();
	});
	{/literal}
//]]>
</script>

{capture name=path}<a href="{$link->getPageLink('my-account.php', true)}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My addresses'}{/capture}
{if !isset($ajaxload)}
    	<!--div class="menu"><div class="brand-top"><img src="../img/my-account.png"	alt="My account" /></div></div-->
        <div class="main-content">        
    <!-- LEFT MENU -->    
    {include file="$tpl_dir./flow-account.tpl" intCurrent=4}
    <!-- END LEFT MENU -->
{/if}
    <!-- DATA -->
    <div class="data">
   		{include file="$tpl_dir./breadcrumb.tpl"}
       	<div class="box-addresses">            
            <div class="header-title"><h3>{l s='My addresses'}</h3></div>
            <div class="addresses-item">
                <p>{l s='Please configure the desired billing and delivery addresses to be preselected when placing an order. You may also add additional addresses, useful for sending gifts or receiving your order at the office.'}</p>
                
                {if isset($multipleAddresses) && $multipleAddresses}
                    <div class="addresses-content">
                        <h4>{l s='Your addresses are listed below.'}</h4>
                        <p>{l s='Be sure to update them if they have changed.'}</p>
                        {assign var="adrs_style" value=$addresses_style}
                        {foreach from=$multipleAddresses item=address name=myLoop}
                            <ul class="address_box" >                            	
                                <li class="address_title">{$address.object.alias}</li>
                                {foreach from=$address.ordered name=adr_loop item=pattern}
                                    {assign var=addressKey value=" "|explode:$pattern} 
                                        {if $pattern != 'company' && $pattern != 'vat_number' && $pattern != 'address2'}                      
                                            <li>
                                                <span class="first-item"> 
                                                    {if $pattern == 'firstname'}Họ tên: 
                                                    {elseif $pattern == 'address1'}Địa chỉ: 
                                                    {elseif $pattern == 'city'}Tỉnh/thành: 
                                                    {elseif $pattern == 'country'}Quốc gia:
                                                    {elseif $pattern == 'phone'}Điện thoại:{/if}
                                                </span>
                                                <span class="last-item">
                                                    {foreach from=$addressKey item=key name="word_loop"}      
                                                        {if $key=='address1'}
                                                            {strip}
                                                                {$address.formated[$key]|escape:'htmlall':'UTF-8'}
                                                                {if $address.formated['district'] > 0}
                                                                ,&nbsp;{$address.formated['district']|districtname|escape:'htmlall':'UTF-8'}
                                                                {/if}
                                                            {/strip}
                                                        {elseif $key=='city'}
                                                            {$address.formated[$key]|provincename|escape:'htmlall':'UTF-8'}
                                                        {elseif $key != 'district'}
                                                            {$address.formated[$key]|escape:'htmlall':'UTF-8'}
                                                        {/if}
                                                    {/foreach}
                                                </span>
                                            </li>
                                        {/if}
                                {/foreach}
                                {if isset($address.object.id)}
                                    <li><a href="{$link->getPageLink('address.php', true)}?id_address={$address.object.id|intval}&amp;back=addresses.php" title="{l s='Update'}" class="address_update">Cập nhật</a></li>
                                    <li><a href="{$link->getPageLink('address.php', true)}?id_address={$address.object.id|intval}&amp;delete" onclick="return confirm('{l s='Are you sure?'}');" title="{l s='Delete'}"  class="address_delete">Xóa</a></li>
                                {/if}
                            </ul>
                        {/foreach}
                    </div>
                {else}
                    <p class="warning">{l s='No addresses available.'}&nbsp;<a href="{$link->getPageLink('address.php', true)}">{l s='Add new address'}</a></p>
                {/if}        
                <ul class="footer_links">
                     <li><a href="{$link->getPageLink('address.php', true)}?back=addresses.php" title="{l s='Add an address'}" class="button_large">Thêm mới địa chỉ giao nhận hàng</a></li>
                   {* <li><a href="{$link->getPageLink('my-account.php', true)}"><img src="{$smarty.const._THEME_DIR_}images/icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account.php', true)}">{l s='Back to Your Account'}</a></li>
                    <li><a href="{$base_dir}"><img src="{$smarty.const._THEME_DIR_}images/icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home'}</a></li>*}
                </ul>
            </div>
        </div>
    </div>
{if !isset($ajaxload)}
	</div>
{/if}