<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
class baokim extends PaymentModule
{	
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'baokim';
		$this->tab = 'payments_gateways';
		$this->version = '';
		$this->author = 'PrestaShop';
		$this->need_instance = 1;
		
		$this->currencies = true;
		$this->currencies_mode = 'radio';
		
		parent::__construct();
		
		$this->displayName = $this->l('Bảo Kim');
		$this->description = $this->l('Accept payments by Bảokim.vn');		
	}

	public function install()
	{
		if (!parent::install()
			OR !$this->registerHook('payment')
			OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	
	public function uninstall()
	{
		if (!Configuration::deleteByName('BAOKIM_RECEIVER')
			OR !parent::uninstall())
			return false;
		return true;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;		
		return $this->display(__FILE__, 'baokim.tpl');		
	}
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'confirmation.tpl');
	}
	
	function validateOrderBK($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL, $dont_touch_amount = false)
	{
		if (!$this->active)
			return ;

		parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars);
	}
	
	public function getContent()
	{		
		$this->_html = '<h2>Bảo Kim</h2>';
		if (isset($_POST['submitbaokim']))
		{
			if (empty($_POST['receiver']))
				$this->_postErrors[] = $this->l('baokim receiver e-mail address is required.');
			elseif (!Validate::isEmail($_POST['receiver']))
				$this->_postErrors[] = $this->l('baokim receiver must be an e-mail address.');
			if (empty($_POST['checkout']))
				$this->_postErrors[] = $this->l('Address checkout is required.');
			if (empty($_POST['merchantID']))
				$this->_postErrors[] = $this->l('Merchant ID is required.');
			if (empty($_POST['secure_code']))
				$this->_postErrors[] = $this->l('Secure code is required.');
			if (empty($_POST['return_url']))
				$this->_postErrors[] = $this->l('Return_Url is required.');
			if (!sizeof($this->_postErrors))
			{
				Configuration::updateValue('baokim_RECEIVER', strval($_POST['receiver']));
				Configuration::updateValue('baokim_CHECKOUT', strval($_POST['checkout']));
				Configuration::updateValue('baokim_MERCHANT_SITE', strval($_POST['merchantID']));
				Configuration::updateValue('baokim_SECURE_CODE', strval($_POST['secure_code']));
				Configuration::updateValue('baokim_RETURN_URL', strval($_POST['return_url']));
				//Configuration::updateValue('baokim_HEADER', strval($_POST['header']));
				$this->displayConf();
			}
			else
				$this->displayErrors();
		}
		$this->displayFormSettings();
		return $this->_html;
	}
	
	public function displayConf()
	{
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Settings updated').'
		</div>';
	}

	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	public function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('baokim_RECEIVER','baokim_CHECKOUT','baokim_MERCHANT_SITE','baokim_SECURE_CODE','baokim_RETURN_URL','baokim_HEADER'));
		$receiver = array_key_exists('receiver', $_POST) ? $_POST['receiver'] : (array_key_exists('baokim_RECEIVER', $conf) ? $conf['baokim_RECEIVER'] : '');
		$checkout= array_key_exists('checkout', $_POST) ? $_POST['checkout'] : (array_key_exists('baokim_CHECKOUT', $conf) ? $conf['baokim_CHECKOUT'] : '');
		$merchantID = array_key_exists('merchantID', $_POST) ? $_POST['merchantID'] : (array_key_exists('baokim_MERCHANT_SITE', $conf) ? $conf['baokim_MERCHANT_SITE'] : '');
		$secure_code = array_key_exists('secure_code', $_POST) ? $_POST['secure_code'] : (array_key_exists('baokim_SECURE_CODE', $conf) ? $conf['baokim_SECURE_CODE'] : '');
		$return_url = array_key_exists('return_url', $_POST) ? $_POST['return_url'] : (array_key_exists('baokim_RETURN_URL', $conf) ? $conf['baokim_RETURN_URL'] : '');
		$header = array_key_exists('header', $_POST) ? $_POST['header'] : (array_key_exists('baokim_HEADER', $conf) ? $conf['baokim_HEADER'] : '');

		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear: both;">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('Email Đăng kí Bảo Kim:').'</label>
			<div><input type="text" size="40" name="receiver" value="'.$receiver.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Trang chủ của bạn:').'</label>
			<div><input type="text" size="40" name="checkout" value="'.$checkout.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Merchant ID').'</label>
			<div><input type="text" size="40" name="merchantID" value="'.$merchantID.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Secure Pass').'</label>
			<div><input type="text" size="33" name="secure_code" value="'.$secure_code.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Đường dẫn trả về<br/>(Nếu có)').'</label>
			<div><input type="text" size="40" name="return_url" value="'.$return_url.'" /></div>
			<br /><center><input type="submit" name="submitbaokim" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br /><br />';
		
	}
}
class BaoKimPayment 
{
	// URL checkout của baokim.vn
	private $baokim_url = 'https://www.baokim.vn/payment/customize_payment/order';

	
	public function createRequestUrl($order_id, $business, $total_amount, $shipping_fee, $tax_fee, $order_description, $url_success, $url_cancel, $url_detail,$merchant_id, $secure_pass)
	{
		// Mảng các tham số chuyển tới baokim.vn
		$params = array(
			'merchant_id'		=>	strval($merchant_id),
			'order_id'			=>	strval($order_id),
			'business'			=>	strval($business),
			'total_amount'		=>	strval($total_amount),
			'shipping_fee'		=>  strval($shipping_fee),
			'tax_fee'			=>  strval($tax_fee),
			'order_description'	=>	strval($order_description),
			'url_success'		=>	strtolower($url_success),
			'url_cancel'		=>	strtolower($url_cancel),
			'url_detail'		=>	strtolower($url_detail)
		);
		ksort($params);
		
		$str_combined = $secure_pass.implode('', $params);
		$params['checksum'] = strtoupper(md5($str_combined));
		
		//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = $this->baokim_url;
		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';			
		}
				
		// Tạo đoạn url chứa tham số
		$url_params = '';
		foreach ($params as $key=>$value)
		{
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}
		return $redirect_url.$url_params;
	}
	
	
}