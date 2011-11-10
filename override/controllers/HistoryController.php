<?php
/*
* Override CartController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/
class HistoryController extends HistoryControllerCore
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
				echo '<script type="text/javascript"   src="'._THEME_JS_DIR_.'history.js"></script>';
				echo '<script type="text/javascript"   src="'._PS_JS_DIR_.'jquery/jquery.scrollTo-1.4.2-min.js"></script>';
				echo self::$smarty->fetch(_PS_THEME_DIR_.'history.tpl');
				die;
			}	
	}
	public function displayContent()
	{
		FrontController::displayContent();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'history.tpl'));
	}
}
