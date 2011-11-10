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
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if !$opc}
	<script type="text/javascript">
	// <![CDATA[
	var baseDir = '{$base_dir_ssl}';
	var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
	var currencyRate = '{$currencyRate|floatval}';
	var currencyFormat = '{$currencyFormat|intval}';
	var currencyBlank = '{$currencyBlank|intval}';
	var txtProduct = "{l s='product'}";
	var txtProducts = "{l s='products'}";
	// ]]>
	</script>

	{capture name=path}{l s='Your payment method'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}
{/if}
<div class="box-payment-method">
	{if !$opc}<h1>Vui lòng chọn phương thức thanh toán phù hợp để thanh toán đơn hàng.</h1>{else}<h2>Vui lòng chọn phương thức thanh toán phù hợp để thanh toán đơn hàng.</h2>{/if}
	<div class="payment-method-item">
        {if !$opc}
            {assign var='current_step' value='payment'}
            {include file="$tpl_dir./order-steps.tpl"}
            {include file="$tpl_dir./errors.tpl"}
        {else}
            <div id="opc_payment_methods" class="opc-main-block">
                <div id="opc_payment_methods-overlay" class="opc-overlay" style="display: none;"></div>
        {/if}    
                    
                    <div id="HOOK_TOP_PAYMENT">{$HOOK_TOP_PAYMENT}</div>    
                    
        {if $HOOK_PAYMENT}
        	<form id="hookModulePayment" name="hookModulePayment" method="post">
                {if !$opc}<h4>{l s='Please select your preferred payment method to pay the amount of'}&nbsp;<span class="price">{convertPrice price=$total_price}</span> {if $taxes_enabled}{l s='(tax incl.)'}{/if}</h4><br />{/if}
                {if $opc}<div id="opc_payment_methods-content">{/if}
                    <div id="HOOK_PAYMENT">{$HOOK_PAYMENT}</div>
                {if $opc}</div>{/if}
          	</form>
            
        {else}
            <p class="warning">{l s='No payment modules have been installed.'}</p>
        {/if}
        
        {if !$opc}
            <p class="cart_navigation"><a href="{$link->getPageLink('order.php', true)}?step=2" title="{l s='Previous'}" class="button">&laquo; {l s='Previous'}</a></p>
        {else}        	
            </div>
        {/if}
   	</div>
</div>
{if $opc}
    <div id="showMethodPayment" class="box-method-payment-of-user"></div>
{/if}
{if $opc}
	<div class="process-step">
        <div class="back"><input type="button" value="Quay về" onclick="javascript:chooseStep(1);" /></div>
        <div class="next"><input type="button" onclick="javascript:validatorMethodPayment();" value="Tiếp tục"></div>
    </div>
{/if}
<script type="text/javascript" language="javascript">	
	$(document).ready(function(){	
		$('.payment-method-item').corner("br");				
	});
	function validatorMethodPayment() {
		var intType = $("input[name='paymentModule']:checked").val();
		if (!intType) {
			jAlert('Vui lòng chọn phương thức thanh toán', 'Chú ý');
			return false;
		};		
		var strLink	= $("#paymentModule"+ intType +"Url").val();		
		$("#hookModulePayment").attr("action", strLink);
		$("#hookModulePayment").submit();
		
	}
	function showHideOrderSummary(intType) {
		$("#showMethodPayment").hide();
		$('#showMethodPayment').html($("#paymentModule"+ intType +"Ext").html()).show();
	}
</script>
