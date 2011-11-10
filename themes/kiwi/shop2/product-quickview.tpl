<!-- MAIN CONTENT WEBSITE -->
<script type="text/javascript">
// <![CDATA[

var ipaColor = {$ipaColor};

// PrestaShop internal settings
var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
var currencyRate = '{$currencyRate|floatval}';
var currencyFormat = '{$currencyFormat|intval}';
var currencyBlank = '{$currencyBlank|intval}';
var taxRate = {$tax_rate|floatval};
var jqZoomEnabled = {if $jqZoomEnabled}true{else}false{/if};

//JS Hook
var oosHookJsCodeFunctions = new Array();

// Parameters
var id_product = '{$product->id|intval}';
var productHasAttributes = {if isset($groups)}true{else}false{/if};
var quantitiesDisplayAllowed = {if $display_qties == 1}true{else}false{/if};
var quantityAvailable = {if $display_qties == 1 && $product->quantity}{$product->quantity}{else}0{/if};
var allowBuyWhenOutOfStock = {if $allow_oosp == 1}true{else}false{/if};
var availableNowValue = '{$product->available_now|escape:'quotes':'UTF-8'}';
var availableLaterValue = '{$product->available_later|escape:'quotes':'UTF-8'}';
var productPriceTaxExcluded = {$product->getPriceWithoutReduct(true)|default:'null'} - {$product->ecotax};
var reduction_percent = {if $product->specificPrice AND $product->specificPrice.reduction AND $product->specificPrice.reduction_type == 'percentage'}{$product->specificPrice.reduction*100}{else}0{/if};
var reduction_price = {if $product->specificPrice AND $product->specificPrice.reduction AND $product->specificPrice.reduction_type == 'amount'}{$product->specificPrice.reduction}{else}0{/if};
var specific_price = {if $product->specificPrice AND $product->specificPrice.price}{$product->specificPrice.price}{else}0{/if};
var specific_currency = {if $product->specificPrice AND $product->specificPrice.id_currency}true{else}false{/if};
var group_reduction = '{$group_reduction}';
var default_eco_tax = {$product->ecotax};
var ecotaxTax_rate = {$ecotaxTax_rate};
var currentDate = '{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}';
var maxQuantityToAllowDisplayOfLastQuantityMessage = {$last_qties};
var noTaxForThisProduct = {if $no_tax == 1}true{else}false{/if};
var displayPrice = {$priceDisplay};
var productReference = '{$product->reference|escape:'htmlall':'UTF-8'}';
var productAvailableForOrder = {if (isset($restricted_country_mode) AND $restricted_country_mode) OR $PS_CATALOG_MODE}'0'{else}'{$product->available_for_order}'{/if};
var productShowPrice = '{if !$PS_CATALOG_MODE}{$product->show_price}{else}0{/if}';
var productUnitPriceRatio = '{$product->unit_price_ratio}';
var idDefaultImage = {if isset($cover.id_image_only)}{$cover.id_image_only}{else}0{/if};

// Customizable field
var img_ps_dir = '{$img_ps_dir}';
var customizationFields = new Array();
{assign var='imgIndex' value=0}
{assign var='textFieldIndex' value=0}
{foreach from=$customizationFields item='field' name='customizationFields'}
	{assign var="key" value="pictures_`$product->id`_`$field.id_customization_field`"}
	customizationFields[{$smarty.foreach.customizationFields.index|intval}] = new Array();
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][0] = '{if $field.type|intval == 0}img{$imgIndex++}{else}textField{$textFieldIndex++}{/if}';
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][1] = {if $field.type|intval == 0 && isset($pictures.$key) && $pictures.$key}2{else}{$field.required|intval}{/if};
{/foreach}

// Images
var img_prod_dir = '{$img_prod_dir}';
var combinationImages = new Array();

{if isset($combinationImages)}
	{foreach from=$combinationImages item='combination' key='combinationId' name='f_combinationImages'}
		combinationImages[{$combinationId}] = new Array();
		{foreach from=$combination item='image' name='f_combinationImage'}
			combinationImages[{$combinationId}][{$smarty.foreach.f_combinationImage.index}] = {$image.id_image|intval};
		{/foreach}
	{/foreach}
{/if}

