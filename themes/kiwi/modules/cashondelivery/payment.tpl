<div class="payment_module" title="{l s='Pay with cash on delivery (COD)'}" style="margin-top:20px; margin-left:-272px;">
    <input type="hidden" name="paymentModule2Url" id="paymentModule2Url" value="{$this_path_ssl}validation.php?confirm=1" />
	<input type="radio" name="paymentModule" id="paymentModule2" value="2" onclick="javascript:showHideOrderSummary(this.value);"/>    			
    <label for="paymentModule2">Giao hàng, nhận tiền</label>
    <div class="payment_module_ext" id="paymentModule2Ext" style="display:none">
    	<table border="0" width="500" cellpadding="0" cellspacing="0">
        	<tr><th>GIAO HÀNG, NHẬN TIỀN</th></tr>
            
        	<tr>
            	<td colspan="2" align="left">
                	<ul>
                    	<li>Chúng tôi sẽ nhận tiền khi giao hàng cho quý khách.</li>
                        <li>Thời gian giao hàng của chúng tôi sẽ là 3 ngày (trừ các ngày cuối tuần và ngày lễ, tết)</li>
                        <li>Trước khi đến chúng tôi sẽ gọi điện để xác nhận lại thông tin đã cung cấp.</li>
                    </ul>
                </td>
            </tr>
            <tr>
            	<td width="80%" align="center">Tổng giá trị đơn hàng của bạn:<br/><em>( Đã bao thuế VAT )</em></td>
                <td align="left" valign="top">{assign var='blockuser_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}<h3 style="font-size:18px;">{convertPrice price=$cart->getOrderTotal(true, $blockuser_cart_flag)}</h3></td>
            </tr>
        </table>     
    </div>
</div>