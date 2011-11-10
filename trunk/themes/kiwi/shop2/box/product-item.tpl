<div class="pro-thumb">
	

	<span class="quick_view" link="{$product.link|escape:'htmlall':'UTF-8'}">Xem nhanh</span>
    <a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img class="mainpic" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} border="0"/></a>

{if isset($product.combinationImages) && $product.combinationImages}

	<div id="color-images" class="colorimages">
		<ul id="thumbs_list_frame" class="thumbs_list_color">
			{foreach from=$product.combinationImages key=ipa item=color_info}
			
			<li id="thumbnail_{$color_info.id_attribute}" style="display: list-item; width: 20px;">
	<a id_color="{$color_info.id_attribute}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="circle"  rel="other-views" href="{$link->getImageLink($product.link_rewrite, $color_info.id_image, 'home')}">
	<img width="20" height="20" alt="{$product.name|escape:'htmlall':'UTF-8'}" src="{$img_ps_dir}a/{$color_info.id_product_attribute}-att.jpg" id="thumb_{$color_info.id_attribute}">
	</a>
	</li>
			{/foreach}


														</ul>
		</div>
{/if}

    <h4><a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|truncate:22:'...'|escape:'htmlall':'UTF-8'}</a></h4>
    <h5>                            	            	
        {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
            {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}{if $priceDisplay}{convertPrice price=$product.price}{else}{if $product.reduction != 0}<span style="text-decoration:line-through;">{convertPrice price=$product.price_without_reduction}</span> {/if}{if isset($product.orderprice) && $product.orderprice}{convertPrice price=$product.orderprice}{else}{convertPrice price=$product.price_tax_exc}{/if}{/if}{/if}
        {/if}
    </h5>
    <h6>
        <a href="#">{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}{if ($product.allow_oosp || $product.quantity > 0)}{l s=''}{else}{l s='(hết hàng)'}{/if}{/if}</a>
    </h6>    
</div>