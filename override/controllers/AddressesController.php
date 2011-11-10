<?php
class AddressesController extends AddressesControllerCore
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
		Tools::addCSS(_THEME_CSS_DIR_.'addresses.css');
		Tools::addJS(_THEME_JS_DIR_.'tools.js');
	}
	public function process() 
	{
		parent::process();  

		if (Tools::getValue('ajaxload')) {
				self::$smarty->assign('ajaxload','1');
				echo self::$smarty->fetch(_PS_THEME_DIR_.'addresses.tpl');
				die;
			}	
	}
	public function displayContent()
	{
		FrontController::displayContent();				
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'addresses.tpl'));
	}
}

