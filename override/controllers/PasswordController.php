<?php
class PasswordController extends PasswordControllerCore{
	public function setMedia()
	{
		FrontController::setMedia();		
		Tools::addCSS(_THEME_CSS_DIR_.'register.css');
	}
	public function displayContent()
	{
		FrontController::displayContent();		
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'password.tpl'));
	}
}