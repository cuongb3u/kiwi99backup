<?php
class NganLuong extends PaymentModule
{
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'nganluong';
		$this->tab = 'payments_gateways';
		$this->version = '';
		
		$this->currencies = true;
		$this->currencies_mode = 'radio';

        parent::__construct();

		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Ngân Lượng');
        $this->description = $this->l('Accepts payments by NgânLượng.vn');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
		if (Configuration::get('NGANLUONG_RECEIVER') == 'nganluong@prestashop.com')
			$this->warning = $this->l('You are currently using the default NgânLượng.vn email address, you need to use your own email address');
		if ($_SERVER['SERVER_NAME'] == 'localhost')
			$this->warning = $this->l('Your are in localhost, Ngân Lượng we can\'t validate order');
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
		if (!Configuration::deleteByName('NGANLUONG_RECEIVER')
			OR !parent::uninstall())
			return false;
		return true;
	}

	public function getContent()
	{
		$this->_html = '<h2>NgânLượng</h2>';
		if (isset($_POST['submitnganluong']))
		{
			if (empty($_POST['receiver']))
				$this->_postErrors[] = $this->l('NganLuong receiver e-mail address is required.');
			elseif (!Validate::isEmail($_POST['receiver']))
				$this->_postErrors[] = $this->l('NganLuong receiver must be an e-mail address.');
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
				Configuration::updateValue('NGANLUONG_RECEIVER', strval($_POST['receiver']));
				Configuration::updateValue('NGANLUONG_CHECKOUT', strval($_POST['checkout']));
				Configuration::updateValue('NGANLUONG_MERCHANT_SITE', strval($_POST['merchantID']));
				Configuration::updateValue('NGANLUONG_SECURE_CODE', strval($_POST['secure_code']));
				Configuration::updateValue('NGANLUONG_RETURN_URL', strval($_POST['return_url']));
				Configuration::updateValue('NGANLUONG_HEADER', isset($_POST['header']) ? strval($_POST['header']) : null);
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
		$conf = Configuration::getMultiple(array('NGANLUONG_RECEIVER','NGANLUONG_CHECKOUT','NGANLUONG_MERCHANT_SITE','NGANLUONG_SECURE_CODE','NGANLUONG_RETURN_URL','NGANLUONG_HEADER'));
		$receiver = array_key_exists('receiver', $_POST) ? $_POST['receiver'] : (array_key_exists('NGANLUONG_RECEIVER', $conf) ? $conf['NGANLUONG_RECEIVER'] : '');
		$checkout= array_key_exists('checkout', $_POST) ? $_POST['checkout'] : (array_key_exists('NGANLUONG_CHECKOUT', $conf) ? $conf['NGANLUONG_CHECKOUT'] : '');
		$merchantID = array_key_exists('merchantID', $_POST) ? $_POST['merchantID'] : (array_key_exists('NGANLUONG_MERCHANT_SITE', $conf) ? $conf['NGANLUONG_MERCHANT_SITE'] : '');
		$secure_code = array_key_exists('secure_code', $_POST) ? $_POST['secure_code'] : (array_key_exists('NGANLUONG_SECURE_CODE', $conf) ? $conf['NGANLUONG_SECURE_CODE'] : '');
		$return_url = array_key_exists('return_url', $_POST) ? $_POST['return_url'] : (array_key_exists('NGANLUONG_RETURN_URL', $conf) ? $conf['NGANLUONG_RETURN_URL'] : '');
		$header = array_key_exists('header', $_POST) ? $_POST['header'] : (array_key_exists('NGANLUONG_HEADER', $conf) ? $conf['NGANLUONG_HEADER'] : '');

		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear: both;">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('Ngân lượng  Email').'</label>
			<div><input type="text" size="40" name="receiver" value="'.$receiver.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Ngân lượng checkout').'</label>
			<div><input type="text" size="40" name="checkout" value="'.$checkout.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Merchant ID').'</label>
			<div><input type="text" size="40" name="merchantID" value="'.$merchantID.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Mật khẩu giao tiếp').'</label>
			<div><input type="text" size="33" name="secure_code" value="'.$secure_code.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Return_Url').'</label>
			<div><input type="text" size="40" name="return_url" value="'.$return_url.'" /></div>
			<br /><center><input type="submit" name="submitnganluong" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br /><br />';
		
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'nganluong.tpl');
	}

	public function hookPaymentReturn($params)
	{ 
		if (!$this->active)
			return ;
		return $this->display(__FILE__, 'confirmation.tpl');
	}
	
	function validateOrderNL($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL, $dont_touch_amount = false)
	{
		if (!$this->active)
			return ;

		parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars);
	}
	
}
class NL_Checkout
{
	// URL chheckout của nganluong.vn
	private $nganluong_url;

	// Mã merchante site 
	private $merchant_site_code;	// Biến này được nganluong.vn cung cấp khi bạn đăng ký merchant site

	// Mật khẩu bảo mật
	private $secure_pass; // Biến này được nganluong.vn cung cấp khi bạn đăng ký merchant site

	
	function __construct($nganluong_url,$merchant_site_code,$secure_pass)
	{
	     $this->nganluong_url=$nganluong_url;
		 $this->merchant_site_code=$merchant_site_code;
		 $this->secure_pass=$secure_pass;
	}
	
	//Hàm xây dựng url, trong đó có tham số mã hóa (còn gọi là public key)
	public function buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price)
	{
		
		// Mảng các tham số chuyển tới nganluong.vn
		$arr_param = array(
			'merchant_site_code'=>	strval($this->merchant_site_code),
			'return_url'		=>	strtolower(urlencode($return_url)),
			'receiver'			=>	strval($receiver),
			'transaction_info'	=>	strval($transaction_info),
			'order_code'		=>	strval($order_code),
			'price'				=>	strval($price)
		);
		$secure_code ='';
		$secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
		$arr_param['secure_code'] = md5($secure_code);
		
		/* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
		$redirect_url = $this->nganluong_url;
		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';			
		}
				
		/* Bước 3. tạo url*/
		$url = '';
		foreach ($arr_param as $key=>$value)
		{
			if ($url == '')
				$url .= $key . '=' . $value;
			else
				$url .= '&' . $key . '=' . $value;
		}
		
		return $redirect_url.$url;
	}
	
	/*Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn*/
	
	public function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
	{
		// Tạo mã xác thực từ chủ web
		$str = '';
		$str .= ' ' . strval($transaction_info);
		$str .= ' ' . strval($order_code);
		$str .= ' ' . strval($price);
		$str .= ' ' . strval($payment_id);
		$str .= ' ' . strval($payment_type);
		$str .= ' ' . strval($error_text);
		$str .= ' ' . strval($this->merchant_site_code);
		$str .= ' ' . strval($this->secure_pass);

        // Mã hóa các tham số
		$verify_secure_code = '';
		$verify_secure_code = md5($str);
		
		// Xác thực mã của chủ web với mã trả về từ nganluong.vn
		if ($verify_secure_code === $secure_code) return true;
		
		return false;
	}
}
