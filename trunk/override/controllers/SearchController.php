<?php
class SearchController extends SearchControllerCore{
	public function displayContent()
	{
		FrontController::displayContent();
		//$this->processAddressFormat();		
		
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'search.tpl'));
	}
	
	
	public function preProcess()
	{
		parent::preProcess();
		
		$query = urldecode(Tools::getValue('q'));
		if ($this->ajaxSearch)
		{
			self::$link = new Link();
			$searchResults = Search::find((int)(Tools::getValue('id_lang')), $query, 1, 10, 'position', 'desc', true);
			foreach ($searchResults AS &$product)
				$product['product_link'] = self::$link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);
			die(Tools::jsonEncode($searchResults));
		}
		
		if ($this->instantSearch && !is_array($query))
		{
			$this->productSort();
			$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
			$this->p = abs((int)(Tools::getValue('p', 1)));
			$search = Search::find((int)(self::$cookie->id_lang), $query, $this->p, $this->n, $this->orderBy, $this->orderWay);
			Module::hookExec('search', array('expr' => $query, 'total' => $search['total']));
			$nbProducts = $search['total'];
			$this->pagination($nbProducts);
			self::$smarty->assign(array(
			'products' => $search['result'], // DEPRECATED (since to 1.4), not use this: conflict with block_cart module
			'search_products' => $search['result'],
			'nbProducts' => $search['total'],
			'search_query' => $query,
			'instantSearch' => $this->instantSearch,
			'homeSize' => Image::getSize('home')));
		}
		elseif ($query = Tools::getValue('search_query', Tools::getValue('ref')) AND !is_array($query))
		{
			
			$searchfilter = Tools::getValue('searchfilter');
			
			$filteredData = array();
						
			$this->productSort();
			$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
			$this->p = abs((int)(Tools::getValue('p', 1)));
			
			if($searchfilter)
				$search = Search::findfilter((int)(self::$cookie->id_lang), $query, $this->p, $this->n, $this->orderBy, $this->orderWay);
			else
				$search = Search::find((int)(self::$cookie->id_lang), $query, $this->p, $this->n, $this->orderBy, $this->orderWay);
				
			Module::hookExec('search', array('expr' => $query, 'total' => $search['total']));
			$nbProducts = $search['total'];
			$this->pagination($nbProducts);
			
						
			self::$smarty->assign(array(
				'allBrandByThisCategory' => null,
				'allAttributesByThisCategory' => null,
				'allColorsByThisCategory' => null,
				'min_slider' => null,
				'max_slider' => null,
				'orderprices' => null,
				'id_category' => 2,
				
			'products' => $search['result'], // DEPRECATED (since to 1.4), not use this: conflict with block_cart module
			'search_products' => $search['result'],
			'nbProducts' => $search['total'],
			'search_query' => $query,
			'homeSize' => Image::getSize('home')));
			
			if($searchfilter){
									$productList = self::$smarty->fetch(_PS_THEME_DIR_.'product-list.tpl');
				//					$pagination  = self::$smarty->fetch(_PS_THEME_DIR_.'pagination.tpl');
//									$classFilter = self::$smarty->fetch(_PS_THEME_DIR_.'leftmenu-filter.tpl');
									$filteredData['productList'] = $productList;
				//					$filteredData['pagination'] = $pagination;
//									$filteredData['classFilter'] = $classFilter;
									echo json_encode($filteredData);
									die;

			}
			
			
		}
		elseif ($tag = urldecode(Tools::getValue('tag')) AND !is_array($tag))
		{
			$nbProducts = (int)(Search::searchTag((int)(self::$cookie->id_lang), $tag, true));
			$this->pagination($nbProducts);
			$result = Search::searchTag((int)(self::$cookie->id_lang), $tag, false, $this->p, $this->n, $this->orderBy, $this->orderWay);
			Module::hookExec('search', array('expr' => $tag, 'total' => sizeof($result)));
			self::$smarty->assign(array(
			'search_tag' => $tag,
			'products' => $result, // DEPRECATED (since to 1.4), not use this: conflict with block_cart module
			'search_products' => $result,
			'nbProducts' => $nbProducts,
			'homeSize' => Image::getSize('home')));
		}
		else
		{
			self::$smarty->assign(array(
			'products' => array(),
			'search_products' => array(),
			'pages_nb' => 1,
			'nbProducts' => 0));
		}
		self::$smarty->assign('add_prod_display', Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'));
	}
	
	
	public function setMedia()
	{
		parent::setMedia();

        Tools::addJS(_THEME_JS_DIR_.'searchfilter.js');
	}
	
}