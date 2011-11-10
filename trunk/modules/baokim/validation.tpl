{capture name=path}{l s='Shipping' mod='baokim'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Order summation' mod='baokim'}</h2>

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h3>{l s='Thanh toán Bảo Kim' mod='baokim'}</h3>

<form action="{$this_path_ssl}validation.php" method="post">
	<input type="hidden" name="confirm" value="1" />
	<p>
		<img src="https://www.baokim.vn/application/themes/baokim/img/img_new/logo.png" alt="{l s='Cash on delivery (COD) payment' mod='baokim'}" style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='Thanh toán Bảo Kim' mod='baokim'}
		<br/><br />
		{l s='Tổng giá trị đơn hàng:' mod='baokim'}
		<span id="amount_{$currencies.0.id_currency}" class="price">{convertPrice price=$total}</span> {l s='(tax incl.)' mod='baokim'}
	</p>
	<p>
		<br /><br />
		<br /><br />
		<b>{l s='Cám ơn bạn đã mua hàng!' mod='baokim'}.</b>
	</p>
	<p class="cart_navigation">
		<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Phương thức khác' mod='baokim'}</a>
		<input type="submit" name="submit" value="{l s='Thanh toán qua Bảo Kim' mod='baokim'}" class="exclusive_large" />
	</p>
</form>
