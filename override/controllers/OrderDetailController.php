<?php
class OrderDetailController extends OrderDetailControllerCore{	
	
	public function displayContent()
	{
		FrontController::displayContent();	
		self::$smarty->display(_PS_THEME_DIR_.'order-detail.tpl');
		exit();
	}
}