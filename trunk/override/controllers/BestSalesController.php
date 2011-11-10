<?php

class BestSalesController extends BestSalesControllerCore{

	public function displayContent()
	{
		FrontController::displayContent();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'best-sales.tpl'));
	}


}