{capture name=path}{l s='Shipping' mod='nganluong'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Order summation' mod='nganluong'}</h2>

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h3>{l s='Ngân Lượng payment' mod='nganluong'}</h3>

<form action="{$this_path_ssl}validation.php" method="post">
	<input type="hidden" name="confirm" value="1" />
	<p>
		<img src="{$this_path}nganluong.jpg" alt="{l s='Ngân Lượng payment' mod='nganluong'}" style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='You have chosen Ngân Lượng method.' mod='nganluong'}
		<br/><br />
		{l s='The total amount of your order is' mod='nganluong'}
		<span id="amount_{$currencies.0.id_currency}" class="price">{convertPrice price=$total}</span> {l s='(tax incl.)' mod='nganluong'}
	</p>
	<p>
		<br /><br />
		<br /><br />
		
		<b>{l s='Please click \'Thanh Toán\'' mod='nganluong'}.</b>
	</p>
	<p class="cart_navigation">
		<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Other payment methods' mod='nganluong'}</a>
		<input type="submit" name="submit" value="{l s='Thanh Toán' mod='nganluong'}" class="exclusive_large" />
	</p>
</form>
