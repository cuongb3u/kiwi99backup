<?php
/*
	providers login using OAUTH
	*/

class ProvidersControllerCore extends FrontController
{
	//public $auth = true;
	//public $php_self = 'providers.php';
	//public $authRedirection = 'providers.php';
	//public $ssl = true;
	//public $ssl = true;
	//public $php_self = 'providers.php';
	public function run() 
	{
		$this->init();
		$this->preProcess();
		$this->displayHeader();
		//$this->process();
		$this->displayContent();
		$this->displayFooter();	
	}
	
	public function init()
	{
		parent::init();
	}
	
	public function displayHeader()
	{
		global $css_files, $js_files;

		if (!self::$initialized)
			$this->init();

		// P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
		header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

		/* Hooks are volontary out the initialize array (need those variables already assigned) */

		if ((Configuration::get('PS_CSS_THEME_CACHE') OR Configuration::get('PS_JS_THEME_CACHE')) AND is_writable(_PS_THEME_DIR_.'cache'))
		{
			// CSS compressor management
			if (Configuration::get('PS_CSS_THEME_CACHE'))
				Tools::cccCss();

			//JS compressor management
			if (Configuration::get('PS_JS_THEME_CACHE'))
				Tools::cccJs();
		}

		//self::$smarty->assign('css_files', $css_files);
		//self::$smarty->assign('js_files', array_unique($js_files));
		self::$smarty->display(_PS_THEME_DIR_.'header_popup.tpl');
	}
	
	public function displayFooter()
	{

		if (!self::$initialized)
			$this->init();
		self::$smarty->display(_PS_THEME_DIR_.'footer_popup.tpl');
	}
	
