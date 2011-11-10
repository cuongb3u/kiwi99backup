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
{if !isset($ajaxload)}
<div class="main-content">
	
    <!-- LEFT MENU -->
    {include file="$tpl_dir./shop$shop/leftmenu.tpl"}
    <!-- END LEFT MENU -->
    <!-- DATA -->

	{/if}	 
	<div class="ajax-content">   
	    <div class="navi">

		        	<h3><a href="{$base_dir}" title="{l s='return to'} {l s='Home'}">{l s='Home'}</a>{if isset($path) AND $path}{*<span class="navigation-pipe"> {$navigationPipe|escape:html:'UTF-8'} </span>*}{if !$path|strpos:'span'}<span class="navigation_page">{$path}</span>{else}{$path}{/if}{/if}
		</h3>
		           {* <ul>
		                <li class="first"><a href="javascript: history.back()">&laquo; back</a> | <a href="javascript:history.go(-1)">‹ previous</a> | <a href="javascript:history.go(0)">next ›</a></li>
		                <li class="last">
		                    <a href="#" class="lastest-viewed">lastest viewed</a>
		                </li>
		            </ul>*}
		        </div>               	

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
					<a href="{$link->getImageLink($product->link_rewrite, $imageIds)}" rel="other-views" class="thickbox {if $cover.id_image	==	{$image.id_image}}shown{/if}" title="{$image.legend|htmlspecialchars}">
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
		{if isset($images) && count($images) > 1}<p class="align_center clear"><span id="wrapResetImages" style="display: none;"><img src="{$img_dir}icon/cancel_16x18.gif" alt="{l s='Cancel'}" width="16" height="18"/> <a id="resetImages" href="{$link->getProductLink($product)}" onclick="$('span#wrapResetImages').hide('slow');return (false);">{l s='Display all pictures'}</a></span></p>{/if}
		<!-- usefull links-->
		<ul id="usefull_link_block">
			{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
			<li><a href="javascript:print();">{l s='Print'}</a><br class="clear" /></li>
			{if $have_image && !$jqZoomEnabled}
			<li><span id="view_full_size" class="span_link">{l s='View full size'}</span></li>
			{/if}
		</ul>
	</div>
 

	<div id="more_info_block">
		<ul id="more_info_tabs" class="idTabs idTabsShort">
			<li><a href="#idTab1">Details</a></li>
			<li><a href="#idTab2">Brand</a></li>
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
                            {if $product->description_short OR $packItems|@count > 0}
                                <div class="pro-description">                                 	 
                                    {if $product->description_short}
                                        <div class="idShortDesc" {if !$product->description_short}style="display:none"{/if}>
                                            {$product->description_short}
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
                        {if (isset($colors) && $colors) || isset($groups)}                    
                            <li id="product_attributes">
                                {if isset($colors) && $colors}    
                        <span class="first-item">Color: </span>
                                    <div id="color_picker">
                                        <ul id="color_to_pick_list" class="color">
                                            {foreach from=$colors key='id_attribute' item='color'}
                                                <li><a id_attribute="{$id_attribute|intval}" id="color_{$id_attribute|intval}" class="circle"onclick="updateColorSelect({$id_attribute|intval});$('#wrapResetImages').show('slow');" title="{$color.name}">{if file_exists($col_img_dir|cat:$id_attribute|cat:'.png')}<img src="{$img_col_dir}{$id_attribute}.png" alt="{$color.name}" width="16" height="16" />{/if}</a></li>
                                            {/foreach}
                                        </ul>
                                    </div>                            
                                {/if}


								<div>     
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
                        	<span class="first-item">                                
                               	<label>{l s='Quantity :'}</label>
                            </span>  
                            <span class="last-item">
                            	<!-- quantity wanted -->
                            	<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) OR $virtual OR !$product->available_for_order OR $PS_CATALOG_MODE} style="display: none;"{/if}>
                                    <input type="text" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" size="2" maxlength="3" {if $product->minimal_quantity > 1}onkeyup="checkMinimalQuantity({$product->minimal_quantity});"{/if} />
                                </p>                    
                                <!-- minimal quantity wanted -->
                                <p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 OR !$product->available_for_order OR $PS_CATALOG_MODE} style="display: none;"{/if}>{l s='You must add '}<strong id="minimal_quantity_label">{$product->minimal_quantity}</strong>{l s=' as a minimum quantity to buy this product.'}</p>
                                {if $product->minimal_quantity > 1}
									<script type="text/javascript">
                                        checkMinimalQuantity();
                                    </script>
                                {/if}  
                            	<p id="add_to_cart">
                            	    <input type="image" src="{$smarty.const._THEME_DIR_}images/btn/add-to-bag.gif" class="btn-add-cart"/>
                                </p>
                                <span class="hidden">delivery 2-4 days</span>
                            </span>             
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

           <div id="idTab2">
        
				
            	<h3><a href="#"><img src="../img/m/{$product_manufacturer->id_manufacturer}.jpg" alt="{$product_manufacturer->name|escape:'htmlall':'UTF-8'}" border="0"/></a></h3>

				<div id="brand_details">
					{$product_manufacturer->description}
				</div>
   				
           </div>

	     </div>
		</div>

			<div id="right_products">
				{$HOOK_PRODUCT_FOOTER}
			</div>
			
	
        </div>
        <div class="box-product-relation">
            <div class="header-title">
                <ul>
                    <li class="first">
                        <strong>free delivery</strong>
                        <a href="#">learn more ›</a>
                    </li>
                    <li>
                        <strong>customer service</strong>
                        <a href="#">always here ›</a>
                    </li>
                    <li>
                        <strong>return policy</strong>
                        <a href="#">read up ›</a>
                    </li>
                </ul>
            </div>
            <div class="item parent-item">
                <h3>complete the look</h3>
             {*   <a class="prev-item"><span></span></a>
                <a class="next-item"><span></span></a>*}
                <div id="motioncontainer">
                 <div id="motiongallery">
                {if isset($accessories) AND $accessories}
                    <!-- accessories -->
                    <ul id="trueContainer">
                        {foreach from=$accessories item=accessory name=accessories_list}
                        	{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
                            <li class="ajax_block_product {if $smarty.foreach.accessories_list.first}first_item{elseif $smarty.foreach.accessories_list.last}last_item{else}item{/if} product_accessories_description">
                                <div class="pro-thumb">
                                    <a href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{$accessory.legend|escape:'htmlall':'UTF-8'}" class="product_image"><img src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, '90x120')}" alt="{$accessory.legend|escape:'htmlall':'UTF-8'}" width="90" height="120" /></a>
                                   {* <h4>vero moda dress here</h4>*}
                                    <h5>{if $accessory.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<span class="price">{if $priceDisplay != 1}{displayWtPrice p=$accessory.price}{else}{displayWtPrice p=$accessory.price_tax_exc}{/if}</span>{/if}</h5>
                                  {*  <h6>
                                    	{if $accessory.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<span class="price">{if $priceDisplay != 1}{displayWtPrice p=$accessory.price}{else}{displayWtPrice p=$accessory.price_tax_exc}{/if}</span>{/if}
                                        <a class="button" href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>
                                        {if ($accessory.allow_oosp || $accessory.quantity > 0) AND $accessory.available_for_order AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                            <a class="exclusive button ajax_add_to_cart_button" href="{$link->getPageLink('cart.php')}?qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add" rel="ajax_id_product_{$accessory.id_product|intval}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
                                        {else}
                                            <span class="exclusive">{l s='Add to cart'}</span>
                                            <span class="availability">{if (isset($accessory.quantity_all_versions) && $accessory.quantity_all_versions > 0)}{l s='Product available with different options'}{else}{l s='Out of stock'}{/if}</span>
                                        {/if}
                                    </h6>*}
                                </div>
                            </li>
                            
                        {/foreach}
                    </ul>
               	{/if}
               	</div>
               	</div>
                <div class="adv">
                    <img src="{$smarty.const._THEME_DIR_}images/data/adv-2.gif" alt="" />
                </div>
            </div>
        </div>
        </div>
        {if !isset($ajaxload)}
    </div>
    </div>
    {/if}
    <!-- END DATA -->

{literal}
	<script type="text/javascript" language="javascript">				
		// $(function() {
		// 	$('.circle').corner();
		// })
		function showFullDesc() {
			alert('chay');
			$('#idShortDesc').hide();			
			$('#idFullDesc').show();
			$('#idLinkShowDesc').hide();
		}
	</script>
{/literal}
	
<!-- END MAIN CONTENT WEBSITE -->