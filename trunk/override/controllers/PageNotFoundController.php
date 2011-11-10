<?php

class PageNotFoundController extends PageNotFoundControllerCore
{
	
	public function displayContent()
	{

		FrontController::displayContent();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'404.tpl'));
	
	}
}

