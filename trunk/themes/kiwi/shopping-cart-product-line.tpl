<li id="product_{$product.id_product}_{$product.id_product_attribute}" class="{if $smarty.foreach.productLoop.last}last_item{elseif $smarty.foreach.productLoop.first}first_item{/if}{if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0}alternate_item{/if} cart_item">
	<div>
    	<div class="thumb">
			<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.pai_id_image, '60x109')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" width="60" height="109" border="0" /></a>
        </div>
	</div>
	<div class="product_att_cart">
		<h5><a class="product_name" href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h5>
		{if isset($product.attributes) && $product.attributes}<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.attributes|escape:'htmlall':'UTF-8'|replace:', ':'<br/>'}</a>{/if}
		 {if isset($product.id_manufacturer)}
                          					  <p class="product_brand" > <img src="../img/m/{$product.id_manufacturer}.jpg"  border="0"/></p>
                       		 		   {/if}
	</div>
	<div {if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0} {/if}>
		{if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0}<span id="cart_quantity_custom_{$product.id_product}_{$product.id_product_attribute}" >{$product.customizationQuantityTotal}</span>{/if}
	</div>
	{*<div>
		<span class="price" id="product_price_{$product.id_product}_{$product.id_product_attribute}">
			{if !$priceDisplay}{convertPrice price=$product.price_wt}{else}{convertPrice price=$product.price}{/if}
		</span>
	</div>	*}
	<div>
		<span class="price" id="total_product_price_{$product.id_product}_{$product.id_product_attribute}">
			{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}
				{if !$priceDisplay}{displayPrice price=$product.total_customization_wt}{else}{displayPrice price=$product.total_customization}{/if}
			{else}
				{if !$priceDisplay}{displayPrice price=$product.total_wt}{else}{displayPrice price=$product.total}{/if}
			{/if}
		</span>
		</div>
			<div style="width:54px;float:right;">
				{if !isset($customizedDatas.$productId.$productAttributeId) OR $quantityDisplayed > 0}
			<div id="cart_quantity_button" style="float:left; margin-top:-2px; padding-top: 4px;">
				<a rel="nofollow" class="cart_quantity_up" id="cart_quantity_up_{$product.id_product}_{$product.id_product_attribute}" href="{$link->getPageLink('cart.php', true)}?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;token={$token_cart}" title="{l s='Add'}"><img src="{$smarty.const._THEME_DIR_}images/icon/quantity_up.png" alt="{l s='Add'}" border="0"/></a>
                {if $product.minimal_quantity < ($product.cart_quantity-$quantityDisplayed) OR $product.minimal_quantity <= 1}
                    <a rel="nofollow" class="cart_quantity_down" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}" href="{$link->getPageLink('cart.php', true)}?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;op=down&amp;token={$token_cart}" title="{l s='Subtract'}">
                        <img src="{$smarty.const._THEME_DIR_}images/icon/quantity_down.png" alt="{l s='Subtract'}"  border="0"/>
                    </a>
                {else}
                    <a class="cart_quantity_down" style="opacity: 0.3;" href="#" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}" title="{l s='You must purchase a minimum of '}{$product.minimal_quantity}{l s=' of this product.'}">
                        <img src="{$smarty.const._THEME_DIR_}images/icon/quantity_down.png"  alt="{l s='Subtract'}" border="0" />
                    </a>
                {/if}
			
			<input type="hidden" value="{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}" name="quantity_{$product.id_product}_{$product.id_product_attribute}_hidden" />
			<input size="2" type="text" class="cart_quantity_input" value="{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}"  name="quantity_{$product.id_product}_{$product.id_product_attribute}" />
			</div>
		{/if}
				<a rel="nofollow" class="cart_quantity_delete" id="{$product.id_product}_{$product.id_product_attribute}" href="{$link->getPageLink('cart.php', true)}?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;token={$token_cart}" title="{l s='Delete'}">Remove</a>
				
			</div>
	
</li>
