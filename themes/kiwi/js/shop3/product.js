/*
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
*  @version  Release: $Revision: 6625 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


//global variables
var combinations = new Array();
var selectedCombination = new Array();
var globalQuantity = new Number;
var colors = new Array();

//check if a function exists
function function_exists(function_name)
{
	if (typeof function_name == 'string')
		return (typeof window[function_name] == 'function');
	return (function_name instanceof Function);
}

//execute oosHook js code
function oosHookJsCode()
{
	for (var i = 0; i < oosHookJsCodeFunctions.length; i++)
	{
		if (function_exists(oosHookJsCodeFunctions[i]))
			setTimeout(oosHookJsCodeFunctions[i]+'()', 0);
	}
}

//add a combination of attributes in the global JS sytem
function addCombination(idCombination, arrayOfIdAttributes, quantity, price, ecotax, id_image, reference, unit_price, minimal_quantity)
{
	globalQuantity += quantity;

	var combination = new Array();
	combination['idCombination'] = idCombination;
	combination['quantity'] = quantity;
	combination['idsAttributes'] = arrayOfIdAttributes;
	combination['price'] = price;
	combination['ecotax'] = ecotax;
	combination['image'] = id_image;
	combination['reference'] = reference;
	combination['unit_price'] = unit_price;
	combination['minimal_quantity'] = minimal_quantity;
	combinations.push(combination);

}

// search the combinations' case of attributes and update displaying of availability, prices, ecotax, and image
function findCombination(firstTime, id_attribute_selected, which_group)
{
	
	id_attribute_selected = id_attribute_selected || 0;
	which_group = which_group || 'none';
	
	$('#minimal_quantity_wanted_p').fadeOut();
	
	
	$selected_colors_length = $('#color_to_pick_list .current').length;
	$selected_sizes_length = $('#size_to_pick_list .current').length;
	
	if($selected_sizes_length == $selected_colors_length && $selected_colors_length == 1){

		$('#product_attributes a.gotBlurred').removeClass('gotBlurred');			
		
		remainCombination = new Array();

//		debugger;

		for (var combination = 0; combination < combinations.length; ++combination)
		{
				combination_idsAttributes = combinations[combination]['idsAttributes'];
				if (in_array(id_attribute_selected, combination_idsAttributes))
				{
						for (var i = 0; i < combination_idsAttributes.length; ++i)
							if(combination_idsAttributes[i] != id_attribute_selected){
								
								remainCombination.push(combination_idsAttributes[i]);
								
								if (combinations[combination]['image'] && combinations[combination]['image'] != -1 && which_group == 'color') {					
									displayImage( $('#thumb_'+combinations[combination]['image']).parent() );

									//updateDisplay();

									refreshProductImages(combinations[combination]['idCombination']);
									
								}
								
							}
				}
			
		}
					
					
		
		the_other_selected_attribute = $('#product_attributes a.current[id_attribute!='+id_attribute_selected+']').attr('id_attribute');
		
		if (!in_array(the_other_selected_attribute, remainCombination)){
			$('#product_attributes a.current[id_attribute='+the_other_selected_attribute+']').removeClass('current').addClass('gotBlurred');
//			$('#product_attributes a[id_attribute='+remainCombination[0]+']').addClass('current')			
		}else{


		}
		

		$('#quantity_wanted').val(1);
		var choice = new Array();
		$('div#attributes select').each(function(){
			choice.push($(this).val());
		});	

		//testing every combination to find the conbination's attributes' case of the user
		for (var combination = 0; combination < combinations.length; ++combination)
		{
			//verify if this combinaison is the same that the user's choice
			var combinationMatchForm = true;
			$.each(combinations[combination]['idsAttributes'], function(key, value)
			{
				if (!in_array(value, choice))
				{
					combinationMatchForm = false;
				}
			});
			if (combinationMatchForm)
			{
				if (combinations[combination]['minimal_quantity'] > 1)
				{
					$('#minimal_quantity_label').html(combinations[combination]['minimal_quantity']);
					$('#minimal_quantity_wanted_p').fadeIn();
					$('#quantity_wanted').val(combinations[combination]['minimal_quantity']);
					$('#quantity_wanted').bind('keyup', function() {checkMinimalQuantity(combinations[combination]['minimal_quantity']);});
				}
				//combination of the user has been found in our specifications of combinations (created in back office)

				selectedCombination['unavailable'] = false;
				selectedCombination['reference'] = combinations[combination]['reference'];
				$('#idCombination').val(combinations[combination]['idCombination']);


				//get the data of product with these attributes
				quantityAvailable = combinations[combination]['quantity'];
				selectedCombination['price'] = combinations[combination]['price'];
				selectedCombination['unit_price'] = combinations[combination]['unit_price'];
				if (combinations[combination]['ecotax'])
					selectedCombination['ecotax'] = combinations[combination]['ecotax'];
				else
					selectedCombination['ecotax'] = default_eco_tax;

				//show the large image in relation to the selected combination
				if (combinations[combination]['image'] && combinations[combination]['image'] != -1) {					
					displayImage( $('#thumb_'+combinations[combination]['image']).parent() );
				}

				//update the display
				updateDisplay();

				if(typeof(firstTime) != 'undefined' && firstTime)
					refreshProductImages(0);
				else
					refreshProductImages(combinations[combination]['idCombination']);
				//leave the function because combination has been found
				return;
			}
		}
		//this combination doesn't exist (not created in back office)
		selectedCombination['unavailable'] = true;
		updateDisplay();
		
	}else{

		remainCombination = new Array();

		for (var combination = 0; combination < combinations.length; ++combination)
		{
				combination_idsAttributes = combinations[combination]['idsAttributes'];
				if (in_array(id_attribute_selected, combination_idsAttributes))
				{
						for (var i = 0; i < combination_idsAttributes.length; ++i)
							if(combination_idsAttributes[i] != id_attribute_selected){
								
								remainCombination.push(combination_idsAttributes[i]);
								
								if (combinations[combination]['image'] && combinations[combination]['image'] != -1 && which_group == 'color') {					
									displayImage( $('#thumb_'+combinations[combination]['image']).parent() );
									
//									updateDisplay();
									
									refreshProductImages(combinations[combination]['idCombination']);
								}
								
								
							}
				}
			
		}
		
		for(var j = 0; j < remainCombination.length; ++j){			
			$('#product_attributes a.gotBlurred[id_attribute='+remainCombination[j]+']').removeClass('gotBlurred');
		}
		
	}
	

}

function updateColorSelect(id_attribute)
{
	
	$('#product_attributes a').addClass('gotBlurred');			
		
	$('#color_to_pick_list a.gotBlurred').removeClass('gotBlurred');
			
	// if($('#color_'+id_attribute).hasClass('gotBlurred'))
	// 	return fasle;
	
	if (id_attribute == 0)
	{
		refreshProductImages(0);
		return ;
	}
	
		$('#color_to_pick_list a.current').removeClass('current');
		
	// Visual effect
//	$('#color_'+id_attribute).fadeTo('fast', 1, function(){	$(this).fadeTo('slow', 0, function(){ $(this).fadeTo('fast', 1, function(){$(this).addClass('current');	}); }); });
		
		$('#color_'+id_attribute).addClass('current');
		
	
	// Attribute selection
	$('#group_'+id_color_default+' option[value='+id_attribute+']').attr('selected', 'selected');
	$('#group_'+id_color_default+' option[value!='+id_attribute+']').removeAttr('selected');
	
	findCombination(false, id_attribute, 'color');
	

//	$('#color_'+id_attribute).addClass('current');	
	
}

function updateSizeSelect(id_attribute)
{
	
	$('#product_attributes a').addClass('gotBlurred');			

	$('#size_to_pick_list a.gotBlurred').removeClass('gotBlurred');
	
	
	if (id_attribute == 0)
	{
		refreshProductImages(0);
		return ;
	}
	
			$('#size_to_pick_list a.current').removeClass('current');

			$('#Size_'+id_attribute).addClass('current');


	// Attribute selection
	id_tmp = id_color_default;
	id_color_default = 1;

	$('#group_'+id_color_default+' option[value='+id_attribute+']').attr('selected', 'selected');
	$('#group_'+id_color_default+' option[value!='+id_attribute+']').removeAttr('selected');

	id_color_default = id_tmp;

	findCombination(false, id_attribute, 'size');	
}


//update display of the availability of the product AND the prices of the product
function updateDisplay()
{
	if (!selectedCombination['unavailable'] && quantityAvailable > 0 && productAvailableForOrder == 1)
	{
		//show the choice of quantities
		$('#quantity_wanted_p:hidden').show('slow');

		//show the "add to cart" button ONLY if it was hidden
		$('#add_to_cart:hidden').fadeIn(600);

		//hide the hook out of stock
		$('#oosHook').hide();

		//availability value management
		if (availableNowValue != '')
		{
			//update the availability statut of the product
			$('#availability_value').removeClass('warning_inline');
			$('#availability_value').text(availableNowValue);
			$('#availability_statut:hidden').show();
		}
		else
		{
			//hide the availability value
			$('#availability_statut:visible').hide();
		}

		//'last quantities' message management
		if (!allowBuyWhenOutOfStock)
		{
			if (quantityAvailable <= maxQuantityToAllowDisplayOfLastQuantityMessage)
				$('#last_quantities').show('slow');
			else
				$('#last_quantities').hide('slow');
		}
		
		if (quantitiesDisplayAllowed)
		{
			$('#pQuantityAvailable:hidden').show('slow');
			$('#quantityAvailable').text(quantityAvailable);

			if (quantityAvailable < 2) // we have 1 or less product in stock and need to show "item" instead of "items"
			{
				$('#quantityAvailableTxt').show();
				$('#quantityAvailableTxtMultiple').hide();
			}
			else
			{
				$('#quantityAvailableTxt').hide();
				$('#quantityAvailableTxtMultiple').show();
			}
		}
	}
	else
	{
		//show the hook out of stock
		if (productAvailableForOrder == 1)
		{
			$('#oosHook').show();
			if ($('#oosHook').length > 0 && function_exists('oosHookJsCode'))
				oosHookJsCode();
		}

		//hide 'last quantities' message if it was previously visible
		$('#last_quantities:visible').hide('slow');

		//hide the quantity of pieces if it was previously visible
		$('#pQuantityAvailable:visible').hide('slow');

		//hide the choice of quantities
		if (!allowBuyWhenOutOfStock)
			$('#quantity_wanted_p:visible').hide('slow');

		//display that the product is unavailable with theses attributes
		if (!selectedCombination['unavailable'])
			$('#availability_value').text(doesntExistNoMore + (globalQuantity > 0 ? ' ' + doesntExistNoMoreBut : '')).addClass('warning_inline');
		else
		{
			$('#availability_value').text(doesntExist).addClass('warning_inline');
			$('#oosHook').hide();
		}
		$('#availability_statut:hidden').show();


		//show the 'add to cart' button ONLY IF it's possible to buy when out of stock AND if it was previously invisible
		if (allowBuyWhenOutOfStock && !selectedCombination['unavailable'] && productAvailableForOrder == 1)
		{
			$('#add_to_cart:hidden').fadeIn(600);

			if (availableLaterValue != '')
			{
				$('#availability_value').text(availableLaterValue);
				$('p#availability_statut:hidden').show('slow');
			}
			else
				$('p#availability_statut:visible').hide('slow');
		}
		else
		{
			$('#add_to_cart:visible').fadeOut(600);
			$('p#availability_statut:hidden').show('slow');
		}

		if (productAvailableForOrder == 0)
			$('p#availability_statut:visible').hide();
	}

	if (selectedCombination['reference'] || productReference)
	{
		if (selectedCombination['reference'])
			$('#product_reference span').text(selectedCombination['reference']);
		else if (productReference)
			$('#product_reference span').text(productReference);
		$('#product_reference:hidden').show('slow');
	}
	else
		$('#product_reference:visible').hide('slow');

	//update display of the the prices in relation to tax, discount, ecotax, and currency criteria
	if (!selectedCombination['unavailable'] && productShowPrice == 1)
	{
		var combination_add_price = selectedCombination['price'] * group_reduction;

		var tax = (taxRate / 100) + 1;
		var taxExclPrice = (specific_price ? (specific_currency ? specific_price : specific_price * currencyRate) : productPriceTaxExcluded) + combination_add_price * currencyRate;

		if (specific_price)
			var productPriceWithoutReduction = productPriceTaxExcluded + combination_add_price * currencyRate;

		if (!displayPrice && !noTaxForThisProduct)
		{

			var productPrice = ps_round(taxExclPrice * tax, 2);
			if (specific_price)
				productPriceWithoutReduction = ps_round(productPriceWithoutReduction * tax, 2);
		}
		else
		{
			var productPrice = ps_round(taxExclPrice, 2);
			if (specific_price)
				productPriceWithoutReduction = ps_round(productPriceWithoutReduction, 2);
		}

		var reduction = 0;
		if (reduction_price || reduction_percent)
		{
			reduction = productPrice * (parseFloat(reduction_percent) / 100) + reduction_price;
			if (reduction_price && (displayPrice || noTaxForThisProduct))
				reduction = reduction / tax;
		}

		if (!specific_price)
			productPriceWithoutReduction = productPrice;
		productPrice -= reduction;

		var ecotaxAmount = !displayPrice ? ps_round(selectedCombination['ecotax'] * (1 + ecotaxTax_rate / 100), 2) : selectedCombination['ecotax'];
		productPrice += ecotaxAmount;
		productPriceWithoutReduction += ecotaxAmount;

		//productPrice = ps_round(productPrice * currencyRate, 2);
		if (productPrice > 0)
			$('#our_price_display').text(formatCurrency(productPrice, currencyFormat, currencySign, currencyBlank));
		else
			$('#our_price_display').text(formatCurrency(0, currencyFormat, currencySign, currencyBlank));

		$('#old_price_display').text(formatCurrency(productPriceWithoutReduction, currencyFormat, currencySign, currencyBlank));

		/* Special feature: "Display product price tax excluded on product page" */
		if (!noTaxForThisProduct)
			var productPricePretaxed = productPrice / tax;
		else
			var productPricePretaxed = productPrice;
		$('#pretaxe_price_display').text(formatCurrency(productPricePretaxed, currencyFormat, currencySign, currencyBlank));
		/* Unit price */
        productUnitPriceRatio = parseFloat(productUnitPriceRatio);
		if (productUnitPriceRatio > 0 )
		{
        	newUnitPrice = (productPrice / parseFloat(productUnitPriceRatio)) + selectedCombination['unit_price'];
			$('#unit_price_display').text(formatCurrency(newUnitPrice, currencyFormat, currencySign, currencyBlank));
		}

		/* Ecotax */
		var ecotaxAmount = !displayPrice ? ps_round(selectedCombination['ecotax'] * (1 + ecotaxTax_rate / 100), 2) : selectedCombination['ecotax'];
		$('#ecotax_price_display').text(formatCurrency(ecotaxAmount, currencyFormat, currencySign, currencyBlank));
	}
}

