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
*  @version  Release: $Revision: 6599 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	var baseDir = '{$base_dir_ssl}';
//]]>
</script>

{capture name=path}<a href="{$link->getPageLink('my-account.php', true)}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Order history'}{/capture}
{if !isset($ajaxload)}

<div class="main-content">
	<!-- LEFT MENU -->
	
    {include file="$tpl_dir./flow-account.tpl" intCurrent=5}
    <!-- END LEFT MENU -->
    <!-- DATA -->
    <div class="data">
    	{/if}
    	<div class="ajax-content">
    	{include file="$tpl_dir./breadcrumb.tpl"}
    	<div class="box-history">
        	<div class="header-title"><h3>{l s='Order history'}</h3></div>
        	<div class="history-item">            
                {include file="$tpl_dir./errors.tpl"}
                <p>{l s='Here are the orders you have placed since the creation of your account'}.</p>
                
                {if $slowValidation}<p class="warning">{l s='If you have just placed an order, it may take a few minutes for it to be validated. Please refresh the page if your order is missing.'}</p>{/if}                
                <div class="block-center" id="block-history">
                    {if $orders && count($orders)}
                        <table id="order-list" class="std" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{l s='Order'}</th>
                                    <th>{l s='Date'}</th>
                                    <th>{l s='Total price'}</th>
                                    <th>{l s='Payment'}</th>
                                    <th>{l s='Status'}</th>
                                    <th>{l s='Invoice'}</th>
                                    <th width="65">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach from=$orders item=order name=myLoop}
                                <tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
                                    <td class="history_link bold">
                                        {if isset($order.invoice) && $order.invoice && isset($order.virtual) && $order.virtual}<img src="{$smarty.const._THEME_DIR_}images/icon/download_product.gif" class="icon" alt="{l s='Products to download'}" title="{l s='Products to download'}" />{/if}
                                        <a class="color-myaccount" href="javascript:showOrder(1, {$order.id_order|intval}, 'order-detail');">{l s='#'}{$order.id_order|string_format:"%06d"}</a>
                                    </td>
                                    <td class="history_date bold">{dateFormat date=$order.date_add full=0}</td>
                                    <td class="history_price"><span class="price">{displayPrice price=$order.total_paid_real currency=$order.id_currency no_utf8=false convert=false}</span></td>
                                    <td class="history_method">{$order.payment|escape:'htmlall':'UTF-8'}</td>
                                    <td class="history_state">{if isset($order.order_state)}{$order.order_state|escape:'htmlall':'UTF-8'}{/if}</td>
                                    <td class="history_invoice">
                                    {if (isset($order.invoice) && $order.invoice && isset($order.invoice_number) && $order.invoice_number) && isset($invoiceAllowed) && $invoiceAllowed == true}
                                        <a href="{$link->getPageLink('pdf-invoice.php', true)}?id_order={$order.id_order|intval}" title="{l s='Invoice'}"><img src="{$smarty.const._THEME_DIR_}images/icon/pdf.gif" alt="{l s='Invoice'}" class="icon" /></a>
                                        <a href="{$link->getPageLink('pdf-invoice.php', true)}?id_order={$order.id_order|intval}" title="{l s='Invoice'}">{l s='PDF'}</a>
                                    {else}-{/if}
                                    </td>
                                    <td class="history_detail">
                                        <a class="color-myaccount" href="javascript:showOrder(1, {$order.id_order|intval}, 'order-detail');">{l s='details'}</a>
                                        <a href="{if isset($opc) && $opc}{$link->getPageLink('order-opc.php', true)}{else}{$link->getPageLink('order.php', true)}{/if}?submitReorder&id_order={$order.id_order|intval}" title="{l s='Reorder'}">
                                            <img src="{$smarty.const._THEME_DIR_}images/bk/arrow_rotate_anticlockwise.png" alt="{l s='Reorder'}" title="{l s='Reorder'}" class="icon" />
                                        </a>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    	{*<div id="block-order-detail" class="hidden">&nbsp;</div>*}
                    {else}
                        <p class="warning">{l s='You have not placed any orders.'}</p>
                    {/if}
                </div>                
           	</div>
       	</div>
 		</div>
  	{if !isset($ajaxload)}
  	 	</div>
</div>
{/if}
