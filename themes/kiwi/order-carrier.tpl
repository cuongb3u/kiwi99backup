{if !$opc}
	<script type="text/javascript">
	//<![CDATA[
		var baseDir = '{$base_dir_ssl}';
		var orderProcess = 'order';
		var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
		var currencyRate = '{$currencyRate|floatval}';
		var currencyFormat = '{$currencyFormat|intval}';
		var currencyBlank = '{$currencyBlank|intval}';
		var txtProduct = "{l s='product'}";
		var txtProducts = "{l s='products'}";

		var msg = "{l s='You must agree to the terms of service before continuing.' js=1}";
		{literal}
		function acceptCGV()
		{
			if ($('#cgv').length && !$('input#cgv:checked').length)
			{
				alert(msg);
				return false;
			}
			else
				return true;
		}
		{/literal}
	//]]>
	</script>
{/if}
{if !$virtual_cart && isset($giftAllowed) && $cart->gift == 1}
	<script type="text/javascript" language="javascript">
    {literal}
    // <![CDATA[
        $('document').ready( function(){
            $('#gift_div').toggle('slow');
        });
    //]]>
    {/literal}
    </script>
{/if}
{if !$opc}
    {capture name=path}{l s='Shipping'}{/capture}
    {include file="$tpl_dir./breadcrumb.tpl"}
{/if}
<div class="box-delivery">
	{if !$opc}<h1>{l s='Shipping'}</h1>{else}<h2>Phương thức giao hàng</h2>{/if}   
	<div class="delivery-item">             
        {if !$opc}
            {assign var='current_step' value='shipping'}
            {include file="$tpl_dir./order-steps.tpl"}    
            {include file="$tpl_dir./errors.tpl"}
            <form id="form" action="{$link->getPageLink('order.php', true)}" method="post" onsubmit="return acceptCGV();">
        {else}
            <div id="opc_delivery_methods">
                <div id="opc_delivery_methods-overlay" class="opc-overlay" style="display: none !important;"></div>
        {/if}    
        {if isset($conditions) AND $cms_id}
        	<div class="delivery-items">
                <h3 class="condition_title">{l s='Terms of service'}</h3>
                <p class="checkbox">
                    <input type="checkbox" name="cgv" id="cgv" value="1" {if $checkedTOS}checked="checked"{/if} />
                    <label for="cgv">{l s='I agree to the terms of service and adhere to them unconditionally.'}</label> <a href="{$link_conditions}" class="iframe">{l s='(read)'}</a>
                </p>
           	</div>
            <script type="text/javascript">$('a.iframe').fancybox();</script>
        {/if}    
        {if $virtual_cart}
            <input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
        {else}
        	<div class="delivery-items">
            	<h3>Khu vực Hồ Chí Minh:</h3>
                <div>
                	<p><strong>Thời gian giao hàng:</strong> 1-3 ngày</p>
                    <p><strong>Phí giao hàng:</strong> 30.000 VNĐ</p>
                </div>
            </div>
            <div class="delivery-items">
            	<h3>Khu vực Miền Trung:</h3>
                <div>
                	<p><strong>Thời gian giao hàng:</strong> 1-3 ngày</p>
                    <p><strong>Phí giao hàng:</strong> 80.000 VNĐ</p>
                </div>
            </div>
        	{*
        	<div class="delivery-items">
                <h3 class="carrier_title">{l s='Choose your delivery method'}</h3>        
                <div id="HOOK_BEFORECARRIER">{if isset($carriers)}{$HOOK_BEFORECARRIER}{/if}</div>
                {if isset($isVirtualCart) && $isVirtualCart}
                    <p class="warning">{l s='No carrier needed for this order'}</p>
                {else}
                    {if $recyclablePackAllowed}
                        <p class="checkbox">
                            <input type="checkbox" name="recyclable" id="recyclable" value="1" {if $recyclable == 1}checked="checked"{/if} />
                            <label for="recyclable">{l s='I agree to receive my order in recycled packaging'}.</label>
                        </p>
                    {/if}
                    <p class="warning" id="noCarrierWarning" {if isset($carriers) && $carriers && count($carriers)}style="display:none;"{/if}>{l s='There are no carriers available that deliver to this address.'}</p>
                    <table id="carrierTable" cellpadding="0" cellspacing="0" {if !isset($carriers) || !$carriers || !count($carriers)}style="display:none;"{/if} border="1">		
                        <tr>
                            <th width="20">&nbsp;</th>
                            <th width="100">{l s='Carrier'}</th>
                            <th>{l s='Information'}</th>
                            <th width="200">{l s='Price'}</th>
                        </tr>
                        {if isset($carriers)}
                            {foreach from=$carriers item=carrier name=myLoop}
                                <tr>
                                    <td align="center">
                                        <input type="radio" name="id_carrier" value="{$carrier.id_carrier|intval}" id="id_carrier{$carrier.id_carrier|intval}"  {if $opc}onclick="updateCarrierSelectionAndGift();"{/if} {if !($carrier.is_module AND $opc AND !$isLogged)}{if $carrier.id_carrier == $checked || $carriers|@count == 1}checked="checked"{/if}{else}disabled="disabled"{/if} />
                                    </td>
                                    <td>
                                        <label for="id_carrier{$carrier.id_carrier|intval}">
                                            {if $carrier.img}<img src="{$carrier.img|escape:'htmlall':'UTF-8'}" alt="{$carrier.name|escape:'htmlall':'UTF-8'}" />{else}{$carrier.name|escape:'htmlall':'UTF-8'}{/if}
                                        </label>
                                    </td>
                                    <td>{$carrier.delay|escape:'htmlall':'UTF-8'}</td>
                                    <td>
                                        {if $carrier.price}
                                            <span class="price">
                                                {if $priceDisplay == 1}{convertPrice price=$carrier.price_tax_exc}{else}{convertPrice price=$carrier.price}{/if}
                                            </span>
                                            {if $use_taxes}{if $priceDisplay == 1} {l s='(tax excl.)'}{else} {l s='(tax incl.)'}{/if}{/if}
                                        {else}
                                            {l s='Free!'}
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                            {if $HOOK_EXTRACARRIER}
                            	<tr id="HOOK_EXTRACARRIER"><td colspan="4">{$HOOK_EXTRACARRIER}</td></tr>
                            {/if}
                        {/if}            
                    </table>
                    <div style="display: none;" id="extra_carrier"></div>        
                    {if isset($giftAllowed)}
                        <h3 class="gift_title">{l s='Gift'}</h3>
                        <p class="checkbox">
                            <input type="checkbox" name="gift" id="gift" value="1" {if $cart->gift == 1}checked="checked"{/if} onclick="$('#gift_div').toggle('slow');" />
                            <label for="gift">{l s='I would like the order to be gift-wrapped.'}</label>
                            <br />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {if $gift_wrapping_price > 0}
                                ({l s='Additional cost of'}
                                <span class="price" id="gift-price">
                                    {if $priceDisplay == 1}{convertPrice price=$total_wrapping_tax_exc_cost}{else}{convertPrice price=$total_wrapping_cost}{/if}
                                </span>
                                {if $use_taxes}{if $priceDisplay == 1} {l s='(tax excl.)'}{else} {l s='(tax incl.)'}{/if}{/if})
                            {/if}
                        </p>
                        <p id="gift_div" class="textarea">
                            <label for="gift_message">{l s='If you wish, you can add a note to the gift:'}</label>
                            <textarea rows="5" cols="35" id="gift_message" name="gift_message">{$cart->gift_message|escape:'htmlall':'UTF-8'}</textarea>
                        </p>
                    {/if}
                {/if}
          	</div>
            *}
        {/if}
        {if !$opc}
            <p class="cart_navigation submit">
                <input type="hidden" name="step" value="3" />
                <input type="hidden" name="back" value="{$back}" />
                <a href="{$link->getPageLink('order.php', true)}{if !$is_guest}?step=1{if $back}&back={$back}{/if}{/if}" title="{l s='Previous'}" class="button">&laquo; {l s='Previous'}</a>
                <input type="submit" name="processCarrier" value="{l s='Next'} &raquo;" class="exclusive" />
            </p>
        </form>
        {else}
        	<div class="delivery-items">
                <h3>Ghi chú:</h3>
                <div>
                    <p>Nếu bạn có một vài lưu ý khi giao hàng thì hãy điền nội dung vào ô bên dưới:</p>
                    <p><textarea  name="message" id="message">{if isset($oldMessage)}{$oldMessage}{/if}</textarea></p>
                </div>
            </div>
            
        </div>
        {/if}
  	</div>
</div>
{if $opc}
    <div class="process-step">
        <div class="next"><input type="button" value="Tiếp tục" onclick="javascript:chooseStep(2);"/></div>
    </div>
{/if}
<script type="text/javascript" language="javascript">
	/*$('.box-delivery h2').corner("top");	*/
	$('.delivery-item').corner("br");		
</script>