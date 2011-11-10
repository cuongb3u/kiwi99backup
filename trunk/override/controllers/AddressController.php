<?php

class AddressController extends AddressControllerCore{

	public function setMedia()
	{
		parent::setMedia();
		Tools::addJS(_THEME_JS_DIR_.'tools/statesManagement.js');
		Tools::addCSS(_THEME_CSS_DIR_.'address.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'identity.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'addresses.css');
		Tools::addCSS(_THEME_CSS_DIR_.'history.css');
		Tools::addCSS(_THEME_CSS_DIR_.'discount.css');
		Tools::addCSS(_THEME_CSS_DIR_.'my-account.css');
	}
		public function preProcess()
	{
			if (Tools::isSubmit('ajaxadd'))
		{
			$return	=	array();
			$return['success']='0';
			$address = new Address();
			$this->errors = $address->validateControler();
			$address->id_customer = (int)(self::$cookie->id_customer);

			if (!Tools::getValue('phone') AND !Tools::getValue('phone_mobile'))
				$this->errors[] = Tools::displayError('You must register at least one phone number');
			if (!$country = new Country((int)$address->id_country) OR !Validate::isLoadedObject($country))
				die(Tools::displayError());

			/* US customer: normalize the address */
			if ($address->id_country == Country::getByIso('US'))
			{
				include_once(_PS_TAASC_PATH_.'AddressStandardizationSolution.php');
				$normalize = new AddressStandardizationSolution;
				$address->address1 = $normalize->AddressLineStandardization($address->address1);
				$address->address2 = $normalize->AddressLineStandardization($address->address2);
			}

			$zip_code_format = $country->zip_code_format;
			if ($country->need_zip_code)
			{
				if (($postcode = Tools::getValue('postcode')) AND $zip_code_format)
				{
					$zip_regexp = '/^'.$zip_code_format.'$/ui';
					$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
					$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
					$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
					$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
					$zip_regexp = str_replace('C', $country->iso_code, $zip_regexp);
					if (!preg_match($zip_regexp, $postcode))
						$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.').'<br />'.Tools::displayError('Must be typed as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
				}
				elseif ($zip_code_format)
					$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is required.');
				elseif ($postcode AND !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode))
						$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.').'<br />'.Tools::displayError('Must be typed as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
			}
			if ($country->isNeedDni() AND (!Tools::getValue('dni') OR !Validate::isDniLite(Tools::getValue('dni'))))
				$this->errors[] = Tools::displayError('Identification number is incorrect or has already been used.');
			elseif (!$country->isNeedDni())
				$address->dni = NULL;
			/*if (Configuration::get('PS_TOKEN_ENABLE') == 1 AND
				strcmp(Tools::getToken(false), Tools::getValue('token')) AND
				self::$cookie->isLogged(true) === true)
				$this->errors[] = Tools::displayError('Invalid token');*/

			if ((int)($country->contains_states) AND !(int)($address->id_state))
				$this->errors[] = Tools::displayError('This country requires a state selection.');
			
			if (!sizeof($this->errors))
			{
				if (isset($id_address))
				{
					$country = new Country((int)($address->id_country));
					if (Validate::isLoadedObject($country) AND !$country->contains_states)
						$address->id_state = 0;
					$address_old = new Address((int)$id_address);
					if (Validate::isLoadedObject($address_old) AND Customer::customerHasAddress((int)self::$cookie->id_customer, (int)$address_old->id))
					{
						if ($address_old->isUsed())
						{
							$address_old->delete();
							if (!Tools::isSubmit('ajax'))
							{
								$to_update = false;
								if (self::$cart->id_address_invoice == $address_old->id)
								{
									$to_update = true;
									self::$cart->id_address_invoice = 0;
								}
								if (self::$cart->id_address_delivery == $address_old->id)
								{
									$to_update = true;
									self::$cart->id_address_delivery = 0;
								}
								if ($to_update)
									self::$cart->update();
							}
						}
						else
						{
							$address->id = (int)($address_old->id);
							$address->date_add = $address_old->date_add;
						}
					}
				}
				elseif (self::$cookie->is_guest)
				
					Tools::redirect('addresses.php');
					
				if ($result = $address->save())
				{
					/* In order to select this new address : order-address.tpl */
					if ((bool)(Tools::getValue('select_address', false)) == true OR (Tools::isSubmit('ajax') AND Tools::getValue('type') == 'invoice'))
					{
						/* This new adress is for invoice_adress, select it */
						self::$cart->id_address_invoice = (int)($address->id);
						self::$cart->update();
					}
					if (Tools::isSubmit('ajax'))
					{
						$return = array(
							'hasError' => !empty($this->errors), 
							'errors' => $this->errors,
							'id_address_delivery' => self::$cart->id_address_delivery,
							'id_address_invoice' => self::$cart->id_address_invoice
						);
						die(Tools::jsonEncode($return));
					}
					echo 1;
					die;
					Tools::redirect($back ? ($mod ? $back.'&back='.$mod : $back) : 'addresses.php');
				}
				$this->errors[] = Tools::displayError('An error occurred while updating your address.');
				
			}
			$return['error']=$this->errors;
			echo json_encode($return);
			die;
		}
		if ($back = Tools::getValue('back'))
			self::$smarty->assign('back', Tools::safeOutput($back));
		if ($mod = Tools::getValue('mod'))
			self::$smarty->assign('mod', Tools::safeOutput($mod));
		
