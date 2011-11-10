<?php 
/*
* Override CmsController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/
class CmsController extends CmsControllerCore{

	public function setMedia()
	{
		parent::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'cms.css');
		Tools::addCSS(_THEME_CSS_DIR_.'order-opc.css');	
		//Tools::addJS(_THEME_JS_DIR_.'order-opc.js');		
	}
	public function process()
	{
		parent::process();
		if (Tools::getValue('ajaxload')) {
				self::$smarty->assign('ajaxload','1');
				echo self::$smarty->fetch(_PS_THEME_DIR_.'cms.tpl');
				die;
			}	
	}
	public function displayContent()
	{
		FrontController::displayContent();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'cms.tpl'));
	}
}


?>