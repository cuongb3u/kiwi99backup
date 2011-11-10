<?php
/*
* Override ProductController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/

class ProductController extends ProductControllerCore
{

		public function process() 
	{
		parent::process();  
	
		if (Tools::getValue('ajaxload')) {
			
				$ipaColor = (int)Tools::getValue('ipaColor');
				self::$smarty->assign('ipaColor', $ipaColor);
				self::$smarty->assign('ajaxload','1');
				$shop=Tools::getValue('shop');
				$static_token=Tools::getValue('static_token');
				self::$smarty->assign('static_token',$static_token);
				if (Tools::getValue('quick_view')) {
					if (Tools::getValue('first')) {
						echo '<script type="text/javascript"   src="'._PS_JS_DIR_.'jquery/jquery.jqzoom.js"></script>';
						echo '<script type="text/javascript"   src="'._THEME_JS_DIR_.'shop'.$shop.'/jquery.motionGallery.js"></script>';
						echo '<script type="text/javascript"   src="'._PS_JS_DIR_.'jquery/jquery.scrollTo-1.4.2-min.js"></script>';
						echo '<script type="text/javascript"   src="'._PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js"></script>';
						echo '<script type="text/javascript"   src="'._PS_JS_DIR_.'jquery/jquery.serialScroll-1.2.2-min.js"></script>';
						echo '<script type="text/javascript"   src="'._THEME_JS_DIR_.'shop'.$shop.'/tools.js"></script>';
						echo '<script type="text/javascript"   src="'._THEME_JS_DIR_.'shop'.$shop.'/product.js"></script>';
						echo '<script type="text/javascript"   src="'._THEME_JS_DIR_.'shop'.$shop.'/ui.core.min.js"></script>';
						// echo '<link href="'._THEME_CSS_DIR_.'shop'.$shop.'/product-relation.css" rel="stylesheet" type="text/css" media="all">';
					}
						echo self::$smarty->fetch(_PS_THEME_DIR_.'shop'.$shop.'/product-quickview.tpl');
				die;
				}
				echo self::$smarty->fetch(_PS_THEME_DIR_.'shop'.$shop.'/product.tpl');
				die;
			}	
	}
	public function setMedia()
	{
		FrontController::setMedia();

/*
		Tools::addCSS(_THEME_CSS_DIR_.'product.css');
		Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css', 'screen');
		Tools::addJS(array(
			_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js',
			_PS_JS_DIR_.'jquery/jquery.idTabs.modified.js',
			_PS_JS_DIR_.'jquery/jquery.scrollTo-1.4.2-min.js',
			_PS_JS_DIR_.'jquery/jquery.serialScroll-1.2.2-min.js',
			_THEME_JS_DIR_.'tools.js',
			_THEME_JS_DIR_.'product.js'));

		if (Configuration::get('PS_DISPLAY_JQZOOM') == 1)
		{
			Tools::addCSS(_PS_CSS_DIR_.'jqzoom.css', 'screen');
			Tools::addJS(_PS_JS_DIR_.'jquery/jquery.jqzoom.js');
		}
*/

//		Tools::addCSS(_THEME_CSS_DIR_.'global.css');
		$shop=Tools::getValue('shop');
		if ($shop) {
				Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop.'/product.css');
			Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css', 'screen');
			Tools::addJS(array(
				_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js',
				_PS_JS_DIR_.'jquery/jquery.idTabs.modified.js',
				_PS_JS_DIR_.'jquery/jquery.scrollTo-1.4.2-min.js',
				_PS_JS_DIR_.'jquery/jquery.serialScroll-1.2.2-min.js',
				_THEME_JS_DIR_.'shop'.$shop.'/tools.js',
				_THEME_JS_DIR_.'shop'.$shop.'/product.js'));
	
			if (Configuration::get('PS_DISPLAY_JQZOOM') == 1)
			{
				Tools::addCSS(_PS_CSS_DIR_.'jqzoom.css', 'screen');
				Tools::addJS(_PS_JS_DIR_.'jquery/jquery.jqzoom.js');
			}
			Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop.'/product-detail.css');
			Tools::addJS(array(
				_THEME_JS_DIR_.'shop'.$shop.'/ui.core.min.js',
				_THEME_JS_DIR_.'shop'.$shop.'/ui.slider.min.js',
				_THEME_JS_DIR_.'shop'.$shop.'/product2.js',
				_THEME_JS_DIR_.'shop'.$shop.'/jquery.motionGallery.js',
				_THEME_JS_DIR_.'shop'.$shop.'/product.js'
	//			_THEME_JS_DIR_.'jquery.gzoom.js'
				));
		}
		else{
		Tools::addCSS(_THEME_CSS_DIR_.'product.css');
		Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css', 'screen');
		Tools::addJS(array(
			_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js',
			_PS_JS_DIR_.'jquery/jquery.idTabs.modified.js',
			_PS_JS_DIR_.'jquery/jquery.scrollTo-1.4.2-min.js',
			_PS_JS_DIR_.'jquery/jquery.serialScroll-1.2.2-min.js',
			_THEME_JS_DIR_.'tools.js',
			_THEME_JS_DIR_.'product.js'));

		if (Configuration::get('PS_DISPLAY_JQZOOM') == 1)
		{
			Tools::addCSS(_PS_CSS_DIR_.'jqzoom.css', 'screen');
			Tools::addJS(_PS_JS_DIR_.'jquery/jquery.jqzoom.js');
		}
		Tools::addCSS(_THEME_CSS_DIR_.'product-detail.css');
		Tools::addJS(array(
			_THEME_JS_DIR_.'ui.core.min.js',
			_THEME_JS_DIR_.'ui.slider.min.js',
			_THEME_JS_DIR_.'product2.js',
			_THEME_JS_DIR_.'jquery.motionGallery.js',
			_THEME_JS_DIR_.'product.js'
//			_THEME_JS_DIR_.'jquery.gzoom.js'
			));
		}
	}

	public function displayContent()
	{
	
		FrontController::displayContent();		
	
		$ipaColor = (int)Tools::getValue('ipaColor');
		self::$smarty->assign('ipaColor', $ipaColor);	
			$shop=Tools::getValue('shop');	
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'shop'.$shop.'/product.tpl'));
	
		//self::$smarty->display(_PS_THEME_DIR_.'product.tpl');
	}

	
}

?>