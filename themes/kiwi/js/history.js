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
*  @version  Release: $Revision: 7509 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

//show the order-details with ajax
function showOrder(mode, var_content, file)
{
	var url;
	if (file.match(/^https?:\/\//))
		url = file;
	else
		url = baseDir + file + '.php';
		//alert(url);
	$('body').showContent('add','<div id="block-order-detail" style="width:720px;height:500px;padding:20px"></div>');
	$('#block-order-detail').ajaxloading();
	$.get(
		url,
		((mode == 1) ? {'id_order': var_content, 'ajax': true} : {'id_order_return': var_content, 'ajax': true}),
		function(data)
		{
			$('#block-order-detail').fadeIn('slow', function()
			{
				//$(this).html(data);
				$('#block-order-detail').ajaxloading('remove');
				$('#block-order-detail').html(data);
				/* if return is allowed*/
				if ($('div#order-detail-content table td.order_cb').length > 0)
				{
					//return slip : check or uncheck every checkboxes
					$('form div#order-detail-content th input[type=checkbox]').click(function()
					{
							$('form div#order-detail-content td input[type=checkbox]').each(function()
							{
								this.checked = $('form div#order-detail-content th input[type=checkbox]').is(':checked');
								updateOrderLineDisplay(this);
							});
					});
					//return slip : enable or disable 'global' quantity editing
					$('form div#order-detail-content td input[type=checkbox]').click(function()
					{
						updateOrderLineDisplay(this);
					});
					//return slip : limit quantities
					$('form div#order-detail-content td input.order_qte_input').keyup(function()
					{
						var maxQuantity = parseInt($(this).parent().find('span.order_qte_span').text());
						var quantity = parseInt($(this).val());
						if (isNaN($(this).val()) && $(this).val() != '')
						{
							$(this).val(maxQuantity);
						}
						else
						{
							if (quantity > maxQuantity)
								$(this).val(maxQuantity);
							else if (quantity < 1)
								$(this).val(1);
						}
					});
				}
				//catch the submit event of sendOrderMessage form
				$('form#sendOrderMessage').submit(function(){
					return sendOrderMessage();
			});
		//	$(this).fadeIn('slow');
			//$.scrollTo(this, 1200);
			if(typeof(resizeAddressesBox) == 'function')
				resizeAddressesBox();
		});
	});
}

function updateOrderLineDisplay(domCheckbox){
	var lineQuantitySpan = $(domCheckbox).parent().parent().find('span.order_qte_span');
	var lineQuantityInput = $(domCheckbox).parent().parent().find('input.order_qte_input');
	if($(domCheckbox).is(':checked'))
	{
		lineQuantitySpan.hide();
		lineQuantityInput.show();
	}
	else
	{
		lineQuantityInput.hide();
		lineQuantityInput.val(lineQuantitySpan.text());
		lineQuantitySpan.show();
	}
}

//send a message in relation to the order with ajax
function sendOrderMessage (){
	paramString = "ajax=true";
	$('form#sendOrderMessage').find('input, textarea').each(function(){
		paramString += '&' + $(this).attr('name') + '=' + encodeURI($(this).val());
	});
	$.ajax({
		type: "POST",
		url: baseDir + "order-detail.php",
		data: paramString,
		success: function (msg){
			$('#block-order-detail').fadeOut('slow', function() {
				$(this).html(msg);
				//catch the submit event of sendOrderMessage form
				$('form#sendOrderMessage').submit(function(){
					return sendOrderMessage();
				});
				$(this).fadeIn('slow');
			});
		}
	});
	return false;
}