		if (Tools::isSubmit('ajax') AND Tools::isSubmit('type'))
		{
			if (Tools::getValue('type') == 'delivery')
				$id_address = isset(self::$cart->id_address_delivery) ? (int)self::$cart->id_address_delivery : 0;
			elseif (Tools::getValue('type') == 'invoice')
				$id_address = (isset(self::$cart->id_address_invoice) AND self::$cart->id_address_invoice != self::$cart->id_address_delivery) ? (int)self::$cart->id_address_invoice : 0;
			else
				exit;
		}
		else
			$id_address = (int)Tools::getValue('id_address', 0);
		
		if ($id_address)
		{
			$this->_address = new Address((int)$id_address);
			if (Validate::isLoadedObject($this->_address) AND Customer::customerHasAddress((int)(self::$cookie->id_customer), (int)($id_address)))
			{
				if (Tools::isSubmit('delete'))
				{
					if (self::$cart->id_address_invoice == $this->_address->id)
						unset(self::$cart->id_address_invoice);
					if (self::$cart->id_address_delivery == $this->_address->id)
						unset(self::$cart->id_address_delivery);
					if ($this->_address->delete())
					{
						echo 1;
						die;
						Tools::redirect('addresses.php');
					}
					$this->errors[] = Tools::displayError('This address cannot be deleted.');
				}
				self::$smarty->assign(array('address' => $this->_address, 'id_address' => (int)$id_address));
			}
			elseif (Tools::isSubmit('ajax'))
				exit;
			else
				Tools::redirect('addresses.php');
		}
		if (Tools::isSubmit('submitAddress'))
		{
			$address = new Address();
			$this->errors = $address->validateControler();
			$address->id = $id_address;
			$address->id_customer = (int)(self::$cookie->id_customer);
			if (!Tools::getValue('phone') AND !Tools::getValue('phone_mobile'))
				$this->errors[] = Tools::displayError('You must register at least one phone number');
			if (!$country = new Country((int)$address->id_country) OR !Validate::isLoadedObject($country))
				die(Tools::displayError());

			/* US customer: normalize the address */
			if ($address->id_country == Country::getByIso('US'))
			{
				include_once(_PS_TAASC_PATH_.'AddressStandardizationSolution.php');
				$normalize = new AddressStandardizationSolution;
				$address->address1 = $normalize->AddressLineStandardization($address->address1);
				$address->address2 = $normalize->AddressLineStandardization($address->address2);
			}

			$zip_code_format = $country->zip_code_format;
			if ($country->need_zip_code)
			{
				if (($postcode = Tools::getValue('postcode')) AND $zip_code_format)
				{
					$zip_regexp = '/^'.$zip_code_format.'$/ui';
					$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
					$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
					$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
					$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
					$zip_regexp = str_replace('C', $country->iso_code, $zip_regexp);
					if (!preg_match($zip_regexp, $postcode))
						$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.').'<br />'.Tools::displayError('Must be typed as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
				}
				elseif ($zip_code_format)
					$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is required.');
				elseif ($postcode AND !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode))
						$this->errors[] = '<strong>'.Tools::displayError('Zip/ Postal code').'</strong> '.Tools::displayError('is invalid.').'<br />'.Tools::displayError('Must be typed as follows:').' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
			}
			if ($country->isNeedDni() AND (!Tools::getValue('dni') OR !Validate::isDniLite(Tools::getValue('dni'))))
				$this->errors[] = Tools::displayError('Identification number is incorrect or has already been used.');
			elseif (!$country->isNeedDni())
				$address->dni = NULL;
			/*if (Configuration::get('PS_TOKEN_ENABLE') == 1 AND
				strcmp(Tools::getToken(false), Tools::getValue('token')) AND
				self::$cookie->isLogged(true) === true)
				$this->errors[] = Tools::displayError('Invalid token');*/

