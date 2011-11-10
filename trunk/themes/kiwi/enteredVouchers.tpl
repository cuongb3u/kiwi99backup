{if sizeof($discounts)}    	

    {foreach from=$discounts item=discount name=discountLoop}
        <tr class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if}" id="cart_discount_{$discount.id_discount}">
            <td class="cart_discount_name" colspan="2">{$discount.name}</td>
            <td class="cart_discount_description" colspan="2">{$discount.description}</td>
            <td class="cart_discount_delete" colspan="2">
         <span class="price-discount">
                {if $discount.value_real > 0}
                    {if !$priceDisplay}{displayPrice price=$discount.value_real*-1}{else}{displayPrice price=$discount.value_tax_exc*-1}{/if}
                {/if}
            </span>
            <a href="{if $opc}{$link->getPageLink('order-opc.php', true)}{else}{$link->getPageLink('order.php', true)}{/if}?deleteDiscount={$discount.id_discount}" class="cart_quantity_delete" title="{l s='Delete'}">Remove{*<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="icon " width="11" height="13" />*}</a>
        </td>
        </tr>
    {/foreach}
              
{/if}
