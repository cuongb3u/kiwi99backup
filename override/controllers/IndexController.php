<?php
/*
* Override IndexController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/

class IndexController extends IndexControllerCore
{	
	public function process()
	{
		FrontController::process();		
	}
	
	public function setMedia()
	{
		FrontController::setMedia();
		$shop 		= Tools::getValue('shop');
		if($shop){
			Tools::addCSS(_THEME_CSS_DIR_."shop$shop/home.css", 'all');
		}
		else{
		Tools::addCSS(_THEME_CSS_DIR_.'home.css', 'all');
	}
	}
	
	public function displayContent()
	{
		FrontController::displayContent();		
		$shop 		= Tools::getValue('shop');				
		if($shop){
			self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_."shop$shop/home.tpl"));		
		}else{

		
			self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'home.tpl'));
		}
		
	}
	
}