//update display of the large image
function displayImage(domAAroundImgThumb)
{
    if (domAAroundImgThumb.attr('href'))
    {
			  var newSrc = domAAroundImgThumb.children('img').attr('src').replace('medium','large');			  
//        var newSrc = domAAroundImgThumb.attr('href').replace('thickbox','large');
        if ($('#bigpic').attr('src') != newSrc)
        {
            $('#bigpic').fadeOut('fast', function(){
                $(this).attr('src', newSrc).show();
                if (typeof(jqZoomEnabled) != 'undefined' && jqZoomEnabled)
	                $(this).attr('alt', domAAroundImgThumb.attr('href'));
            });
        }
        $('#views_block li a').removeClass('shown');
        $(domAAroundImgThumb).addClass('shown');
    }
}

// Serialscroll exclude option bug ?
function serialScrollFixLock(event, targeted, scrolled, items, position)
{
	serialScrollNbImages = $('#thumbs_list li:visible').length;
	serialScrollNbImagesDisplayed = 3;

	var leftArrow = position == 0 ? true : false;
	var rightArrow = position + serialScrollNbImagesDisplayed >= serialScrollNbImages ? true : false;

	$('a#view_scroll_left').css('cursor', leftArrow ? 'default' : 'pointer').css('display', leftArrow ? 'none' : 'block').fadeTo(0, leftArrow ? 0 : 1);
	$('a#view_scroll_right').css('cursor', rightArrow ? 'default' : 'pointer').fadeTo(0, rightArrow ? 0 : 1).css('display', rightArrow ? 'none' : 'block');
	return true;
}

