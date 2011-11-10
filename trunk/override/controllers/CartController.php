<?php
/*
* Override CartController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/
class CartController extends CartControllerCore
{
	public function run()
	{
		$this->init();
		$this->preProcess();

		if (Tools::getValue('ajax') == 'true')
		{
			if (Tools::getIsset('summary'))
			{
				if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1)
				{
					if (self::$cookie->id_customer)
					{
						$customer = new Customer((int)(self::$cookie->id_customer));
						$groups = $customer->getGroups();
					}
					else
						$groups = array(1);
					if ((int)self::$cart->id_address_delivery)
						$deliveryAddress = new Address((int)self::$cart->id_address_delivery);
					$result = array('carriers' => Carrier::getCarriersForOrder((int)Country::getIdZone((isset($deliveryAddress) AND (int)$deliveryAddress->id) ? (int)$deliveryAddress->id_country : (int)Configuration::get('PS_COUNTRY_DEFAULT')), $groups));
				}
				$result['summary'] = self::$cart->getSummaryDetails();
				$result['customizedDatas'] = Product::getAllCustomizedDatas((int)(self::$cart->id));
				$result['HOOK_SHOPPING_CART'] = Module::hookExec('shoppingCart', $result['summary']);
				$result['HOOK_SHOPPING_CART_EXTRA'] = Module::hookExec('shoppingCartExtra', $result['summary']);
				die(Tools::jsonEncode($result));
			}
			else
				$this->includeCartModule();
		}
		else
		{
			$this->setMedia();
			$this->displayHeader();
			$this->process();
			$this->displayContent();
			$this->displayFooter();
			$this->displayPagelayout();
		}
	}

	

	

	public function displayContent()
	{
		FrontController::displayContent();
		$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
										SELECT `provinceid`, `name`
										FROM `'._DB_PREFIX_.'provinces` 							
										ORDER BY `iseq` ASC');
		self::$smarty->assign('provinces', $provinces);
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'errors.tpl'));
		//self::$smarty->display(_PS_THEME_DIR_.'errors.tpl');
	}
}
