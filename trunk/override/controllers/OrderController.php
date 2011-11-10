<?php
/*
* Override OrderOpcController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* startdate: 20/06/2011 by truongkimphung1982@yahoo.com
*/

class OrderController extends OrderControllerCore
{
	public function setMedia()
	{
		parent::setMedia();

		Tools::addCSS(_THEME_CSS_DIR_.'shopping-cart.css');
		
	}

	public function displayContent()
	{		
		FrontController::displayContent();
		$this->processAddress();
		
	}
	
}
?>