// Change the current product images regarding the combination selected
function refreshProductImages(id_product_attribute)
{
	$('#thumbs_list_frame').scrollTo('li:eq(0)', 700, {axis:'x'});
	$('#thumbs_list li').hide();
	id_product_attribute = parseInt(id_product_attribute, 10);

	if (typeof(combinationImages) != 'undefined' && typeof(combinationImages[id_product_attribute]) != 'undefined')
	{
		for (var i = 0; i < combinationImages[id_product_attribute].length; i++)
			$('#thumbnail_' + parseInt(combinationImages[id_product_attribute][i], 10)).show();
	}
	if (i > 0)
	{
		var thumb_width = $('#thumbs_list_frame >li').width()+parseInt($('#thumbs_list_frame >li').css('marginRight'),10);
		$('#thumbs_list_frame').width((parseInt((thumb_width)* i,10) + 3) + 'px'); //  Bug IE6, needs 3 pixels more ?
	}
	else
	{
		$('#thumbnail_' + idDefaultImage).show();
		displayImage($('#thumbnail_'+ idDefaultImage +' a'));
	}
	$('#thumbs_list').trigger('goto', 0);
	serialScrollFixLock('', '', '', '', 0);// SerialScroll Bug on goto 0 ?
}
function flyAddBasket() {
	//
	/*
	var productX		= $('#bigpic').offset().left;
	var productY		= $('#bigpic').offset().top;
	alert(productX);
	return false;
	var basketX 		= $("#shopping_cart").offset().left;
	var basketY 		= $("#shopping_cart").offset().top;	
	
	var gotoX 			= basketX - productX + 100;
	var gotoY 			= basketY - productY;
	

	
	var newImageWidth 	= Math.round($('#bigpic').width() / 3);
	var newImageHeight	= Math.round($('#bigpic').height() / 3);
	
	$("#bigpic img")				
	.clone()
	.prependTo("#bigpic")
	.css({'position' : 'absolute'})
	.animate({opacity: 0.4}, 100 )
	.animate({opacity: 0.1, marginLeft: gotoX, marginTop: gotoY, width: newImageWidth, height: newImageHeight}, 1200, function() {
		$(this).remove();
		alert('xong');
		
		var productID = $('#proId').val(); 
		var quantity = $("#quantity").val();
		if( productID && quantity) {
			$.post("/cart/ajaddtocart", {id: productID, quantity: quantity, language: language}, function(data){
				var jsonData = $.parseJSON(data);							
				if (jsonData.code == 0) {
					var data = $.parseJSON(jsonData.data);
					if (data.numberOfProducts > 0){
						$("#cartSummaryDetail").css("display", "");
						$("#cartSummaryEmpty").css("display", "none");
						if (data.numberOfProducts > 1) {
							$("#cartNumberOfProducts").html(data.numberOfProducts + " products");
							
							
						}
						else
							$("#cartNumberOfProducts").html(data.numberOfProducts + " product");
						$("#cartTotalNumber").html(data.numberOfProducts);
						$("#cartTotal").html(data.total + " VND");
					}
					else {
						$("#cartSummaryDetail").css("display", "none");
						$("#cartSummaryEmpty").css("display", "");
					}
				}							
			});							
		}					
	});*/
	
}
//To do after loading HTML
$(document).ready(function()
{
	
	if(parseInt(ipaColor, 10) > 0){		
		$('#color_'+ipaColor).click();
	}
	
	//init the serialScroll for thumbs
	$('#thumbs_list').serialScroll({
		items:'li:visible',
		prev:'a#view_scroll_left',
		next:'a#view_scroll_right',
		axis:'x',
		offset:0,
		start:0,
		stop:true,
		onBefore:serialScrollFixLock,
		duration:700,
		step: 2,
		lazy: true,
		lock: false,
		force:false,
		cycle:false
	});

	$('#thumbs_list').trigger('goto', 1);// SerialScroll Bug on goto 0 ?
	$('#thumbs_list').trigger('goto', 0);

	//hover 'other views' images management
	$('#views_block li a').hover(
		function(){displayImage($(this));},
		function(){}
	);

	//set jqZoom parameters if needed
	if (typeof(jqZoomEnabled) != 'undefined' && jqZoomEnabled)
	{
		$('img.jqzoom').jqueryzoom({
			xzoom: 360, //zooming div default width(default width value is 200)
			yzoom: 450, //zooming div default width(default height value is 200)
			offset: 21 //zooming div default offset(default offset value is 10)
			//position: "right" //zooming div position(default position value is "right")
		});
	}

	//add a link on the span 'view full size' and on the big image
	$('span#view_full_size, div#image-block img').live('click',function(){
		$('#views_block li a.shown').click();
		

	});

	//catch the click on the "more infos" button at the top of the page
	$('div#short_description_block p a.button').click(function(){
		$('#more_info_tab_more_info').click();
		$.scrollTo( '#more_info_tabs', 1200 );
	});

	// Hide the customization submit button and display some message
	$('p#customizedDatas input').click(function() {
		$('p#customizedDatas input').hide();
		$('#ajax-loader').fadeIn();
		$('p#customizedDatas').append(uploading_in_progress);
	});

	//init the price in relation of the selected attributes
	if (typeof productHasAttributes != 'undefined' && productHasAttributes)
		findCombination(true);
	else if (typeof productHasAttributes != 'undefined' && !productHasAttributes)
		refreshProductImages(0);

	//
	$('a#resetImages').click(function() {
		updateColorSelect(0);
	});

	$('.thickbox').fancybox({
		'hideOnContentClick': true,
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic'
	});
	
				$('#fancybox-content')
				.mouseenter(function(){

				})
					.live("mousemove",function(e) {

						galleryImageHeight = $('#fancybox-img').height();						
						
						galleryImageWrap = $("#fancybox-content");
						galleryImageWrapHeight = galleryImageWrap.height();
						galleryImageWrapOffset = $('#fancybox-wrap').offset().top;
					
						var posY = (Math.round(( (e.pageY - galleryImageWrapOffset)/galleryImageWrapHeight)*100)/100)  * (galleryImageHeight-galleryImageWrapHeight);
						
						
						if(posY + galleryImageWrapHeight > galleryImageHeight)
								posY =	galleryImageHeight - 	galleryImageWrapHeight;			
						
						//IE makes it necessary to check that galleryImage exists...
						if ($("#fancybox-img")) $('#fancybox-img').css({'margin-top': -posY});
					});

	
	
});

function saveCustomization()
{
	$('#quantityBackup').val($('#quantity_wanted').val());
	customAction = $('#customizationForm').attr('action');
	$('body select[id^="group_"]').each(function() {
		customAction = customAction.replace(new RegExp(this.id + '=\\d+'), this.id +'='+this.value);
	});
	$('#customizationForm').attr('action', customAction);
	$('#customizationForm').submit();
}

function submitPublishProduct(url, redirect)
{
	var id_product = $('#admin-action-product-id').val();

	$.ajaxSetup({async: false});
	$.post(url+'/ajax.php', { submitPublishProduct: '1', id_product: id_product, status: 1, redirect: redirect },
		function(data)
		{
			if (data.indexOf('error') === -1)
				document.location.href = data;
		}
	);

	return true;
}

function checkMinimalQuantity(minimal_quantity)
{
	if ($('#quantity_wanted').val() < minimal_quantity)
	{
		$('#quantity_wanted').css('border', '1px solid red');
		$('#minimal_quantity_wanted_p').css('color', 'red');
	}
	else
	{
		$('#quantity_wanted').css('border', '1px solid #BDC2C9');
		$('#minimal_quantity_wanted_p').css('color', '#374853');
	}
}