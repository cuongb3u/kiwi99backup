<div class="box-bankwire">
	{if $status == 'ok'}
    	<h4>Phương thức thanh toán</h4>
    {/if}   
    <div class="bankwire-item"> 
        {if $status == 'ok'}
        	<div style="float:left;width:610px;margin:0 40px;">
            	<p>
                    Quý khách đã chọn phương thức thanh toán quan Ngân Hàng.
                </p>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                	<tr>
                    	<td width="60%">
                			<p>{if $bankwireDetails}{$bankwireDetails}{else}___________{/if}</p>  
                            <p>Tài khoản: <span class="bold">{if $bankwireOwner}{$bankwireOwner}{else}___________{/if}</span></p>
                            <p>&nbsp;</p>      	
                            <p>
                            	<span class="bold">{if $bankwireAddress}{$bankwireAddress}{else}___________{/if}
                            </p>
                        </td>
                        <td align="center">
                        	<p>Tổng giá trị hóa đơn<br/><em>(Đã bao gồm thuế VAT)</em></p>
                            <p><strong style="font-size:18px;">{$total_to_pay}</strong></p>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                        	<br /><br />
                        	{l s='Do not forget to insert your order #' mod='bankwire'} <span class="bold">{$id_order}</span> {l s='in the subject of your bank wire' mod='bankwire'}
                    		<br /><br />{l s='An e-mail has been sent to you with this information.' mod='bankwire'}
                    		<br /><br /><span class="bold">{l s='Your order will be sent as soon as we receive your settlement.' mod='bankwire'}</span>
                    		<br /><br />{l s='For any questions or for further information, please contact our' mod='bankwire'} <a href="{$link->getPageLink('contact-form.php', true)}">{l s='customer support' mod='bankwire'}</a>.
                        </td>
                    </tr>
                </table>
            </div>           
        {else}
            <p class="warning">
                {l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='bankwire'} 
                <a href="{$link->getPageLink('contact-form.php', true)}">{l s='customer support' mod='bankwire'}</a>.
            </p>
        {/if}
  	</div>
</div>

