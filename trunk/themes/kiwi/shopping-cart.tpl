{assign var='current_step' value='summary'}
{include file="$tpl_dir./order-steps.tpl"}
{include file="$tpl_dir./errors.tpl"}
{if isset($empty)}
	<p class="warning1">{l s='Your shopping cart is empty.'}</p>
{elseif $PS_CATALOG_MODE}
	<p class="warning">{l s='This store has not accepted your new order.'}</p>
{else}
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
    <div id="order-detail-content" class="table_block">
        <div id="cart_summary" class="list-item" cellpadding="0" cellspacing="0" border="0">
            <div>
               <p class="cart-tittle">Giỏ hàng của bạn : {$products|@count}  sản phẩm
               	</p>
            </div>		        
            <div>
            	{if $products}
            		<ul class="product_list_cart">
                    {foreach from=$products item=product name=productLoop}
                        {assign var='productId' value=$product.id_product}
                        {assign var='productAttributeId' value=$product.id_product_attribute}
                        {assign var='quantityDisplayed' value=0}
                        {* Display the product line *}
                        {include file="$tpl_dir./shopping-cart-product-line.tpl"}
                        {* Then the customized datas ones*}
                        {if isset($customizedDatas.$productId.$productAttributeId)}
                            {foreach from=$customizedDatas.$productId.$productAttributeId key='id_customization' item='customization'}
                                <ul id="product_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" class="alternate_item cart_item">
                                    <li>
                                        {foreach from=$customization.datas key='type' item='datas'}
                                            {if $type == $CUSTOMIZE_FILE}
                                                <div class="customizationUploaded">
                                                    <ul class="customizationUploaded">
                                                        {foreach from=$datas item='picture'}<li><img src="{$pic_dir}{$picture.value}_60x109" alt="" class="customizationUploaded" border="0" /></li>{/foreach}
                                                    </ul>
                                                </div>
                                            {elseif $type == $CUSTOMIZE_TEXTFIELD}
                                                <ul class="typedText">
                                                    {foreach from=$datas item='textField' name='typedText'}<li>{if $textField.name}{$textField.name}{else}{l s='Text #'}{$smarty.foreach.typedText.index+1}{/if}{l s=':'} {$textField.value}</li>{/foreach}
                                                </ul>
                                            {/if}
                                        {/foreach}
                                    </li>
                                    <li class="cart_quantity">
                                        <div style="float:right">
                                            <a rel="nofollow" class="cart_quantity_delete" id="{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" href="{$link->getPageLink('cart.php', true)}?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" title="{l s='Delete this customization'}" width="11" height="13" class="icon" /></a>
                                        </div>
                                        <div id="cart_quantity_button" style="float:left">
                                        <a rel="nofollow" class="cart_quantity_up" id="cart_quantity_up_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" href="{$link->getPageLink('cart.php', true)}?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}" title="{l s='Add'}"><img src="{$img_dir}icon/quantity_up.gif" alt="{l s='Add'}" width="14" height="9" /></a><br />
                                        {if $product.minimal_quantity < ($customization.quantity -$quantityDisplayed) OR $product.minimal_quantity <= 1}
                                        <a rel="nofollow" class="cart_quantity_down" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" href="{$link->getPageLink('cart.php', true)}?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}" title="{l s='Subtract'}">
                                            <img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Subtract'}" width="14" height="9" />
                                        </a>
                                        {else}
                                        <a class="cart_quantity_down" style="opacity: 0.3;" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" href="#" title="{l s='Subtract'}">
                                            <img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Subtract'}" width="14" height="9" />
                                        </a>
                                        {/if}
                                       
                                        <input type="hidden" value="{$customization.quantity}" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_hidden"/>
                                        <input size="2" type="text" value="{$customization.quantity}" class="cart_quantity_input" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}"/>
                                         </div>
                                    </li>
                                    <li class="cart_total"></li>
                                </ul>
                                {assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
                            {/foreach}
                            {* If it exists also some uncustomized products *}
                            {if $product.quantity-$quantityDisplayed > 0}{include file="$tpl_dir./shopping-cart-product-line.tpl"}{/if}
                        {/if}
                    {/foreach}
            		</ul>
                {else}
                    <p>{l s='Your shopping cart is empty.'}</p>
                {/if}     
						<div id="enteredVouchers">
							{include file="$tpl_dir./enteredVouchers.tpl"}
						</div>
        </div>
    </div>
    </div>
    <div class="order-cart-content">
    {if $products}
    	{include file="$tpl_dir./voucher.tpl"}
    		<div class="shipping">
        	{include file="$tpl_dir./shipping.tpl"}
				</div>
		
        {if !$opc}    
            <div class="proceed-to-checkout">
                <ul>
                    <li>
                   		<a href="{$link->getPageLink('order.php', true)}?step=1{if $back}&amp;back={$back}{/if}" class="exclusive" title="{l s='Next'}"><img class="checkout" src="{$smarty.const._THEME_DIR_}images/btn/proceed-to-checkout.gif" alt="" border="0" /></a>                        
                    </li>
                    <li><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="shopping-continue" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></li>
                </ul>
            </div>
       	{else}
        	{*<div class="proceed-to-checkout">
                <ul>
                    <li>
                   		<a href="javascript:chooseStep(2);"><img class="checkout" src="{$smarty.const._THEME_DIR_}images/btn/proceed-to-checkout.gif" alt="" border="0" /></a>                        
                    </li>
                    <li><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="shopping-continue" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></li>
                </ul>
            </div>
            *}
        {/if}
  	{/if}
<div id="HOOK_SHOPPING_CART">{$HOOK_SHOPPING_CART}</div>
{if (($carrier->id AND !isset($virtualCart)) OR $delivery->id OR $invoice->id) AND !$opc}
    <div class="order_delivery">
        {if $delivery->id}
        <ul id="delivery_address" class="address item">
            <li class="address_title">{l s='Delivery address'}</li>
            {if $delivery->company}<li class="address_company">{$delivery->company|escape:'htmlall':'UTF-8'}</li>{/if}
            <li class="address_name">{$delivery->firstname|escape:'htmlall':'UTF-8'} {$delivery->lastname|escape:'htmlall':'UTF-8'}</li>
            <li class="address_address1">{$delivery->address1|escape:'htmlall':'UTF-8'}</li>
            {if $delivery->address2}<li class="address_address2">{$delivery->address2|escape:'htmlall':'UTF-8'}</li>{/if}
            <li class="address_city">{$delivery->postcode|escape:'htmlall':'UTF-8'} {$delivery->city|escape:'htmlall':'UTF-8'}</li>
            <li class="address_country">{$delivery->country|escape:'htmlall':'UTF-8'} {if $delivery_state}({$delivery_state|escape:'htmlall':'UTF-8'}){/if}</li>
        </ul>
        {/if}
        {if $invoice->id}
        <ul id="invoice_address" class="address alternate_item">
            <li class="address_title">{l s='Invoice address'}</li>
            {if $invoice->company}<li class="address_company">{$invoice->company|escape:'htmlall':'UTF-8'}</li>{/if}
            <li class="address_name">{$invoice->firstname|escape:'htmlall':'UTF-8'} {$invoice->lastname|escape:'htmlall':'UTF-8'}</li>
            <li class="address_address1">{$invoice->address1|escape:'htmlall':'UTF-8'}</li>
            {if $invoice->address2}<li class="address_address2">{$invoice->address2|escape:'htmlall':'UTF-8'}</li>{/if}
            <li class="address_city">{$invoice->postcode|escape:'htmlall':'UTF-8'} {$invoice->city|escape:'htmlall':'UTF-8'}</li>
            <li class="address_country">{$invoice->country|escape:'htmlall':'UTF-8'} {if $invoice_state}({$invoice_state|escape:'htmlall':'UTF-8'}){/if}</li>
        </ul>
        {/if}
        {if $carrier->id AND !isset($virtualCart)}
        <div id="order_carrier">
            <h4>{l s='Carrier:'}</h4>
            {if isset($carrierPicture)}<img src="{$img_ship_dir}{$carrier->id}.jpg" alt="{l s='Carrier'}" />{/if}
            <span>{$carrier->name|escape:'htmlall':'UTF-8'}</span>
        </div>
        {/if}
    </div>
{/if}
{if (($carrier->id AND !isset($virtualCart)) OR $delivery->id OR $invoice->id) AND !$opc}
    <p class="cart_navigation">
        {if !$opc}<a href="{$link->getPageLink('order.php', true)}?step=1{if $back}&amp;back={$back}{/if}" class="exclusive" title="{l s='Next'}">{l s='Next'} &raquo;</a>{/if}
    </p>
{/if}
<p class="cart_navigation_extra">
	<span id="HOOK_SHOPPING_CART_EXTRA">{$HOOK_SHOPPING_CART_EXTRA}</span>
</p>
{/if}
</div>

