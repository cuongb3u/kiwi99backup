<div class="payment_module" title="{l s='Pay by bank wire' mod='bankwire'}" style="margin-top:100px;">
	<input type="hidden" name="paymentModule1Url" id="paymentModule1Url" value="{$this_path_ssl}validation.php?currency_payement={if isset($cart->id_currency)}{$cart->id_currency}{else}{/if}" />
	<input type="radio" name="paymentModule" id="paymentModule1" value="1"  onclick="javascript:showHideOrderSummary(this.value);"/> 
    <label for="paymentModule1">  
    	Thanh toán qua Ngân Hàng
    </label>
    <div class="payment_module_ext" id="paymentModule1Ext" style="display:none">
    	<table border="0" width="500" cellpadding="0" cellspacing="0">
        	<tr><th rowspan="3">Chọn ngân hàng bạn muốn thanh toán<br /><img src="{$smarty.const._THEME_DIR_}images/bank.png" alt="Bank" border="0" /></th></tr>
            <tr>
            	<td align="left" valign="top">Thông tin Ngân Hàng của Kiwi99.com<br/><br/><strong>Ngân Hàng Techcom Bank</strong><br/>Tên người nhận: Lê Tôn Vinh<br/>Số tài khoản: 0909.0011<br/>Chi Nhánh: Lê Ngô Cát</td>
            </tr>
            <tr>
            	<td align="left" valign="top">Tổng giá trị đơn hàng của bạn:<br/><em>( Đã bao thuế VAT )</em><br/>{assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}<h3 style="font-size:18px;">{convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}</h3></td>
            </tr>
        </table>     
    </div>
</div>
