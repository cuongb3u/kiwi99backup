<?php
/*
* Override OrderOpcController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* startdate: 20/06/2011
*/

ControllerFactory::includeController('ParentOrderController');

class DiscountController extends DiscountControllerCore
{
	public function setMedia()
	{
		parent::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'address.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'identity.css');	
		Tools::addCSS(_THEME_CSS_DIR_.'addresses.css');
		Tools::addCSS(_THEME_CSS_DIR_.'history.css');
		Tools::addCSS(_THEME_CSS_DIR_.'discount.css');
		Tools::addCSS(_THEME_CSS_DIR_.'my-account.css');
	}	
	public function process() 
	{
		parent::process();  

		if (Tools::getValue('ajaxload')) {
				self::$smarty->assign('ajaxload','1');
				echo self::$smarty->fetch(_PS_THEME_DIR_.'discount.tpl');
				die;
			}	
	}
	public function displayContent()
	{
		FrontController::displayContent();	
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'discount.tpl'));
		
	}
	
}
?>