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
*  @version  Release: $Revision: 6599 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	var baseDir = '{$base_dir_ssl}';
//]]>
</script>

{capture name=path}{l s='Xác nhận thông tin mua hàng'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<div class="box-order-confirmation">
	<div class="header-title"><h3>{l s='Thông tin sản phẩm đã mua'}</h3></div>
    <div class="order-confirmaction-item">
        {assign var='current_step' value='payment'}
        {include file="$tpl_dir./order-steps.tpl"}
        
        {include file="$tpl_dir./errors.tpl"}
        
        <p>Xin chào {$cookie->customer_firstname} {$cookie->customer_lastname},<br/>
            Bên dưới là danh sách các sản phẩm mà bạn đã đặt hàng tại Kiwi99.com. Bạn vui lòng kiểm tra lại, trong trường hợp có sự thay đổi bạn vui lòng liên hệ với chúng tôi trong vòng 24h trước khi lệnh đặt hàng của bạn có hiệu lực.</p>
        <p>&nbsp;</p>
        <table class="list-item" cellpadding="0" cellspacing="0" width="100%" border="0">
            <thead>
                <tr>
                    <th class="{if $return_allowed}item{else}first_item{/if}">{l s='Mã hàng'}</th>
                    <th>{l s='Sản phẩm'}</th>
                    <th>{l s='Số lượng'}</th>
                    <th>{l s='Đơn giá'}</th>
                    <th>{l s='Tổng giá'}</th>
                </tr>
            </thead>
            <tfoot>            	
                {if $priceDisplay && $use_tax}
                    <tr>
                        <td colspan="5">
                            {l s='Total products (tax excl.):'} {displayWtPriceWithCurrency price=$order->getTotalProductsWithoutTaxes() currency=$currency}
                        </td>
                    </tr>
                {/if}
                <tr>
                    <td colspan="5" align="right">
                        {l s='Tổng phí sản phẩm'} {if $use_tax}{l s='(tax incl.)'}{/if}: {displayWtPriceWithCurrency price=$order->getTotalProductsWithTaxes() currency=$currency}
                    </td>
                </tr>
                {if $order->total_discounts > 0}
                <tr>
                    <td colspan="5" align="right">
                        {l s='Tổng phí được giảm:'} {displayWtPriceWithCurrency price=$order->total_discounts currency=$currency}
                    </td>
                </tr>
                {/if}
                {if $order->total_wrapping > 0}
                <tr>
                    <td colspan="5" align="right">
                        {l s='Total gift-wrapping:'} {displayWtPriceWithCurrency price=$order->total_wrapping currency=$currency}
                    </td>
                </tr>
                {/if}
                <tr>
                    <td colspan="5" align="right">
                        {l s='Phí vận chuyển'} {if $use_tax}{l s='(tax incl.)'}{/if}: {displayWtPriceWithCurrency price=$order->total_shipping currency=$currency}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="right">
                        {l s='Thành thanh toán:'} {displayWtPriceWithCurrency price=$order->total_paid currency=$currency}
                    </td>
                </tr>
            </tfoot>
            <tbody>
            {foreach from=$products item=product name=products}
                {if !isset($product.deleted)}
                    {assign var='productId' value=$product.product_id}
                    {assign var='productAttributeId' value=$product.product_attribute_id}
                    {if isset($customizedDatas.$productId.$productAttributeId)}{assign var='productQuantity' value=$product.product_quantity-$product.customizationQuantityTotal}{else}{assign var='productQuantity' value=$product.product_quantity}{/if}
                    <!-- Customized products -->
                    {if isset($customizedDatas.$productId.$productAttributeId)}
                        <tr>                           
                            <td><label for="cb_{$product.id_order_detail|intval}">{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</label></td>
                            <td class="bold">
                                <label for="cb_{$product.id_order_detail|intval}">{$product.product_name|escape:'htmlall':'UTF-8'}</label>
                            </td>
                            <td align="center"><span class="order_qte_span editable">{$product.customizationQuantityTotal|intval}</span></td>
                            <td align="center">
                                <label for="cb_{$product.id_order_detail|intval}">
                                    {if $group_use_tax}
                                        {convertPriceWithCurrency price=$product.product_price_wt currency=$currency convert=0}
                                    {else}
                                        {convertPriceWithCurrency price=$product.product_price currency=$currency convert=0}
                                    {/if}
                                </label>
                            </td>
                            <td align="center">
                                <label for="cb_{$product.id_order_detail|intval}">
                                    {if isset($customizedDatas.$productId.$productAttributeId)}
                                        {if $group_use_tax}
                                            {convertPriceWithCurrency price=$product.total_customization_wt currency=$currency convert=0}
                                        {else}
                                            {convertPriceWithCurrency price=$product.total_customization currency=$currency convert=0}
                                        {/if}
                                    {else}
                                        {if $group_use_tax}
                                            {convertPriceWithCurrency price=$product.total_wt currency=$currency convert=0}
                                        {else}
                                            {convertPriceWithCurrency price=$product.total_price currency=$currency convert=0}
                                        {/if}
                                    {/if}
                                </label>
                            </td>
                        </tr>
                        {foreach from=$customizedDatas.$productId.$productAttributeId item='customization' key='customizationId'}
                        <tr class="alternate_item">                           
                            <td colspan="2">
                            {foreach from=$customization.datas key='type' item='datas'}
                                {if $type == $CUSTOMIZE_FILE}
                                <ul class="customizationUploaded">
                                    {foreach from=$datas item='data'}
                                        <li><img src="{$pic_dir}{$data.value}_small" alt="" class="customizationUploaded" /></li>
                                    {/foreach}
                                </ul>
                                {elseif $type == $CUSTOMIZE_TEXTFIELD}
                                <ul class="typedText">{counter start=0 print=false}
                                    {foreach from=$datas item='data'}
                                        {assign var='customizationFieldName' value="Text #"|cat:$data.id_customization_field}
                                        <li>{$data.name|default:$customizationFieldName}{l s=':'} {$data.value}</li>
                                    {/foreach}
                                </ul>
                                {/if}
                            {/foreach}
                            </td>
                            <td>
                                <label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$customization.quantity|intval}</span></label>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        {/foreach}
                    {/if}
                    <!-- Classic products -->
                    {if $product.product_quantity > $product.customizationQuantityTotal}                    	
                        <tr>
                        	<td><label for="cb_{$product.id_order_detail|intval}">{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</label></td>
                            <td class="bold">
                            	<span style="float:left;">
                                	{if $product.pai_id_image > 0}
                            			<img src="{$link->getImageLink($product.link_rewrite, $product.pai_id_image, 'small')}" alt="" align="texttop" border="0" />
                                   	{else}
                                    	<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small')}" alt="" align="texttop" border="0" />
                                    {/if}
                               	</span>
                                <label for="cb_{$product.id_order_detail|intval}" style="float:left;width:auto;text-align:left;">                                	
                                    {if $product.download_hash && $invoice}
                                        <a href="{$link->getPageLink('get-file.php', true)}?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}{if isset($is_guest) && $is_guest}&id_order={$order->id}&secure_key={$order->secure_key}{/if}" title="{l s='download this product'}">
                                            <img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Download product'}" />
                                        </a>
                                        <a href="{$link->getPageLink('get-file.php', true)}?key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}{if isset($is_guest) && $is_guest}&id_order={$order->id}&secure_key={$order->secure_key}{/if}" title="{l s='download this product'}">
                                            <strong>{$product.product_name|escape:'htmlall':'UTF-8'|replace:' - ':'<br/>'}</strong>
                                        </a>
                                    {else}
                                        <strong>{$product.product_name|escape:'htmlall':'UTF-8'|replace:' - ':'<br/>'}</strong>
                                    {/if}                                    
                                </label>
                            </td>
                            <td><label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$productQuantity|intval}</span></label></td>
                            <td>
                                <label for="cb_{$product.id_order_detail|intval}">
                                {if $group_use_tax}
                                    {convertPriceWithCurrency price=$product.product_price_wt currency=$currency convert=0}
                                {else}
                                    {convertPriceWithCurrency price=$product.product_price currency=$currency convert=0}
                                {/if}
                                </label>
                            </td>
                            <td>
                                <label for="cb_{$product.id_order_detail|intval}">
                                {if $group_use_tax}
                                    {convertPriceWithCurrency price=$product.total_wt currency=$currency convert=0}
                                {else}
                                    {convertPriceWithCurrency price=$product.total_price currency=$currency convert=0}
                                {/if}
                                </label>
                            </td>
                        </tr>
                    {/if}
                {/if}
            {/foreach}
            {foreach from=$discounts item=discount}
                <tr>
                    <td>{$discount.name|escape:'htmlall':'UTF-8'}</td>
                    <td>{l s='Voucher:'} {$discount.name|escape:'htmlall':'UTF-8'}</td>
                    <td><span class="order_qte_span editable">1</span></td>
                    <td>&nbsp;</td>
                    <td>{if $discount.value != 0.00}{l s='-'}{/if}{convertPriceWithCurrency price=$discount.value currency=$currency convert=0}</td>
                    {if $return_allowed}
                    	<td>&nbsp;</td>
                    {/if}
                </tr>
            {/foreach}
            </tbody>
        </table>        
  	</div>
    <div class="addr">
        <div id="orderConfirmBilling">
            <h4>Địa chỉ thanh toán &amp; giao nhận hàng</h4>
            <ul class="address">
                <li class="address_title">Địa chỉ thanh toán</li>
                {foreach from=$inv_adr_fields name=inv_loop item=field_item} 
                    {assign var=address_words value=" "|explode:$field_item} 
                    {if $field_item != 'district'}
                        <li>
                            <span class="first-item">{if $field_item eq 'firstname'}Họ tên{elseif $field_item eq 'address1'}Địa chỉ{elseif $field_item eq 'city'}Tỉnh/thành{elseif $field_item eq 'country'}Quốc gia{elseif $field_item eq 'phone'}Điện thoại{/if}:</span>
                            <span class="last-item">
                                {if isset($address_words)}
                                    {foreach from=$address_words item=word_item name="word_loop"}
                                        <span class="address_{$word_item}">
                                            {if $word_item == 'address1'}
                                                {strip}
                                                    {$invoiceAddressFormatedValues[$word_item]|escape:'htmlall':'UTF-8'}
                                                    {if $invoiceAddressFormatedValues['district'] > 0}
                                                        ,&nbsp;{$invoiceAddressFormatedValues['district']|districtname|escape:'htmlall':'UTF-8'}
                                                    {/if}
                                                {/strip}
                                            {elseif $word_item == 'city'}
                                                {$invoiceAddressFormatedValues[$word_item]|provincename|escape:'htmlall':'UTF-8'}
                                            {else}
                                                {$invoiceAddressFormatedValues[$word_item]|escape:'htmlall':'UTF-8'}
                                            {/if}
                                        </span>
                                    {/foreach}
                                {/if}
                            </span>
                        </li>
                    {/if}                    
                {/foreach}
            </ul>            
            <ul class="address2">
                <li class="address_title">Địa chỉ nhận hàng</li>
                {foreach from=$dlv_adr_fields name=dlv_loop item=field_item}
                    {assign var=address_words value=" "|explode:$field_item} 
                    {if $field_item != 'district'}
                        <li>
                            <span class="first-item">{if $field_item eq 'firstname'}Họ tên{elseif $field_item eq 'address1'}Địa chỉ{elseif $field_item eq 'city'}Tỉnh/thành{elseif $field_item eq 'country'}Quốc gia{elseif $field_item eq 'phone'}Điện thoại{/if}:</span>
                            <span class="last-item">
                                {if isset($address_words)}
                                    {foreach from=$address_words item=word_item name="word_loop"}
                                        <span class="address_{$word_item}">
                                            {if $word_item == 'address1'}
                                                {strip}
                                                    {$invoiceAddressFormatedValues[$word_item]|escape:'htmlall':'UTF-8'}
                                                    {if $invoiceAddressFormatedValues['district'] > 0}
                                                        ,&nbsp;{$invoiceAddressFormatedValues['district']|districtname|escape:'htmlall':'UTF-8'}
                                                    {/if}
                                                {/strip}
                                            {elseif $word_item == 'city'}
                                                {$invoiceAddressFormatedValues[$word_item]|provincename|escape:'htmlall':'UTF-8'}
                                            {else}
                                                {$invoiceAddressFormatedValues[$word_item]|escape:'htmlall':'UTF-8'}
                                            {/if}
                                        </span>
                                    {/foreach}
                                {/if}
                            </span>
                        </li>
                    {/if}  
                {/foreach}
            </ul>            
        </div>
        {$HOOK_ORDER_CONFIRMATION}
        {$HOOK_PAYMENT_RETURN}        
        <br />
        {if $is_guest}
            <p>{l s='Your order ID is:'} <span class="bold">{$id_order_formatted}</span> . {l s='Your order ID has been sent to your e-mail.'}</p>
            <a href="{$link->getPageLink('guest-tracking.php', true)}?id_order={$id_order}" title="{l s='Follow my order'}"><img src="{$smarty.const._THEME_DIR_}images/icon/order.gif" alt="{l s='Follow my order'}" class="icon" /></a>
            <a href="{$link->getPageLink('guest-tracking.php', true)}?id_order={$id_order}" title="{l s='Follow my order'}">{l s='Follow my order'}</a>
        {else}
        	<div style="float:right;font-size:11px;">
                <a href="{$link->getPageLink('history.php', true)}" title="{l s='Back to orders'}"><img src="{$smarty.const._THEME_DIR_}images/icon/order.gif" alt="{l s='Back to orders'}" class="icon" /></a>
                <a href="{$link->getPageLink('history.php', true)}" title="{l s='Back to orders'}">{l s='Xem các đơn hàng đã mua'}</a>
          	</div>
        {/if}
   	</div>
</div>
{include file="$tpl_dir./box/need-help.tpl"} 