			if ((int)($country->contains_states) AND !(int)($address->id_state))
				$this->errors[] = Tools::displayError('This country requires a state selection.');			
			if (!sizeof($this->errors))
			{				
				if (isset($id_address))
				{
					$country = new Country((int)($address->id_country));
					if (Validate::isLoadedObject($country) AND !$country->contains_states)
						$address->id_state = 0;
					$address_old = new Address((int)$id_address);
					if (Validate::isLoadedObject($address_old) AND Customer::customerHasAddress((int)self::$cookie->id_customer, (int)$address_old->id))
					{
						if ($address_old->isUsed())
						{
							$address_old->delete();
							if (!Tools::isSubmit('ajax'))
							{
								$to_update = false;
								if (self::$cart->id_address_invoice == $address_old->id)
								{
									$to_update = true;
									self::$cart->id_address_invoice = 0;
								}
								if (self::$cart->id_address_delivery == $address_old->id)
								{
									$to_update = true;
									self::$cart->id_address_delivery = 0;
								}
								if ($to_update)
									self::$cart->update();
							}
						}
						else
						{
							$address->id = (int)($address_old->id);
							$address->date_add = $address_old->date_add;
						}
					}
				}
				elseif (self::$cookie->is_guest)
					Tools::redirect('addresses.php');				
				
				if ($result = $address->save())
				{
					/* In order to select this new address : order-address.tpl */
					if ((bool)(Tools::getValue('select_address', false)) == true OR (Tools::isSubmit('ajax') AND Tools::getValue('type') == 'invoice'))
					{
						/* This new adress is for invoice_adress, select it */
						self::$cart->id_address_invoice = (int)($address->id);
						self::$cart->update();
					}
					if (Tools::isSubmit('ajax'))
					{
						$return = array(
							'hasError' => !empty($this->errors), 
							'errors' => $this->errors,
							'id_address_delivery' => self::$cart->id_address_delivery,
							'id_address_invoice' => self::$cart->id_address_invoice
						);
						die(Tools::jsonEncode($return));
					}
					if (Tools::getValue('ajaxupdate')) {						
						echo 1;
						die;
					}
					Tools::redirect($back ? ($mod ? $back.'&back='.$mod : $back) : 'addresses.php');
				}
			
				$this->errors[] = Tools::displayError('An error occurred while updating your address.');
			}
				if (Tools::getValue('ajaxupdate')) {
					echo 0;
					die;
				}
		}
		elseif (!$id_address)
		{
			$customer = new Customer((int)(self::$cookie->id_customer));
			if (Validate::isLoadedObject($customer))
			{
				$_POST['firstname'] = $customer->firstname;
				$_POST['lastname'] = $customer->lastname;
			}
		}
		if (Tools::isSubmit('ajax') AND sizeof($this->errors))
		{
			$return = array(
				'hasError' => !empty($this->errors), 
				'errors' => $this->errors
			);
			die(Tools::jsonEncode($return));
		}
	}

	public function process() 
	{
		parent::process();  

		if (Tools::getValue('ajaxload')) {
				
				$this->_processAddressFormat();
				$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
									SELECT `provinceid`, `name`
									FROM `'._DB_PREFIX_.'provinces` 							
									ORDER BY `iseq` ASC');
				$intProvinceId	= isset($provinces[0]['provinceid']) ? $provinces[0]['provinceid'] : 0;		
				$districts		= Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
																			SELECT `districtid`, `name`
																			FROM `'._DB_PREFIX_.'districts` 							
																			WHERE `province`=' . $intProvinceId . '
																			ORDER BY `iseq` ASC');	
				self::$smarty->assign('districts', $districts);
				self::$smarty->assign('provinces', $provinces);
				self::$smarty->assign('ajaxload','1');
				if (Tools::getValue('ajaxladd')) {
					echo self::$smarty->fetch(_PS_THEME_DIR_.'address_box.tpl');
					die;
				}
				echo self::$smarty->fetch(_PS_THEME_DIR_.'address.tpl');
				die;
			}	
	}
	public function displayContent()
	{
		FrontController::displayContent();
		$this->_processAddressFormat();
		$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
							SELECT `provinceid`, `name`
							FROM `'._DB_PREFIX_.'provinces` 							
							ORDER BY `iseq` ASC');
		$intProvinceId	= isset($provinces[0]['provinceid']) ? $provinces[0]['provinceid'] : 0;		
		$districts		= Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
																	SELECT `districtid`, `name`
																	FROM `'._DB_PREFIX_.'districts` 							
																	WHERE `province`=' . $intProvinceId . '
																	ORDER BY `iseq` ASC');	
		self::$smarty->assign('districts', $districts);
		self::$smarty->assign('provinces', $provinces);
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'address.tpl'));
	}
}