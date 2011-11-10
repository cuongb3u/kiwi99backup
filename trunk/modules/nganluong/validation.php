<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/nganluong.php');
$nganluong = new NganLuong();
if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$nganluong->active)
	Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');

/* Validate order */
if (Tools::getValue('confirm'))
{
	$customer = new Customer(intval($cart->id_customer));
	$total = $cart->getOrderTotal(true, 3);
	$final_total=Tools::ps_round(intval($total));
	$nganluong->validateOrderNL(intval($cart->id), _PS_OS_PREPARATION_, $total, $nganluong->displayName);
	$order = new Order(intval($nganluong->currentOrder));
	if(isset($_GET['secure_code']) && !empty($_GET['secure_code'])) #Nhận thông tin
	{
	#Thông tin giao dịch
	$transaction_info = @$_GET['transaction_info'];
	#Mã sản phẩm, mã đơn hàng, giỏ hàng giao dịch
	$order_code = @$_GET['order_code'];
	#Tổng số tiền thanh toán
	$price = @$_GET['price'];
	#Mã giao dịch thanh toán tại nganluong.vn
	$payment_id = @$_GET['payment_id'];
	#Loại giao dịch tại nganluong.vn (1=thanh toán ngay, 2=thanh toán tạm giữ)
	$payment_type = @$_GET['payment_type'];
	#Thông tin chi tiết về lỗi trong quá trình thanh toán
	$error_text = @$_GET['error_text'];
	#Lấy mã kiểm tra tính hợp lệ của đầu vào
	$secure_code = @$_GET['secure_code'];
	#Xử lý đầu vào
	$nl=new NL_Checkout();
	$check = $nl->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
	if($check === false)
	{
		#Tham số gửi về không hợp lệ, có sự can thiệp từ bên ngoài
		echo 'Kết quả thanh toán không hợp lệ';
	}
	else
	{
		if($error_text != '')
		{
			#Có lỗi trong quá trình thanh toán
			echo 'Có lỗi: '.$error_text.'! Hãy thực hiện lại!';
		}
		else
		{
			#Thanh toán thành công
			echo 'Thanh toán thành công!';
		}
	}
	}
else #Gửi thông tin
{
	#Mã sản phẩm, mã đơn hàng, mã giỏ hàng
	$order_code = intval($nganluong->currentOrder);
	#Tổng số tiền thanh toán
	$price = $final_total;
	#Thông tin giao dịch đến nganluong.vn
	$nl = new NL_Checkout(Configuration::get('NGANLUONG_CHECKOUT'),Configuration::get('NGANLUONG_MERCHANT_SITE'),Configuration::get('NGANLUONG_SECURE_CODE'));
	$strReturn = Configuration::get('NGANLUONG_RETURN_URL').'?id_cart='.$cart->id.'&id_module='.$nganluong->id.'&id_order='.$order_code.'&key='.$customer->secure_key;	
	$url=$nl->buildCheckoutUrl($strReturn,Configuration::get('NGANLUONG_RECEIVER'),'',$order_code,$price);
	Tools::redirectLink($url);
	exit();
}
}
else
{
	/* or ask for confirmation */ 
	$smarty->assign(array(
		'total' => $cart->getOrderTotal(true, 3),
		'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/nganluong/'
	));

	$smarty->assign('this_path', __PS_BASE_URI__.'modules/nganluong/');
	$template = 'validation.tpl';
	if (file_exists(_PS_THEME_DIR_.'modules/nganluong/'.$template))
		echo Module::display('nganluong', $template);
	else
		echo Module::display(__FILE__, $template);
}

include(dirname(__FILE__).'/../../footer.php');