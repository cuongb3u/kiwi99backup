<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/baokim.php');

$baokim = new baokim();
if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$baokim->active)
	Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

/* Validate order */
if (Tools::getValue('confirm'))
{
	$customer = new Customer(intval($cart->id_customer));
	$total = $cart->getOrderTotal(true, 3);

	$final_total=Tools::ps_round(intval($total));
	$baokim->validateOrderBK(intval($cart->id), _PS_OS_PREPARATION_, $total, $baokim->displayName);

	$order = new Order(intval($baokim->currentOrder));

	$order_code = intval($baokim->currentOrder);
	
	//$order_code = 'Mã đơn hàng:'.$order_code;

	$price = intval($final_total);
	$url_detail = Configuration::get('baokim_CHECKOUT') . '/history.php';
	
	$bk_checkout = new BaoKimPayment();
	// url return
	$url_success = 'http://kiwi99.com/en/order-confirmation.php?id_cart='.$cart->id.'&id_module='.$baokim->id.'&id_order='.$order_code.'&key='.$customer->secure_key;	
	
	$url=$bk_checkout->createRequestUrl($order_code, Configuration::get('baokim_RECEIVER'), $price, $shipping_fee, $tax_fee, $order_description, $url_success, $url_detail, $url_detail,Configuration::get('baokim_MERCHANT_SITE'),Configuration::get('baokim_SECURE_CODE'));

	Tools::redirectLink($url);
	
	exit();
}
else
{
	/* or ask for confirmation */ 
	$smarty->assign(array(
		'total' => $cart->getOrderTotal(true, 3),
		'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/baokim/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/baokim/');
	$template = 'validation.tpl';
	if (file_exists(_PS_THEME_DIR_.'modules/baokim/'.$template))
		echo Module::display('baokim', $template);
	else
		echo Module::display(__FILE__, $template);
}

include(dirname(__FILE__).'/../../footer.php');
