<?php
class IdentityController extends IdentityControllerCore{
	
	public function displayContent()
	{
		FrontController::displayContent();		
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'identity.tpl'));
	}
		public function preProcess()
	{
		
		$customer = new Customer((int)(self::$cookie->id_customer));

		if (sizeof($_POST))
		{
			$exclusion = array('secure_key', 'old_passwd', 'passwd', 'active', 'date_add', 'date_upd', 'last_passwd_gen', 'newsletter_date_add', 'id_default_group');
			$fields = $customer->getFields();
			foreach ($fields AS $key => $value)
				if (!in_array($key, $exclusion))
					$customer->{$key} = key_exists($key, $_POST) ? trim($_POST[$key]) : 0;
		}

		if (isset($_POST['years']) AND isset($_POST['months']) AND isset($_POST['days']))
			$customer->birthday = (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']);
			
			if (Tools::isSubmit('ajaxupdate'))
		{
				if (Tools::getValue('checkPaswd'))
					 {
					 	if (empty($_POST['old_passwd']) OR (Tools::encrypt($_POST['old_passwd']) != self::$cookie->passwd))
					 	 {
							$succes	=	0;
							echo $succes;
							die;
						}
						else{
							$succes	=	1;
							echo $succes;
							die;
						}
					}
					
			if (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND
			!(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
				$this->errors[] = Tools::displayError('Invalid date of birth');
			else
			{
				$customer->birthday = (empty($_POST['years']) ? '' : (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']));

				$_POST['old_passwd'] = trim($_POST['old_passwd']);
				if (empty($_POST['old_passwd']) OR (Tools::encrypt($_POST['old_passwd']) != self::$cookie->passwd))
				{	
					$this->errors[] = Tools::displayError('Your password is incorrect.');
				}
				elseif ($_POST['passwd'] != $_POST['confirmation'])
					$this->errors[] = Tools::displayError('Password and confirmation do not match');
				else
				{
					$prev_id_default_group = $customer->id_default_group;
					$this->errors = $customer->validateControler();
				}
				
				if (!sizeof($this->errors))
				{
					
					$customer->id_default_group = (int)($prev_id_default_group);
					$customer->firstname = Tools::ucfirst(Tools::strtolower($customer->firstname));
					if (Tools::getValue('passwd'))
						self::$cookie->passwd = $customer->passwd;
					if ($customer->update())
					{
						self::$cookie->customer_lastname = $customer->lastname;
						self::$cookie->customer_firstname = $customer->firstname;
						self::$smarty->assign('confirmation', 1);
						$success	=	1;
						echo $success;
						die;
					}
					else
						{
							$this->errors[] = Tools::displayError('Cannot update information');
						$success	=	0;
						echo $success;
						die;
					}
				}
			}
		}
		if (Tools::isSubmit('submitIdentity'))
		{
			if (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND
			!(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
				$this->errors[] = Tools::displayError('Invalid date of birth');
			else
			{
				$customer->birthday = (empty($_POST['years']) ? '' : (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']));

				$_POST['old_passwd'] = trim($_POST['old_passwd']);
				if (empty($_POST['old_passwd']) OR (Tools::encrypt($_POST['old_passwd']) != self::$cookie->passwd))
					$this->errors[] = Tools::displayError('Your password is incorrect.');
				elseif ($_POST['passwd'] != $_POST['confirmation'])
					$this->errors[] = Tools::displayError('Password and confirmation do not match');
				else
				{
					$prev_id_default_group = $customer->id_default_group;
					$this->errors = $customer->validateControler();
				}
				if (!sizeof($this->errors))
				{
					$customer->id_default_group = (int)($prev_id_default_group);
					$customer->firstname = Tools::ucfirst(Tools::strtolower($customer->firstname));
					if (Tools::getValue('passwd'))
						self::$cookie->passwd = $customer->passwd;
					if ($customer->update())
					{
						self::$cookie->customer_lastname = $customer->lastname;
						self::$cookie->customer_firstname = $customer->firstname;
						self::$smarty->assign('confirmation', 1);
					}
					else
						$this->errors[] = Tools::displayError('Cannot update information');
				}
			}
		}
		else
			$_POST = array_map('stripslashes', $customer->getFields());

		if ($customer->birthday)
			$birthday = explode('-', $customer->birthday);
		else
			$birthday = array('-', '-', '-');

		/* Generate years, months and days */
		self::$smarty->assign(array(
			'years' => Tools::dateYears(),
			'sl_year' => $birthday[0],
			'months' => Tools::dateMonths(),
			'sl_month' => $birthday[1],
			'days' => Tools::dateDays(),
			'sl_day' => $birthday[2],
			'errors' => $this->errors
		));
		
		self::$smarty->assign('newsletter', (int)Module::getInstanceByName('blocknewsletter')->active);
	}
	
	public function process() 
	{
		parent::process();  

		if (Tools::getValue('ajaxload')) {
				self::$smarty->assign('ajaxload','1');
				echo self::$smarty->fetch(_PS_THEME_DIR_.'identity.tpl');
				die;
			}	
	}
	public function setMedia()
	{
		parent::setMedia();
			Tools::addCSS(_THEME_CSS_DIR_.'address.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'identity.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'addresses.css');
		Tools::addCSS(_THEME_CSS_DIR_.'history.css');
		Tools::addCSS(_THEME_CSS_DIR_.'discount.css');
		
	}
}