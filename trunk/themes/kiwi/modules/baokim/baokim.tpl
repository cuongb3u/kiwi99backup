<div class="payment_module" title="{l s='BảoKim' mod='baokim'}" style="margin-top:-113px;clear:both;margin-left:272px;">
	<input type="hidden" name="paymentModule3Url" id="paymentModule3Url" value="modules/baokim/validation.php?confirm=1"  />
	<input type="radio" name="paymentModule" id="paymentModule3" value="3" onclick="javascript:showHideOrderSummary(this.value);"/> 
    <label for="paymentModule3">
    	Sử dụng tài khoản Bảo Kim
    	<img width="135" height="46" src="{$smarty.const._THEME_DIR_}modules/baokim/baokim.gif" alt="{l s='Thanh toán an toàn qua Bảo Kim' mod='baokim'}" style="float:left;clear:both;" />		
  	</label>
    <div class="payment_module_ext" id="paymentModule3Ext" style="display:none">
    	<table border="0" width="500" cellpadding="0" cellspacing="0">
        	<tr>
            	<td colspan="2" align="center">Cám ơn bạn đã mua hàng bằng tài khoản của Bảokim.vn. Hẹn gặp lại!</td>
            </tr>
            <tr>
            	<td width="80%" align="center">Tổng giá trị đơn hàng của bạn:<br/><em>( Đã bao thuế VAT )</em></td>
                <td align="left" valign="top">{assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}<h3 style="font-size:18px;">{convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}</h3></td>
            </tr>
        </table>
    </div>
</div>