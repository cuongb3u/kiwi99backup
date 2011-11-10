<div class="payment_module" title="{l s='Onepay.vn' mod='onepay'}" style="margin-left:272px;">
	<input type="hidden" name="paymentModule5Url" id="paymentModule5Url" value="modules/onepay/validation.php?confirm=1"  />
	<input type="radio" name="paymentModule" id="paymentModule5" value="5" onclick="javascript:showHideOrderSummary(this.value);"/> {**}
    <label for="paymentModule5">
    	Sử dụng tài khoản Onepay
    	<img src="{$module_template_dir}onepay.gif" alt="{l s='Thanh toán an toàn qua Onepay' mod='onepay'}" style="float:left;" />		
  	</label>
   
    <div class="payment_module_ext" id="paymentModule5Ext" style="display:none">
    	<table border="0" width="500" cellpadding="0" cellspacing="0">
        	<tr>
            	<td colspan="2" align="center">Cám ơn bạn đã mua hàng bằng cổng thanh toán Onepay.vn. Hẹn gặp lại!</td>
            </tr>
            <tr>
            	<td width="80%" align="center">Tổng giá trị đơn hàng của bạn:<br/><em>( Đã bao thuế VAT )</em></td>
                <td align="left" valign="top">{assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}<h3 style="font-size:18px;">{convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}</h3></td>
            </tr>
        </table>  	
    </div>
</div>