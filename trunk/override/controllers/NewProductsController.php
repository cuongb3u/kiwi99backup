<?php

class NewProductsController extends NewProductsControllerCore{

	public function displayContent()
	{
		FrontController::displayContent();
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'new-products.tpl'));
	}


}