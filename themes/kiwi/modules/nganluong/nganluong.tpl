<div class="payment_module" title="{l s='Pay with Ngân Lượng' mod='nganluong'}" style="margin-top:-34px;margin-left:272px;">
	<input type="hidden" name="paymentModule4Url" id="paymentModule4Url" value="modules/nganluong/validation.php?confirm=1" />
	<input type="radio" name="paymentModule" id="paymentModule4" value="4" onclick="javascript:showHideOrderSummary(this.value);"/>    <label for="paymentModule4">
    	Sử dụng tài khoản Ngân Lượng
		<img src="{$smarty.const._THEME_DIR_}modules/nganluong/nganluong.gif" alt="{l s='Pay with Ngân Lượng' mod='nganluong'}" style="float:left;" />
  	</label>
   
    <div class="payment_module_ext" id="paymentModule4Ext" style="display:none">
    	<table border="0" width="500" cellpadding="0" cellspacing="0">
        	<tr>
            	<td colspan="2" align="center">Cám ơn bạn đã mua hàng bằng tài khoản của Ngân Lượng. Hẹn gặp lại!</td>
            </tr>
            <tr>
            	<td width="80%" align="center">Tổng giá trị đơn hàng của bạn:<br/><em>( Đã bao thuế VAT )</em></td>
                <td align="left" valign="top">{assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}<h3 style="font-size:18px;">{convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}</h3></td>
            </tr>
        </table>
    </div>
</div>
