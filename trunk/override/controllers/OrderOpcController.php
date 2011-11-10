<?php
/*
* Override OrderOpcController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* startdate: 20/06/2011
*/

ControllerFactory::includeController('ParentOrderController');

class OrderOpcController extends OrderOpcControllerCore
{
	
	public function setMedia()
	{
		parent::setMedia();
		$intStep = (int)(Tools::getValue('step'));
		//if($intStep == 1){
			//Tools::addCSS(_THEME_CSS_DIR_.'check-out.css');	
		//}
		//else {
			Tools::addCSS(_THEME_CSS_DIR_.'shopping-cart.css');		
			Tools::addCSS(_THEME_CSS_DIR_.'register.css');	
			Tools::addCSS(_THEME_CSS_DIR_.'address.css');	
				
		//}
	}	
	protected function _processAddressFormat()
	{
		
		
		
		$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
		
		$address_delivery = new Address((int)self::$cart->id_address_delivery);
		
		$address_invoice = new Address((int)self::$cart->id_address_invoice);
		$inv_adr_fields = AddressFormat::getOrderedAddressFields((int)$address_delivery->id_country, false, true);
		$dlv_adr_fields = AddressFormat::getOrderedAddressFields((int)$address_invoice->id_country, false, true);


		$inv_all_fields = array();
		$dlv_all_fields = array();

		foreach (array('inv','dlv') as $adr_type)
		{
			
			foreach (${$adr_type.'_adr_fields'} as $fields_line) {
				foreach(explode(' ',$fields_line) as $field_item) {					
					${$adr_type.'_all_fields'}[] = trim($field_item);
				}
			}
			self::$smarty->assign($adr_type.'_adr_fields', ${$adr_type.'_adr_fields'});
			self::$smarty->assign($adr_type.'_all_fields', ${$adr_type.'_all_fields'});

		}
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
		
		self::$smarty->assign('provinces', $provinces);
		self::$smarty->assign('districts', $districts);
	}	
	
	protected function _getPaymentMethods()
		{
			if (!$this->isLogged)
				return '<p class="warning">'.Tools::displayError('Please sign in to see payment methods').'</p>';
			if (self::$cart->OrderExists())
				return '<p class="warning">'.Tools::displayError('Error: this order is already validated').'</p>';
			if (!self::$cart->id_customer OR !Customer::customerIdExistsStatic(self::$cart->id_customer) OR Customer::isBanned(self::$cart->id_customer))
				return '<p class="warning">'.Tools::displayError('Error: no customer').'</p>';
			$address_delivery = new Address(self::$cart->id_address_delivery);
			$address_invoice = (self::$cart->id_address_delivery == self::$cart->id_address_invoice ? $address_delivery : new Address(self::$cart->id_address_invoice));
			if (!self::$cart->id_address_delivery OR !self::$cart->id_address_invoice OR !Validate::isLoadedObject($address_delivery) OR !Validate::isLoadedObject($address_invoice) OR $address_invoice->deleted OR $address_delivery->deleted)
				return '<p class="warning">'.Tools::displayError('Error: please choose an address').'</p>';
			/*if (!self::$cart->id_carrier AND !self::$cart->isVirtualCart())
				return '<p class="warning">'.Tools::displayError('Error: please choose a carrier').'</p>';
			elseif (self::$cart->id_carrier != 0)
			{
				$carrier = new Carrier((int)(self::$cart->id_carrier));
				if (!Validate::isLoadedObject($carrier) OR $carrier->deleted OR !$carrier->active)
					return '<p class="warning">'.Tools::displayError('Error: the carrier is invalid').'</p>';
			}
			if (!self::$cart->id_currency)
				return '<p class="warning">'.Tools::displayError('Error: no currency has been selected').'</p>';
			if (!self::$cookie->checkedTOS AND Configuration::get('PS_CONDITIONS'))
				return '<p class="warning">'.Tools::displayError('Please accept Terms of Service').'</p>';
			*/
			/* If some products have disappear */
			if (!self::$cart->checkQuantities())
				return '<p class="warning">'.Tools::displayError('An item in your cart is no longer available, you cannot proceed with your order.').'</p>';

		/* Check minimal amount */
		$currency 	= Currency::getCurrency((int)self::$cart->id_currency);
		$_SESSION['total'] = self::$cart->getOrderTotal(true, 3);
		$minimalPurchase = Tools::convertPrice((float)Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
		if (self::$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimalPurchase)
			return '<p class="warning">'.Tools::displayError('A minimum purchase total of').' '.Tools::displayPrice($minimalPurchase, $currency).
			' '.Tools::displayError('is required in order to validate your order.').'</p>';

			/* Bypass payment step if total is 0 */
			if (self::$cart->getOrderTotal() <= 0)
				return '<p class="center"><input type="button" class="exclusive_large" name="confirmOrder" id="confirmOrder" value="'.Tools::displayError('I confirm my order').'" onclick="confirmFreeOrder();" /></p>';

			$return = Module::hookExecPayment();
			if (!$return)
				return '<p class="warning">'.Tools::displayError('No payment method is available').'</p>';
			return $return;
			
		}	
	public function process()
		{
			parent::process();
			
		}
	public function displayContent()
	{
		
		FrontController::displayContent();				
		$this->_processAddressFormat();
		$this->_getGuestInformations();	
		if (Tools::getValue('ajax_list_address')) {
					$this->_processAddressFormat();
					$this->_getGuestInformations();	
					self::$smarty->assign('ajax_list',1);
					echo self::$smarty->fetch(_PS_THEME_DIR_.'order-address.tpl');
			die;
		}
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'errors.tpl'));
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'order-opc.tpl'));
		
	}
	
}
?>