{if $voucherAllowed}
	<form action="{if $opc}{$link->getPageLink('order-opc.php', true)}{else}{$link->getPageLink('order.php', true)}{/if}" method="post" id="voucher" name="voucher">
        <div id="cart_voucher" class="voucher-code">
        	{if isset($errors_discount) && $errors_discount}
                <ul class="error">
                    {foreach from=$errors_discount key=k item=error}
                        <li>{$error|escape:'htmlall':'UTF-8'}</li>
                    {/foreach}
                </ul>
            {/if}
            <label>{l s='Mã giảm giá:'}</label>
            <div class="question-box"><span class="question-icon">?</span>
            <div class="question-content">
            	<h4>Applying Discounts</h4><p><a href="javascript:ConfigurablePopup('/web/checkout/help/popups/help.jsp?messageId=discounts','320','391','','0','0','0','0')">Learn More about how to find and apply discounts</a></p><p>Enter your 8 or 20 digit code into the Discount Code field without any spaces, then click the Apply Discount button.</p>
            </div>	
            </div>
           	<input type="text" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" />
           	  <input type="submit" name="submitAddDiscount" value="{l s='update ›'}" class="button" />      
            {*<ul>
                <li class="first">
                	<input type="hidden" name="submitDiscount" />          	
               	</li>
                <li><a href="#"><img src="{$smarty.const._THEME_DIR_}images/icon/question.gif" alt="" border="0" /></a></li>
            </ul>  *}
            {if $displayVouchers}
              {*  <div class="show-voucher">
                    <h4>{l s='Take advantage of our offers:'}</h4>
                    <div id="display_cart_vouchers">
                    {foreach from=$displayVouchers item=voucher}
                        <span onclick="$('#discount_name').val('{$voucher.name}');return false;" class="voucher_name">{$voucher.name}</span> - {$voucher.description} <br />
                    {/foreach}
                    </div>
              	</div>
              	*}
            {/if}

						<div style="clear:both;" id="voucherErrors"></div>
          
        </div>
  	</form>
{/if}