combinationImages[0] = new Array();
{if isset($images)}
	{foreach from=$images item='image' name='f_defaultImages'}
		combinationImages[0][{$smarty.foreach.f_defaultImages.index}] = {$image.id_image};
	{/foreach}
{/if}

// Translations
var doesntExist = '{l s='The product does not exist in this model. Please choose another.' js=1}';
var doesntExistNoMore = '{l s='This product is no longer in stock' js=1}';
var doesntExistNoMoreBut = '{l s='with those attributes but is available with others' js=1}';
var uploading_in_progress = '{l s='Uploading in progress, please wait...' js=1}';
var fieldRequired = '{l s='Please fill in all required fields' js=1}';

{if isset($groups)}
	// Combinations
	{foreach from=$combinations key=idCombination item=combination}
		addCombination({$idCombination|intval}, new Array({$combination.list}), {$combination.quantity}, {$combination.price}, {$combination.ecotax}, {$combination.id_image}, '{$combination.reference|addslashes}', {$combination.unit_impact}, {$combination.minimal_quantity});
	{/foreach}
	// Colors
	{if $colors|@count > 0}
		{if $product->id_color_default}var id_color_default = {$product->id_color_default|intval};{/if}
	{/if}
{/if}
//]]>
</script>

    <div class="data"> 	
        <div class="box-product-detail" id="primary_block">  


	<div id="pb-right-column">
		<!-- product img-->
		<div id="image-block">
		{if $have_image}
			<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}"
				{if $jqZoomEnabled}class="jqzoom" alt="{$link->getImageLink($product->link_rewrite, $cover.id_image)}"{else} title="{$product->name|escape:'htmlall':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" {/if} id="bigpic" width="{$largeSize.width}" height="{$largeSize.height}" />
		{else}
			<img src="{$img_prod_dir}{$lang_iso}-default-large.jpg" id="bigpic" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}" />
		{/if}
		{*
		<div class="image_hints">
											<a class="view_large" href="#">View Large Image</a>
														<span class="hover_to_zoom">Rê chuột để xem chi tiết.</span>
							</div>
							*}
		</div>

		{if isset($images) && count($images) > 0}
		<!-- thumbnails -->
		<div id="views_block" {if isset($images) && count($images) < 2}class="hidden"{/if}>
		
		{if isset($images) && count($images) > 3}<span class="view_scroll_spacer"><a id="view_scroll_left" class="hidden" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Previous'}</a></span>{/if}
		<div id="thumbs_list">
			<ul id="thumbs_list_frame">
				{if isset($images)}
					{foreach from=$images item=image name=thumbnails}
					{assign var=imageIds value="`$product->id`-`$image.id_image`"}
					<li id="thumbnail_{$image.id_image}">
						<a href="{$link->getImageLink($product->link_rewrite, $imageIds)}" rel="other-views" class="thickbox {if $smarty.foreach.thumbnails.first}shown{/if}" title="{$image.legend|htmlspecialchars}">
							<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium')}" alt="{$image.legend|htmlspecialchars}" height="{$mediumSize.height}" width="{$mediumSize.width}" />
						</a>
					</li>
					{/foreach}
				{/if}
			</ul>
		</div>
	
		{if isset($images) && count($images) > 3}<a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a>{/if}
		</div>
		{/if}
		<!-- usefull links-->
		<ul id="usefull_link_block">
			
			{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
			{* <li><a href="javascript:print();">{l s='Print'}</a><br class="clear" /></li>*} 
			{if $have_image && !$jqZoomEnabled}
			<li><span id="view_full_size" class="span_link">{l s='View full size'}</span></li>
			{/if}
		</ul>
	</div>
 

	<div id="more_info_block">

		<ul id="more_info_tabs" class="idTabs idTabsShort">
			<li><a hreff="#idTab1" class="selected">Chi tiết</a></li>
			<li><a hreff="#idTab2">Thương hiệu</a></li>
		</ul>
		
		<div id="more_info_sheets" class="sheets align_justify">           
			
			
            <div id="idTab1" class="product-info">  

            	<h3><a href="#"><img src="../img/m/{$product_manufacturer->id_manufacturer}.jpg" alt="{$product_manufacturer->name|escape:'htmlall':'UTF-8'}" border="0"/></a></h3>

                <form id="buy_block" {if $PS_CATALOG_MODE AND !isset($groups) AND $product->quantity > 0}class=""{/if} action="{$link->getPageLink('cart.php')}" method="post">
                	<p class="hidden">
                        <input type="hidden" name="token" value="{$static_token}" />
                        <input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
                        <input type="hidden" name="add" value="1" />
                        <input type="hidden" name="id_product_attribute" id="idCombination" value="" />
                    </p>
                    <ul>
                        <li class="first">
                            <div class="pro-name-price">
                                <ul>
                                    <li><h4>{$product->name}</h4></li>
                                    {if $product->show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                    	<li>                                    	
                                            <p class="price">
                                                {if !$priceDisplay || $priceDisplay == 2}
                                                    {assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL)}
                                                    {assign var='productPriceWithoutRedution' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
                                                {elseif $priceDisplay == 1}
                                                    {assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL)}
                                                    {assign var='productPriceWithoutRedution' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
                                                {/if}
                                                {if $product->on_sale}
                                                    <img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/>
                                                    <span class="on_sale">{l s='On sale!'}</span>
                                                {elseif $product->specificPrice AND $product->specificPrice.reduction AND $productPriceWithoutRedution >$productPrice}
                                                    <span class="discount">{l s='Reduced price!'}</span>
                                                {/if}                                                
                                                <span class="our_price_display">
                                                {if $priceDisplay >= 0 && $priceDisplay <= 2}
                                                    <span id="our_price_display">{convertPrice price=$productPrice}</span>
                                                    {if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) OR !isset($display_tax_label))}
                                                        {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}
                                                    {/if}
                                                {/if}
                                                </span>
                                                {if $priceDisplay == 2}
                                                    <span id="pretaxe_price"><span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>&nbsp;{l s='tax excl.'}</span>
                                                {/if}
                                                {if $product->specificPrice AND $product->specificPrice.reduction}
                                                    <span id="old_price">
                                                    {if $priceDisplay >= 0 && $priceDisplay <= 2}
                                                        {if $productPriceWithoutRedution > $productPrice}
                                                            <span id="old_price_display">{convertPrice price=$productPriceWithoutRedution}</span>
                                                            {if $tax_enabled && $display_tax_label == 1}
                                                                {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}
                                                            {/if}
                                                        {/if}
                                                    {/if}
                                                    </span>
                                                {/if}
                                            </p>                                            
                                            {if $product->specificPrice AND $product->specificPrice.reduction_type == 'percentage'}
                                                <p id="reduction_percent">{l s='(price reduced by'} <span id="reduction_percent_display">{$product->specificPrice.reduction*100}</span> %{l s=')'}</p>
                                            {/if}
                                            {if $packItems|@count}
                                                <p class="pack_price">{l s='instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></p>
                                                <br class="clear" />
                                            {/if}
                                            {if $product->ecotax != 0}
                                                <p class="price-ecotax">{l s='include'} <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span> {l s='for green tax'}
                                                    {if $product->specificPrice AND $product->specificPrice.reduction}
                                                    <br />{l s='(not impacted by the discount)'}
                                                    {/if}
                                                </p>
                                            {/if}
                                            {if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
                                                {math equation="pprice / punit_price"  pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
                                                <p class="unit-price"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'htmlall':'UTF-8'}</p>
                                            {/if}
                                            {*close if for show price*}
                                      	</li>
                                  	{/if}
                                    <li class="hidden">
                                        <span class="item-number-title">item number: <span class="item-number">12223343454</span></span>
                                    </li>
                                </ul>                                
                                <a href="#" class="hidden"><img src="{$smarty.const._THEME_DIR_}images/btn/stylelist-tip.gif" alt="" border="0" /></a>
                            </div>
                        
                        </li>
                        {if (isset($colors) && $colors) || isset($groups)}                    
                            <li id="product_attributes">
                                {if isset($colors) && $colors}    
                        <span class="first-item">Color: </span>
                                    <div id="color_picker">
                                        <ul id="color_to_pick_list" class="color">
                                            {foreach from=$colors key='id_attribute' item='color'}
                                                <li><a id_attribute="{$id_attribute|intval}" id="color_{$id_attribute|intval}" class="circle"onclick="updateColorSelect({$id_attribute|intval});$('#wrapResetImages').show('slow');" title="{$color.name}">{if file_exists($col_img_dir|cat:$id_attribute|cat:'.png')}<img src="{$img_ps_dir}a/{$color.id_product_attribute}-att.jpg" alt="{$color.name}" width="20" height="20" />{/if}</a></li>
                                            {/foreach}
                                        </ul>
                                    </div>                            
                                {/if}


								<div id="size_picker">     
                                    <ul>
                                    {foreach from=$groups key=id_attribute_group item=group}
                                        {if $group.attributes|@count && $id_attribute_group != 2}
                                            <li style="border:none;"> 
                                                <!-- attributes -->
                                                <span class="first-item" ><label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label></span>

                                                    {assign var="groupName" value="group_$id_attribute_group"}
                                        			<ul id="size_to_pick_list">
                                                        {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                            <li><a id_attribute="{$id_attribute|intval}" id="{$group.name|escape:'htmlall':'UTF-8'}_{$id_attribute|intval}" onclick="updateSizeSelect({$id_attribute|intval});$('#wrapResetImages').show('slow');" title="{$group_attribute|escape:'htmlall':'UTF-8'}">{$group_attribute|escape:'htmlall':'UTF-8'}</a></li>
                                                        {/foreach}
                                                    </ul>

                                             </li>
                                        {/if}
                                        
                                    {/foreach}
                                 </ul> 
                               </div>


                                {if isset($groups)} 
                                    <div id="attributes" style="display:none;">     
                                        <ul class="attributes">
                                        {foreach from=$groups key=id_attribute_group item=group}
                                            {if $group.attributes|@count}
                                                <li> 
                                                    <!-- attributes -->
                                                    <span class="first-item"><label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label></span>
                                                    <span class="last-item">
                                                        {assign var="groupName" value="group_$id_attribute_group"}
                                                        <select name="{$groupName}" id="group_{$id_attribute_group|intval}" onchange="javascript:findCombination();{if $colors|@count > 0}$('#wrapResetImages').show('slow');{/if};">
                                                            {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                                                <option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'htmlall':'UTF-8'}">{$group_attribute|escape:'htmlall':'UTF-8'}</option>
                                                            {/foreach}
                                                        </select>
                                                    </span>
                                                 </li>
                                            {/if}
                                            
                                        {/foreach}
                                     </ul> 
                                   </div>
                                {/if} 
                            </li>
                        {/if}
                        <li>   
                        {*
                        	<span class="first-item">                                
                               	<label>{l s='Quantity :'}</label>
                            </span>  
                            *}
                            <span class="last-item">
                            	<!-- quantity wanted -->
                            	{*<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) OR $virtual OR !$product->available_for_order OR $PS_CATALOG_MODE} style="display: none;"{/if}>
                                    <input type="text" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" size="2" maxlength="3" {if $product->minimal_quantity > 1}onkeyup="checkMinimalQuantity({$product->minimal_quantity});"{/if} />
                                </p>   *}                 
                                <!-- minimal quantity wanted -->
                                <p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 OR !$product->available_for_order OR $PS_CATALOG_MODE} style="display: none;"{/if}>{l s='You must add '}<strong id="minimal_quantity_label">{$product->minimal_quantity}</strong>{l s=' as a minimum quantity to buy this product.'}</p>
                                {if $product->minimal_quantity > 1}
									<script type="text/javascript">
                                        checkMinimalQuantity();
                                    </script>
                                {/if}  
                            	<p id="add_to_cart">
                            	    <input type="image" src="{$smarty.const._THEME_DIR_}images/btn/add-to-bag.png" class="btn-add-cart"/>
                            	    <span style="display:block; margin-top:4px;  color: #9B9B9B;">Giao hàng trong ngày!</span>
                                </p>
                                
                                <span class="hidden">delivery 2-4 days</span>
                            </span>   
                                {if 1}
                                <div class="pro-description">   
                                	  
                                        <div class="idShortDesc" >
                                        	{if $product->description_short}
                                            {$product->description_short}
                                            {/if}    
                                        </div>
                                    
                                						{if $features}
																	<div id="wash_care_feats">
																	{assign var="wash_i" value="1"}
																	<div><span style="position:relative;top:-5px;" class="feature_title">Chất liệu &amp; Cách giặt ủi :</span>
																	{foreach from=$features item=feature name="washfeats"}

																			{if file_exists("{$smarty.const._PS_ROOT_DIR_}/images/features/black/{$feature.id_feature_value}.png")}<img title="{$feature.name|replace:'_':''|escape:'htmlall':'UTF-8'}: {$feature.value|escape:'htmlall':'UTF-8'}" height="24" width="24" src="/images/features/black/{$feature.id_feature_value}.png"     			
																			 />{else}{if $wash_i}</div><div>{/if}{assign var="wash_i" value="0"}{$feature.value|escape:'htmlall':'UTF-8'} <span>{$feature.name|replace:'_':''|escape:'htmlall':'UTF-8'}</span>{if $smarty.foreach.washfeats.last}{else}, {/if}{/if}


																	{/foreach}
																	</div>
																	</div>
																{/if}                             	 
                                                                   
                                    {if $product->description}
                                    	<div class="idFullDesc" style="display:none">	
                                        	{$product->description}
                                        </div>
                                        <p id="idLinkShowDesc">
                                        	&raquo;&nbsp;<a href="javascript:showFullDesc();" class="button" >{l s='More details'}</a>
                                        </p>
                                    {/if}
                                    {if $packItems|@count > 0}
                                        <h3>{l s='Pack content'}</h3>
                                        {foreach from=$packItems item=packItem}
                                            <div class="pack_content">
                                                {$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)}">{$packItem.name|escape:'htmlall':'UTF-8'}</a>
                                                <p>{$packItem.description_short}</p>
                                            </div>
                                        {/foreach}
                                    {/if}
                                </div>
                            {/if}          
                        </li>             
                        <li class="no-padding hidden">
                            <span class="share-wishlist"><a class="share">share</a></span>
                            <span class="share-wishlist border-left"><a class="wish-list">add to wishlist</a></span>
                        </li>
                        <li class="hidden">
                            <div class="ext">
                                <div class="title">get this item for only 60dkk pre month!</div>
                                <ul>
                                    <li>Lear more about part payment</li>
                                    <li>Calculate price on this product</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
            	</form>
            </div>   

           <div id="idTab2" style="">
        
				
            	<h3><a href="#"><img src="../img/m/{$product_manufacturer->id_manufacturer}.jpg" alt="{$product_manufacturer->name|escape:'htmlall':'UTF-8'}" border="0"/></a></h3>

				<div id="brand_details">
					{$product_manufacturer->description}
				</div>
   				
           </div>
<a class="product-link" href="{$link->getProductLink($product)}">Xem đầy đủ sản phẩm</a>
	     </div>
		</div>

			
	
        </div>
    
    <!-- END DATA -->

{literal}
	<script type="text/javascript" language="javascript">				
		// $(function() {
		// 	$('.circle').corner();
		// })
		function showFullDesc() {
			//alert('chay');
			$('#idShortDesc').hide();			
			$('#idFullDesc').show();
			$('#idLinkShowDesc').hide();
		}

  
   	// Stuff to do as soon as the DOM is ready;

   

	  

	</script>
{/literal}

<!-- END MAIN CONTENT WEBSITE -->