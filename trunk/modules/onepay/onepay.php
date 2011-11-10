<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
class OnePay extends PaymentModule
{	
	private	$_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'onepay';
		$this->tab = 'payments_gateways';
		$this->version = '';
		$this->author = 'PrestaShop';
		$this->need_instance = 1;
		
		$this->currencies = true;
		$this->currencies_mode = 'radio';
		
		parent::__construct();
		
		$this->displayName = $this->l('Onepay');
		$this->description = $this->l('Accept payments by Onepay.vn (Vietcombank, ACB, Eximbank, Dong A Bank ....)');		
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
		if (!Configuration::deleteByName('ONEPAY_RECEIVER')
			OR !parent::uninstall())
			return false;
		return true;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;		
		return $this->display(__FILE__, 'onepay.tpl');		
	}
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'confirmation.tpl');
	}
	
	function validateOrderOP($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL, $dont_touch_amount = false)
	{
		if (!$this->active)
			return ;

		parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars);
	}
	
	public function getContent()
	{		
		$this->_html = '<h2>Onepay.vn setting</h2>';
		if (isset($_POST['submitonepay']))
		{
			if (empty($_POST['vpc_Merchant']))
				$this->_postErrors[] = $this->l('Merchant ID is required.');			
			if (empty($_POST['vpc_AccessCode']))
				$this->_postErrors[] = $this->l('Merchant CODE is required.');
			/*if (empty($_POST['vpc_ReturnURL']))
				$this->_postErrors[] = $this->l('Receipt ReturnURL is required.');*/
			if (!sizeof($this->_postErrors))
			{				
				Configuration::updateValue('vpc_Merchant'	, strval($_POST['vpc_Merchant']));
				Configuration::updateValue('vpc_AccessCode'	, strval($_POST['vpc_AccessCode']));
				Configuration::updateValue('vpc_Amount'		, 100);
				Configuration::updateValue('vpc_ReturnURL'	, strval($_POST['vpc_ReturnURL']));
				Configuration::updateValue('vpc_Version'	, 1);
				Configuration::updateValue('vpc_Command'	, 'pay');
				Configuration::updateValue('vpc_Locale'		, 'vn');
				Configuration::updateValue('vpc_Currency'	, 'VND');				
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
		$vpc_Merchant 	= isset($_POST['vpc_Merchant']) ? $_POST['vpc_Merchant'] : (isset($conf['vpc_Merchant']) ? $conf['vpc_Merchant'] : '');
		$vpc_AccessCode	= isset($_POST['vpc_AccessCode']) ? $_POST['vpc_AccessCode'] : (isset($conf['vpc_AccessCode']) ? $conf['vpc_AccessCode'] : '');
		$vpc_ReturnURL 	= isset($_POST['vpc_ReturnURL']) ? $_POST['vpc_ReturnURL'] : ( isset($conf['vpc_ReturnURL']) ? $conf['vpc_ReturnURL'] : '');
		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="clear: both;">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('Merchant ID:').'</label>
			<div><input type="text" size="40" name="vpc_Merchant" value="'.$vpc_Merchant.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Merchant AccessCode:').'</label>
			<div><input type="text" size="40" name="vpc_AccessCode" value="'.$vpc_AccessCode.'" /></div>
			<div>&nbsp</div>
			<label>'.$this->l('Receipt ReturnURL:').'</label>
			<div><input type="text" size="40" name="vpc_ReturnURL" value="'.$vpc_ReturnURL.'" /></div>
			<br /><center><input type="submit" name="submitonepay" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form><br /><br />';
		
	}
}
class OnePayMethod 
{
	// URL checkout của onepay
	private $onepay_url 	= 'http://mtf.onepay.vn/onecomm-pay/vpc.op';
	private function getProvinceName($intId) {
		if((int)$intId > 0) {
			$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
										SELECT `name`
										FROM `'._DB_PREFIX_.'provinces` 
										WHERE `provinceid`=' . $intId . '							
										ORDER BY `iseq` ASC');
			return isset($provinces[0]['name']) ? $provinces[0]['name'] : ''; 
		}
		return $intId;

	}
	public function createRequestUrl(	
										$merchant_id, $merchant_code, $order_id, $total_amount, $return_url,
										$version, $command, $locale, $currency, $address, $customer
									)
	{
		$ip 				= (getenv('HTTP_X_FORWARDED_FOR')) ?  getenv('HTTP_X_FORWARDED_FOR') :  getenv('REMOTE_ADDR');
		$shipping_addr		= $this->UTF8Deaccent($address->address1);
		$shipping_prov		= '';
		$shipping_city		= $this->UTF8Deaccent($this->getProvinceName($address->city));
		$shipping_vina		= $this->UTF8Deaccent($this->getProvinceName($address->country));
		$customize_mobi		= $address->phone;
		$customize_email	= $customer->email;
		$customize_id		= $customer->id;
		$secure_secret 		= "A3EFDFABA8653DF2342E8DAC29B51AF0";
		$md5HashData		= $secure_secret;
		$params = array(
			'vpc_Merchant'			=>	strval($merchant_id),
			'vpc_AccessCode'		=>	strval($merchant_code),
			'vpc_MerchTxnRef'		=>	md5(strval($order_id)),
			'vpc_OrderInfo'			=>	strval($order_id),
			'vpc_Amount'			=>	strval($total_amount),
			'vpc_ReturnURL'			=>	strval($return_url),			
			'vpc_Version'			=>  strval($version),
			'vpc_Command'			=>  strval($command),
			'vpc_Locale'			=>	strval($locale),
			'vpc_Currency'			=>	strval($currency),
			'vpc_TicketNo'			=>	strval($ip),
			'vpc_SHIP_Street01'		=>	strval($shipping_addr),
			'vpc_SHIP_Provice'		=>	strval($shipping_prov),
			'vpc_SHIP_City'			=>	strval($shipping_city),
			'vpc_SHIP_Country'		=>	strval($shipping_vina),
			'vpc_Customer_Phone'	=>	strval($customize_mobi),
			'vpc_Customer_Email'	=>	strval($customize_email),
			'vpc_Customer_Id'		=>	strtolower($customize_id)
		);
		ksort($params);
		
		/*$str_combined = $secure_pass.implode('', $params);
		$params['checksum'] = strtoupper(md5($str_combined));*/
		
		//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = $this->onepay_url;
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
			$md5HashData .= $value;
		}
		if (strlen($secure_secret) > 0) {
			$url_params .= "&vpc_SecureHash=" . strtoupper(md5($md5HashData));
		}
		unset($md5HashData);
		
		return $redirect_url.$url_params.'';
	}
	public function UTF8Deaccent($str)
	{
    	$trans = array
    	(
			// C1 Controls and Latin-1 Supplement (0080 - 00FF)
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'È' => 'E',
			'É' => 'E', 'Ê' => 'E', 'Ì' => 'I', 'Í' => 'I',
			'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
			'Ù' => 'U', 'Ú' => 'U', 'à' => 'a', 'á' => 'a',
			'â' => 'a', 'ã' => 'a', 'è' => 'e', 'é' => 'e',
			'ê' => 'e', 'ì' => 'i', 'í' => 'i', 'ò' => 'o',
			'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ù' => 'u',
			'ú' => 'u', 'ý' => 'y',
			// Latin Extended-A (0100 - 017F)
			'Ă' => 'A', 'ă' => 'a', 'Đ' => 'D', 'đ' => 'd',
			'Ĩ' => 'I', 'ĩ' => 'i',	'Ũ' => 'U', 'ũ' => 'u',
			// Latin Extended-B (0180 - 024F)
			'Ơ' => 'O', 'ơ' => 'o',	'Ư' => 'U', 'ư' => 'u',
			// Latin Extended Additional (1E80 - 1EFF)
			'Ạ' => 'A', 'ạ' => 'a',	'Ả' => 'A', 'ả' => 'a',
			'Ấ' => 'A', 'ấ' => 'a',	'Ầ' => 'A', 'ầ' => 'a',
			'Ẩ' => 'A', 'ẩ' => 'a',	'Ẫ' => 'A', 'ẫ' => 'a',
			'Ậ' => 'A', 'ậ' => 'a', 'Ắ' => 'A', 'ắ' => 'a',
			'Ằ' => 'A', 'ằ' => 'a', 'Ẳ' => 'A', 'ẳ' => 'a',
			'Ẵ' => 'A', 'ẵ' => 'a', 'Ặ' => 'A', 'ặ' => 'a',
			'Ẹ' => 'E', 'ẹ' => 'e', 'Ẻ' => 'E', 'ẻ' => 'e',
			'Ẽ' => 'E', 'ẽ' => 'e', 'Ế' => 'E', 'ế' => 'e',
			'Ề' => 'E', 'ề' => 'e', 'Ể' => 'E', 'ể' => 'e',
			'Ễ' => 'E', 'ễ' => 'e', 'Ệ' => 'E', 'ệ' => 'e',
			'Ỉ' => 'I', 'ỉ' => 'i', 'Ị' => 'I', 'ị' => 'i',
			'Ọ' => 'O', 'ọ' => 'o', 'Ỏ' => 'O', 'ỏ' => 'o',
			'Ố' => 'O', 'ố' => 'o', 'Ồ' => 'O', 'ồ' => 'o',
			'Ổ' => 'O', 'ổ' => 'o', 'Ỗ' => 'O', 'ỗ' => 'o',
			'Ộ' => 'O', 'ộ' => 'o', 'Ớ' => 'O', 'ớ' => 'o',
			'Ờ' => 'O', 'ờ' => 'o', 'Ở' => 'O', 'ở' => 'o',
			'Ỡ' => 'O', 'ỡ' => 'o', 'Ợ' => 'O', 'ợ' => 'o',
			'Ụ' => 'U', 'ụ' => 'u', 'Ủ' => 'U', 'ủ' => 'u',
			'Ứ' => 'U', 'ứ' => 'u', 'Ừ' => 'U', 'ừ' => 'u',
			'Ử' => 'U', 'ử' => 'u', 'Ữ' => 'U', 'ữ' => 'u',
			'Ự' => 'U', 'ự' => 'u', 'Ỳ' => 'Y', 'ỳ' => 'y',
			'Ỵ' => 'Y', 'ỵ' => 'y',	'Ỷ' => 'Y', 'ỷ' => 'y',
			'Ỹ' => 'Y', 'ỹ' => 'y'
		);
		return strtr($str, $trans);
	}
	
}