	public function setMedia()
	{
		parent::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'popup_register_form.css');
		//Tools::addCSS(_THEME_CSS_DIR_.'sign-in.css');
		//Tools::addJS(_THEME_JS_DIR_.'authen.js');		
		//Tools::addJS(array(_THEME_JS_DIR_.'tools/statesManagement.js', _PS_JS_DIR_.'jquery/jquery-typewatch.pack.js'));
	}
	
	public function preProcess()
	{
		parent::preProcess();
		
		//set up a connection with Facebook
		$facebook = new Facebook(array(
			  'appId'  => '301573236520799',
			  'secret' => '4994ec8f7ce0f1aa64b61f5bb555bfa5',));

 		$user = $facebook->getUser();
 		
 		// set up a a connection for yahoo  
 				/*define("CONSUMER_KEY","dj0yJmk9RHFid1RTcVlkN21XJmQ9WVdrOVFrOU9OakZoTkhNbWNHbzlNVGd6TXpFM01qZzJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD04Yg--");  
      
     	define("CONSUMER_SECRET","3c4fcc7f47cd4b9a2daaae5bd66a152dcd7dd21e");  
       
      	define("APP_ID","BON61a4s");   
 */		
 		// for security only
 		/*global $cookie;
 		$security = false;
 		if (Tools::getIsset('popupGG') || Tools::getIsset('popupFB') || Tools::getIsset('popupYH')){
 			$security = false;
 		}
 		if ($cookie->isLogged() && $security){
 			Tools::redirectLink('http://bkiter.net/prestashop');
 		}
 		*/
 		
 		// connect to Facebook
		if (Tools::getIsset('popupFB')) {		
 			$params = array(
			  scope => 'user_birthday, email ',
			  redirect_uri => 'http://kiwi99.com/en/providers.php?FBreturn',
			  display => 'popup'
			);
 			$loginUrl = $facebook->getLoginUrl($params);
			Tools::redirectLink($loginUrl);			
		}	
		
		// connecto Google
		if (Tools::getIsset('popupGG')) {
			define('CALLBACK_URL', 'http://kiwi99.com/providers.php?GGreturn');			
				function gooleAuthenticate() {
			    // Creating new instance
			    $openid = new LightOpenID;
			    $openid->identity = 'https://www.google.com/accounts/o8/id';
			    //setting call back url
			    $openid->returnUrl = CALLBACK_URL;
			    //finding open id end point from google
			    $endpoint = $openid->discover('https://www.google.com/accounts/o8/id');
			    $fields =
			            '?openid.ns=' . urlencode('http://specs.openid.net/auth/2.0') .
			            '&openid.return_to=' . urlencode($openid->returnUrl) .
			            '&openid.claimed_id=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
			            '&openid.identity=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
			            '&openid.mode=' . urlencode('checkid_setup') .
			            '&openid.ns.ax=' . urlencode('http://openid.net/srv/ax/1.0') .
			            '&openid.ax.mode=' . urlencode('fetch_request') .
			            '&openid.ax.required=' . urlencode('email,firstname,lastname') .
			            '&openid.ax.type.firstname=' . urlencode('http://axschema.org/namePerson/first') .
			            '&openid.ax.type.lastname=' . urlencode('http://axschema.org/namePerson/last') .
			            '&openid.ax.type.email=' . urlencode('http://axschema.org/contact/email').
			            '&openid.ns.ui=' . urlencode('http://specs.openid.net/extensions/ui/1.0').
			            '&openid.ui.mode=' . urlencode('popup');
			            
			          
			            
			    header('Location: ' . $endpoint . $fields);
			}
				gooleAuthenticate();			
		}	
		
		// Connect to Yahoo
		if (Tools::getIsset('popupYH')) {
      		/*
      		define('CALLBACK_URL', 'http://yupplease.com/en/providers.php?YHreturn');    		
      		YahooSession::clearSession();
      		$auth_url = YahooSession::createAuthorizationUrl(CONSUMER_KEY, CONSUMER_SECRET, CALLBACK_URL);  
      		ToolS::redirectLink($auth_url);	
      		*/
      		define('CALLBACK_URL', 'http://kiwi99.com/en/providers.php?YHreturn');			
				function YahooAuthenticate() {
			    // Creating new instance
			    $openid = new LightOpenID;
			    $openid->identity = 'https://me.yahoo.com';
			    //setting call back url
			    $openid->returnUrl = CALLBACK_URL;
			    $openid->required = array('contact/email','namePerson');//
				$openid->optional = array('namePerson/friendly','birthDate','person/gender');//
			    header('Location: ' . $openid->authUrl());
			}
				YahooAuthenticate();
      		
		}	
		$email = false;
		
		// get user info via yahoo
		if (Tools::getIsset('YHreturn')) {
			$_POST['email'] = $_GET['openid_ax_value_email'];	
			$_POST['customer_firstname'] = $_GET['openid_ax_value_fullname'];	
			$_POST['customer_lastname'] = $_GET['openid_ax_value_nickname'];
			$email = trim( $_GET['openid_ax_value_email']	);
			if ($_GET['openid_ax_value_gender'] == 'M'){
				$_POST['id_gender']= 1;	
			}	
			elseif ($_GET['openid_ax_value_gender'] == 'F'){
				$_POST['id_gender']= 2;	
			}	
			/*
			$session = YahooSession::requireSession(CONSUMER_KEY,CONSUMER_SECRET,APP_ID); 		
			$user = $session->getSessionedUser(); 
			$profileYH = $user->getProfile();		
			self::$smarty->assign('profileYH', $profileYH);			 
			$_POST['customer_firstname']= $profileYH->givenName;	
			$_POST['customer_lastname']= $profileYH->familyName;		
			$_POST['email']= $profileYH->emails['0']->handle;	
			$_POST['address1']= $profileYH->addresses['0']->street;	
			$_POST['city']= $profileYH->addresses['0']->city;	
			if ($profileYH->gender == 'M'){
				$_POST['id_gender']= 1;	
			}	
			elseif ($profileYH->gender == 'F'){
				$_POST['id_gender']= 2;	
			}				
			$selectedYears = $profileYH->birthYear;
			list($selectedMonths , $selectedDays ) =explode("/",  $profileYH->birthdate);
			$email = trim($profileYH->emails['0']->handle);
			*/
		}
				
		// get user info via Facebook
		if (Tools::getIsset('FBreturn')) {
			//sleep(1);
			$user_profile = $facebook->api('/me');
			self::$smarty->assign('userFB', $user_profile);	
			self::$smarty->assign('emailFB', $user_profile['email']);	
			self::$smarty->assign('firstnameFB', $user_profile['first_name']);	
			self::$smarty->assign('lastnameFB', $user_profile['last_name']);
			self::$smarty->assign('genderFB', $user_profile['gender']);		
			self::$smarty->assign('birthdayFB', $user_profile['birthday']);	
			$_POST['customer_firstname']= $user_profile['first_name'];	
			$_POST['customer_lastname']= $user_profile['last_name'];
			$_POST['email']= $user_profile['email'];	
			
			if ($user_profile['gender'] == 'male'){
				$_POST['id_gender']= 1;	
			}	
			elseif ($user_profile['gender'] == 'female'){
				$_POST['id_gender']= 2;	
			}	
			
			$selectedDays = substr($user_profile['birthday'], 3, 2); 
			$selectedMonths = substr($user_profile['birthday'], 0, 2); 
			$selectedYears = substr($user_profile['birthday'], 6, 4); 
			$email = trim($user_profile['email']);
		}	
		
		// get user info via Google
		if(Tools::getIsset('GGreturn')){
			$_POST['email'] = $_GET['openid_ext1_value_email'];	
			$_POST['customer_firstname'] = $_GET['openid_ext1_value_firstname'];	
			$_POST['customer_lastname'] = $_GET['openid_ext1_value_lastname'];
			$email = trim( $_GET['openid_ext1_value_email']);
		}
		
		/* Receive email from providers, checkec in db
		/* If email exist, auto login to user account
		/* Otherwise, show the user register form
			 */
			
		$customer = new Customer();			
		$authentication = $customer->getByEmail(trim($email),NULL);
		
		// registered user, auto login to user's account
		if (!$authentication OR !$customer->id){
			/* Handle brute force attacks */
			//sleep(1);
			//$this->errors[] = Tools::displayError('Authentication failed');
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
			function closePopup(){
				?>
				<script type="text/javascript"> 
					
					window.opener.location.reload(); 
					window.close();
					
				</script>  
				<?php
			}	
			closePopup();
			exit;					
		}
			
		// unregistered user, show register form
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
		//print_r($districts) ; die;																
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
					
		// handle the submitted form 
		if (Tools::getValue('create_account') || Tools::getValue('SubmitCreate'))
		{
			$create_account = 1;
			self::$smarty->assign('email_create', 1);
		}
	
		if (Tools::isSubmit('SubmitCreate') )
		{
			
			self::$smarty->assign('email_create', 1);
		}

		if (isset($create_account))
		{
			/* Select the most appropriate country */
			if (isset($_POST['id_country']) AND is_numeric($_POST['id_country']))
				$selectedCountry = (int)($_POST['id_country']);
			if (!isset($selectedCountry))
				$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
			$countries = Country::getCountries((int)(self::$cookie->id_lang), true);

			self::$smarty->assign(array(
				'countries' => $countries,
				'districts' => $districts,
				'sl_country' => (isset($selectedCountry) ? $selectedCountry : 0),
				'vat_management' => Configuration::get('VATNUMBER_MANAGEMENT')
			));
		}

		
		if (Tools::getValue('submitAccount')){
			//set up a new customer			
			$create_account = 1;
			if (!Tools::getValue('is_new_customer', 1) AND !Configuration::get('PS_GUEST_CHECKOUT_ENABLED'))
				$this->errors[] = Tools::displayError('You cannot create a guest account.');
			if (!Tools::getValue('is_new_customer', 1))
				$_POST['passwd'] = md5(time()._COOKIE_KEY_);
			
			$customer = new Customer();			
			$_POST['lastname'] = $_POST['customer_lastname'];
			$_POST['firstname'] = $_POST['customer_firstname'];
			$passConfirm	  	= $_POST['passwd-confirm'];	
			$districtId			= Tools::getValue('district');			
			$this->errors = array_unique(array_merge($this->errors, $customer->validateControler()));
			
			$address = new Address();			
			$address->id_customer = 1;	
			$this->errors = array_unique(array_merge($this->errors, $address->validateControler()));
			
			//check confirm password
			if (empty($passConfirm) )
				$this->errors[] = Tools::displayError('<strong>confirm password</strong> is required');
			elseif ($passConfirm != Tools::getValue('passwd'))
				$this->errors[] = Tools::displayError('<strong>password</strong> is not match.');
			
			// check date
			if (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND !(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
				$this->errors[] = Tools::displayError('Invalid date of birth');
			
				
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
			
			
			//no errors found	
		
			if (!sizeof($this->errors))
			{			
				if (Tools::isSubmit('newsletter'))
				{
					$customer->ip_registration_newsletter = pSQL(Tools::getRemoteAddr());
					$customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
				}
				
				$customer->birthday = (empty($_POST['years']) ? '' : (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']));
				
				$customer->active = 1;				
				$customer->is_guest = 0;
				if (!$customer->add()){				
					$this->errors[] = Tools::displayError('An error occurred while creating your account.');
				}
				else{				
					$address->id_customer = (int)($customer->id);
					if (!$address->add())
						$this->errors[] = Tools::displayError('An error occurred while creating your address.');
					else{						
						if (!$customer->is_guest){
							if (!Mail::Send((int)(self::$cookie->id_lang), 'account', Mail::l('Welcome!'),
							array('{firstname}' => $customer->firstname, '{lastname}' => $customer->lastname, '{email}' => $customer->email, '{passwd}' => Tools::getValue('passwd')), $customer->email, $customer->firstname.' '.$customer->lastname))
							$this->errors[] = Tools::displayError('Cannot send email');
						}						
						self::$smarty->assign('confirmation', 1);
						self::$cookie->id_customer = (int)($customer->id);
						self::$cookie->customer_lastname = $customer->lastname;
						self::$cookie->customer_firstname = $customer->firstname;
						self::$cookie->passwd = $customer->passwd;
						self::$cookie->logged = 1;
						self::$cookie->email = $customer->email;
						self::$cookie->is_guest = !Tools::getValue('is_new_customer', 1);
						
						self::$cart->secure_key = $customer->secure_key;
						self::$cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
						self::$cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
						self::$cart->update();	
							
						/* successfully created a user, close popup and
						/* redirect original page to user's account
							*/							
						function closePopup(){
							?>
								<script type="text/javascript"> 
									window.opener.location.reload(); 
									// then close this pop-up window
									window.close();
								</script>  
							<?php
						}	
						closePopup();
						exit;			
					}
				}							
			}
			
		}			
	}
	
	public function process()
	{
	
	}
	
	public function displayContent()
	{
		parent::displayContent();
		self::$smarty->display(_PS_THEME_DIR_.'providers.tpl');
	}
}

