<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/onepay.php');

$onepay = new onepay();
if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$onepay->active)
	Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

/* Validate order */
if (Tools::getValue('confirm'))
{
	$customer = new Customer(intval($cart->id_customer));
	$total = $cart->getOrderTotal(true, 3);

	$final_total=Tools::ps_round(intval($total));
	$onepay->validateOrderOP(intval($cart->id), _PS_OS_PREPARATION_, $total, $onepay->displayName);

	$order = new Order(intval($onepay->currentOrder));

	$order_code = intval($onepay->currentOrder);
	
	$price = intval( $final_total * Configuration::get('vpc_Amount') ) ;	
	$onepay_checkout 	= new OnePayMethod();
	$return_url_success = Configuration::get('vpc_ReturnURL'); //isset(Configuration::get('vpc_ReturnURL')) ? Configuration::get('vpc_ReturnURL') : 'http://yupplease.com/en/order-confirmation.php';
	$return_url_success .= '?id_cart=' . $cart->id . '&id_order=' . $order_code . '&key=' . $customer->secure_key . '&id_module='.$onepay->id;	
	$addrInvoice 		= new Address(intval($cart->id_address_invoice));	
	$url				= $onepay_checkout->createRequestUrl(	
																Configuration::get('vpc_Merchant'), 
																Configuration::get('vpc_AccessCode'), 										
																$order_code, 												
																$price, 
																$return_url_success,
																Configuration::get('vpc_Version'), 
																Configuration::get('vpc_Command'),
																Configuration::get('vpc_Locale'),
																Configuration::get('vpc_Currency'),
																$addrInvoice,
																$customer																
															);
	Tools::redirectLink($url);	
	exit();
}
else
{
	/* or ask for confirmation */ 
	$smarty->assign(array(
		'total' => $cart->getOrderTotal(true, 3),
		'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/onepay/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/onepay/');
	$template = 'validation.tpl';
	if (file_exists(_PS_THEME_DIR_.'modules/onepay/'.$template))
		echo Module::display('onepay', $template);
	else
		echo Module::display(__FILE__, $template);
}

include(dirname(__FILE__).'/../../footer.php');
