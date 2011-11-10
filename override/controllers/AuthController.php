<?php
/*
* Override AuthController class
* Author : Truong Kim Phung <truongkimphung1982@yahoo.com >
* 
*/
class AuthController extends AuthControllerCore{

	public function setMedia()
	{
		FrontController::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'sign-in.css');
		Tools::addCSS(_THEME_CSS_DIR_.'register.css');	
		Tools::addJS(_THEME_JS_DIR_.'authen.js');		
	}
	
	protected function canonicalRedirection()
	{
		global $link, $cookie;
		$isSubmitAjax = Tools::getValue('Submitajax');
		
		if (Configuration::get('PS_CANONICAL_REDIRECT'))
		{
			// Automatically redirect to the canonical URL if needed
			if (isset($this->php_self) AND !empty($this->php_self))
			{
				// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
				$canonicalURL = $link->getPageLink($this->php_self, $this->ssl, $cookie->id_lang);
				if (!preg_match('/^'.Tools::pRegexp($canonicalURL, '/').'([&?].*)?$/', (($this->ssl AND Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) && !$isSubmitAjax)
				{
					header('HTTP/1.0 301 Moved');
					$params = '';
					$excludedKey = array('isolang', 'id_lang');
					foreach ($_GET as $key => $value)
						if (!in_array($key, $excludedKey))
							$params .= ($params == '' ? '?' : '&').$key.'='.$value;
					if (defined('_PS_MODE_DEV_') AND _PS_MODE_DEV_ AND $_SERVER['REQUEST_URI'] != __PS_BASE_URI__)
						die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$canonicalURL.$params.'">'.$canonicalURL.$params.'</a>');
					Tools::redirectLink($canonicalURL.$params);
				}
			}
		}
	}
	
	
	public function preProcess()
	{
		
		//parent::preProcess();	
			// ajax login hphuong edit
		if (Tools::getValue('Submitajax'))
		{
			$passwd = trim(Tools::getValue('passwd'));		
			$email = trim(Tools::getValue('email'));
			$isSuccess = 0;
			if (empty($email))
				{
					$this->errors[] = Tools::displayError('E-mail address required');
					$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
				}
			elseif (!Validate::isEmail($email))
				{
					$this->errors[] = Tools::displayError('Invalid e-mail address');
					$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
				}
			elseif (empty($passwd))
				{
					$this->errors[] = Tools::displayError('Password is required');
					$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
				}
			elseif (Tools::strlen($passwd) > 32)
				{
				$this->errors[] = Tools::displayError('Password is too long');
				$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
			}
			elseif (!Validate::isPasswd($passwd))
				{
				$this->errors[] = Tools::displayError('Sai mật khẩu');
				$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
				}
			else
			{
				$customer = new Customer();
				$authentication = $customer->getByEmail(trim($email), trim($passwd));
				if (!$authentication OR !$customer->id)
				{
					/* Handle brute force attacks */
					sleep(1);
					$this->errors[] = Tools::displayError('Sai tài khoản hoặc mật khẩu .');
					$return = array(
					'success' => $isSuccess,
					'errors' => $this->errors
					);
					echo json_encode($return);
					die;	
				}
				
				if ($authentication)
				{
					self::$cookie->id_customer = (int)($customer->id);
					self::$cookie->customer_lastname = $customer->lastname;
					self::$cookie->customer_firstname = $customer->firstname;
					self::$cookie->logged = 1;
					self::$cookie->is_guest = $customer->isGuest();
					self::$cookie->passwd = $customer->passwd;
					self::$cookie->email = $customer->email;
					if (Configuration::get('PS_CART_FOLLOWING') AND (empty(self::$cookie->id_cart) OR Cart::getNbProducts(self::$cookie->id_cart) == 0))
						self::$cookie->id_cart = (int)(Cart::lastNoneOrderedCart((int)($customer->id)));
					/* Update cart address */
					self::$cart->id_carrier = 0;
					self::$cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
					self::$cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
					self::$cart->update();
					$login = self::$smarty->fetch(_PS_THEME_DIR_.'login.tpl');
					$isSuccess = 1;
					$token=Tools::getToken(false);
					$return = array(
					'success' => $isSuccess,
					'html' => $login,
					'token'=>$token
					);
					echo json_encode($return);
					die;	
				}
					
			}
	
		}
		// ajax login hphuong edit
	
		if (self::$cookie->isLogged() AND !Tools::isSubmit('ajax'))
			Tools::redirect('my-account.php');

		if (Tools::getValue('create_account') || Tools::getValue('SubmitCreate'))
		{
			$create_account = 1;
			self::$smarty->assign('email_create', 1);
		}
	
		if (Tools::isSubmit('SubmitCreate') )
		{
			self::$smarty->assign('email_create', 1);
		}
		if (Tools::getValue('checkemail')==1)
		{
				if (Customer::customerExists(Tools::getValue('email'))) {
					echo 0;die;
				}
				echo 1;die;
		}
		if (Tools::isSubmit('submitAccount') OR Tools::isSubmit('submitGuestAccount'))
		{			
			$create_account = 1;
			if (Tools::isSubmit('submitAccount'))
				self::$smarty->assign('email_create', 1);
			
			
			
			/* New Guest customer */
			if (!Tools::getValue('is_new_customer', 1) AND !Configuration::get('PS_GUEST_CHECKOUT_ENABLED'))
				$this->errors[] = Tools::displayError('You cannot create a guest account.');
			if (!Tools::getValue('is_new_customer', 1))
				$_POST['passwd'] = md5(time()._COOKIE_KEY_);
			if (isset($_POST['guest_email']) AND $_POST['guest_email'])
				$_POST['email'] = $_POST['guest_email'];
			
			/* Preparing customer */
			$customer = new Customer();
			$lastnameAddress 	= Tools::getValue('lastname');
			$firstnameAddress 	= Tools::getValue('firstname');
			$passConfirm	  	= Tools::getValue('passwd-confirm');
			$pAgree	  			= Tools::getValue('pagree');
			$districtId			= Tools::getValue('district');		
			$_POST['lastname']  = Tools::getValue('customer_firstname');//Tools::getValue('customer_lastname');
			$_POST['firstname'] = Tools::getValue('customer_firstname');
			if( Tools::getValue('passwd') == '' && $pAgree == 0) {
				$strAlpha  = '123456789abcdefghijklmnopqrsstuvwxyz';
				$strRand = substr(str_shuffle($strAlpha), 0, 6);
				$_POST['passwd']	= $strRand;
			}
			if (!Tools::getValue('phone') AND !Tools::getValue('phone_mobile'))
				$this->errors[] = Tools::displayError('You must register at least one phone number');
			if(!Tools::getValue('district'))
				$this->errors[] = Tools::displayError('<strong>district</strong> is required');
			$this->errors = array_unique(array_merge($this->errors, $customer->validateControler()));
			/* Preparing address */
			$address = new Address();
			$_POST['lastname'] 	= $firstnameAddress;//$lastnameAddress;
			$_POST['firstname'] = $firstnameAddress;
			
			$address->id_customer = 1;
			$this->errors = array_unique(array_merge($this->errors, $address->validateControler()));			
			/** VINH UPDATE */
			if($pAgree == 1) {
				if (empty($passConfirm) )
					$this->errors[] = Tools::displayError('<strong>confirm password</strong> is required');
				elseif ($passConfirm != Tools::getValue('passwd'))
					$this->errors[] = Tools::displayError('<strong>password</strong> is not match.');
			}
			//$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is required.');
			/** VINH END UPDATE */
			
			/* US customer: normalize the address */
			if($address->id_country == Country::getByIso('US'))
			{
				include_once(_PS_TAASC_PATH_.'AddressStandardizationSolution.php');
				$normalize = new AddressStandardizationSolution;
				$address->address1 = $normalize->AddressLineStandardization($address->address1);
				$address->address2 = $normalize->AddressLineStandardization($address->address2);
			}			
			$zip_code_format = Country::getZipCodeFormat((int)(Tools::getValue('id_country')));			
			if (Country::getNeedZipCode((int)(Tools::getValue('id_country'))))
			{
				
				if (($postcode = Tools::getValue('postcode')) AND $zip_code_format)
				{
					$zip_regexp = '/^'.$zip_code_format.'$/ui';
					$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
					$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
					$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
					$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
					$zip_regexp = str_replace('C', Country::getIsoById((int)(Tools::getValue('id_country'))), $zip_regexp);
					if (!preg_match($zip_regexp, $postcode))
						$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.').'<br />'.Tools::displayError('Must be typed as follows:').' '.str_replace('C', Country::getIsoById((int)(Tools::getValue('id_country'))), str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
				}
				elseif ($zip_code_format)
					$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is required.');
				elseif ($postcode AND !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode))
					$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.');
			}
			
			if (Country::isNeedDniByCountryId($address->id_country) AND (!Tools::getValue('dni') OR !Validate::isDniLite(Tools::getValue('dni'))))
				$this->errors[] = Tools::displayError('Identification number is incorrect or has already been used.');
			elseif (!Country::isNeedDniByCountryId($address->id_country))
				$address->dni = NULL;
			if (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND !(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
				$this->errors[] = Tools::displayError('Invalid date of birth');
			if (!sizeof($this->errors))
			{
				if (Customer::customerExists(Tools::getValue('email')))
					$this->errors[] = Tools::displayError('An account is already registered with this e-mail, please fill in the password or request a new one.');
				if (Tools::isSubmit('newsletter'))
				{
					$customer->ip_registration_newsletter = pSQL(Tools::getRemoteAddr());
					$customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
				}

				$customer->birthday = (empty($_POST['years']) ? '' : (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']));

				if (!sizeof($this->errors))
				{
					if (!$country = new Country($address->id_country, Configuration::get('PS_LANG_DEFAULT')) OR !Validate::isLoadedObject($country))
						die(Tools::displayError());
					if ((int)($country->contains_states) AND !(int)($address->id_state))
						$this->errors[] = Tools::displayError('This country requires a state selection.');
					else
					{
						$customer->active = 1;
						/* New Guest customer */
						if (Tools::isSubmit('is_new_customer'))
							$customer->is_guest = !Tools::getValue('is_new_customer', 1);
						else
							$customer->is_guest = 0;
						if (!$customer->add())
							$this->errors[] = Tools::displayError('An error occurred while creating your account.');
						else
						{
												
							$address->id_customer = (int)($customer->id);
							if (!$address->add())
								$this->errors[] = Tools::displayError('An error occurred while creating your address.');
							else
							{
								
								if (!$customer->is_guest)
								{
									//'{lastname}' => $customer->lastname, 
									if (!Mail::Send((int)(self::$cookie->id_lang), 'account', Mail::l('Welcome!'),
									array('{firstname}' => $customer->firstname, '{email}' => $customer->email, '{passwd}' => Tools::getValue('passwd')), $customer->email, $customer->firstname.' '.$customer->lastname))
										{//$this->errors[] = Tools::displayError('Cannot send email');
									}
								}
								
								
								self::$smarty->assign('confirmation', 1);
								self::$cookie->id_customer = (int)($customer->id);
								#self::$cookie->customer_lastname = $customer->lastname;
								self::$cookie->customer_firstname = $customer->firstname;
								self::$cookie->passwd = $customer->passwd;
								self::$cookie->logged = 1;
								self::$cookie->email = $customer->email;
								self::$cookie->is_guest = !Tools::getValue('is_new_customer', 1);
								/* Update cart address */
								self::$cart->secure_key = $customer->secure_key;
								self::$cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
								self::$cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
								self::$cart->update();
								Module::hookExec('createAccount', array(
									'_POST' => $_POST,
									'newCustomer' => $customer
								));
								if (Tools::isSubmit('ajax'))
								{
									$return = array(
										'hasError' => !empty($this->errors),
										'errors' => $this->errors,
										'isSaved' => true,
										'id_customer' => (int)self::$cookie->id_customer,
										'id_address_delivery' => self::$cart->id_address_delivery,
										'id_address_invoice' => self::$cart->id_address_invoice,
										'token' => Tools::getToken(false)
									);
									die(Tools::jsonEncode($return));
								}
								if ($back = Tools::getValue('back'))
									Tools::redirect($back);
								Tools::redirect('my-account.php');
							}
						}
					}
				}
			}
			if (sizeof($this->errors))
			{
				if (!Tools::getValue('is_new_customer'))
					unset($_POST['passwd']);
				if (Tools::isSubmit('ajax'))
				{
					$return = array(
						'hasError' => !empty($this->errors),
						'errors' => $this->errors,
						'isSaved' => false,
						'id_customer' => 0
					);
					die(Tools::jsonEncode($return));
				}
			}
		}
		
		$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
							SELECT `provinceid`, `name`
							FROM `'._DB_PREFIX_.'provinces` 							
							ORDER BY `iseq` ASC');
		$intProvinceId	= isset($_POST['city']) ? $_POST['city'] : isset($provinces[0]['provinceid']) ? $provinces[0]['provinceid'] : 0;		
		$districts		= Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
																			SELECT `districtid`, `name`
																			FROM `'._DB_PREFIX_.'districts` 							
																			WHERE `province`=' . $intProvinceId . '
																			ORDER BY `iseq` ASC');		
		if (Tools::isSubmit('SubmitLogin'))
		{
			Module::hookExec('beforeAuthentication');
			$passwd = trim(Tools::getValue('passwd'));
			$email = trim(Tools::getValue('email'));
			if (empty($email))
				$this->errors[] = Tools::displayError('E-mail address required');
			elseif (!Validate::isEmail($email))
				$this->errors[] = Tools::displayError('Invalid e-mail address');
			elseif (empty($passwd))
				$this->errors[] = Tools::displayError('Password is required');
			elseif (Tools::strlen($passwd) > 32)
				$this->errors[] = Tools::displayError('Password is too long');
			elseif (!Validate::isPasswd($passwd))
				$this->errors[] = Tools::displayError('Invalid password');
			else
			{
				$customer = new Customer();
				$authentication = $customer->getByEmail(trim($email), trim($passwd));
				if (!$authentication OR !$customer->id)
				{
					/* Handle brute force attacks */
					sleep(1);
					$this->errors[] = Tools::displayError('Authentication failed');
				}
				else
				{
					self::$cookie->id_customer = (int)($customer->id);
					self::$cookie->customer_lastname = $customer->lastname;
					self::$cookie->customer_firstname = $customer->firstname;
					self::$cookie->logged = 1;
					self::$cookie->is_guest = $customer->isGuest();
					self::$cookie->passwd = $customer->passwd;
					self::$cookie->email = $customer->email;
					
					if (Configuration::get('PS_CART_FOLLOWING') AND (empty(self::$cookie->id_cart) OR Cart::getNbProducts(self::$cookie->id_cart) == 0))
						self::$cookie->id_cart = (int)(Cart::lastNoneOrderedCart((int)($customer->id)));
					/* Update cart address */
					self::$cart->id_carrier = 0;
					self::$cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
					self::$cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
					self::$cart->update();
					
					Module::hookExec('authentication');
					if (!Tools::isSubmit('ajax'))
					{
						if ($back = Tools::getValue('back'))
							Tools::redirect($back);
						Tools::redirect('my-account.php');
					}
				}
			}
			if (Tools::isSubmit('ajax'))
			{
				$return = array(
					'hasError' => !empty($this->errors),
					'errors' => $this->errors,
					'token' => Tools::getToken(false)
				);
				die(Tools::jsonEncode($return));
			}
		}

		if (isset($create_account))
		{
			/* Select the most appropriate country */
			if (isset($_POST['id_country']) AND is_numeric($_POST['id_country']))
				$selectedCountry = (int)($_POST['id_country']);
			/* FIXME : language iso and country iso are not similar,
			 * maybe an associative table with country an language can resolve it,
			 * But for now it's a bug !
			 * @see : bug #6968
			 * @link:http://www.prestashop.com/bug_tracker/view/6968/
			elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$array = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if (Validate::isLanguageIsoCode($array[0]))
				{
					$selectedCountry = Country::getByIso($array[0]);
					if (!$selectedCountry)
						$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
				}
			}*/
			if (!isset($selectedCountry))
				$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
			$countries = Country::getCountries((int)(self::$cookie->id_lang), true);

			self::$smarty->assign(array(
				'countries' => $countries,
				'sl_country' => (isset($selectedCountry) ? $selectedCountry : 0),
				'vat_management' => Configuration::get('VATNUMBER_MANAGEMENT')
			));

			/* Call a hook to display more information on form */
			self::$smarty->assign(array(
				'HOOK_CREATE_ACCOUNT_FORM' => Module::hookExec('createAccountForm'),
				'HOOK_CREATE_ACCOUNT_TOP' => Module::hookExec('createAccountTop')
			));
		}

		/* Generate years, months and days */
		if (isset($_POST['years']) AND is_numeric($_POST['years']))
			$selectedYears = (int)($_POST['years']);
		$years = Tools::dateYears();
		if (isset($_POST['months']) AND is_numeric($_POST['months']))
			$selectedMonths = (int)($_POST['months']);
		$months = Tools::dateMonths();

		if (isset($_POST['days']) AND is_numeric($_POST['days']))
			$selectedDays = (int)($_POST['days']);
		$days = Tools::dateDays();
			
		self::$smarty->assign(array(			
			'provinces' => $provinces,
			'districts' => $districts,
			'years' => $years,
			'sl_year' => (isset($selectedYears) ? $selectedYears : 0),
			'months' => $months,
			'sl_month' => (isset($selectedMonths) ? $selectedMonths : 0),
			'days' => $days,
			'sl_day' => (isset($selectedDays) ? $selectedDays : 0)
		));
		self::$smarty->assign('newsletter', (int)Module::getInstanceByName('blocknewsletter')->active);
		
	}
	public function displayContent()
	{		
		FrontController::displayContent();
		$this->processAddressFormat();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'authentication.tpl'));
	}
}
