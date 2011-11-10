<?php
/*
* Override CategoryController class
* Author : Le Ton Vinh <letonvinh@yahoo.com >
* 
*/

class CategoryController extends CategoryControllerCore
{


		public function canonicalRedirection()
		{

				$search_query = Tools::getValue('search_query') ? Tools::vn_str_filter(Tools::getValue('search_query')) : '';
				$shop = Tools::getValue('shop');

				$is_ajax_search = Tools::getValue('is_search');
				$id_category = (int)Tools::getValue('id_category');

			// Automatically redirect to the canonical URL if the current in is the right one
			// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
			if (Validate::isLoadedObject($this->category))
			{

	/*
				$currentURL = self::$link->getCategoryLink($this->category);
				$currentURL = preg_replace('/[?&].*$/', '', $currentURL);
	*/

				if(Tools::getValue('filter')){

						$currentURL = _PS_BASE_URL_.__PS_BASE_URI__.'category.php?id_category='.$id_category;						

						if($search_query){
							$search_query = preg_replace('/ /', '+', $search_query);						
							$currentURL .= '&search_query=' . $search_query . '&shop=' . $shop;
						}

					}else{

						$currentURL = self::$link->getCategoryLink($this->category);
						$currentURL = preg_replace('/[?&].*$/', '', $currentURL);			

						if($search_query){
							$search_query = preg_replace('/ /', '+', $search_query);
							$currentURL .= '?search_query=' . $search_query . '&shop=' . $shop;
						}
					}


				if (!preg_match('/^'.Tools::pRegexp($currentURL, '/').'([&?].*)?$/', Tools::getProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) && $search_query && !Tools::getValue('filter'))
				{
					header('HTTP/1.0 301 Moved');
					if (defined('_PS_MODE_DEV_') AND _PS_MODE_DEV_ )
						die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$currentURL.'">'.$currentURL.'</a>');
					Tools::redirectLink($currentURL);
				}

			}
		}

		public function preProcess()
		{
			if ($id_category = (int)Tools::getValue('id_category'))
				$this->category = new Category($id_category, self::$cookie->id_lang);
						
			if (!Validate::isLoadedObject($this->category))
			{
				header('HTTP/1.1 404 Not Found');
				header('Status: 404 Not Found');
			}
			else
				$this->canonicalRedirection();

			parent::preProcess();


			if((int)(Configuration::get('PS_REWRITING_SETTINGS')))
				if ($id_category = (int)Tools::getValue('id_category'))
				{
					$rewrite_infos = Category::getUrlRewriteInformations((int)$id_category);

					$default_rewrite = array();
					foreach ($rewrite_infos AS $infos)
						$default_rewrite[$infos['id_lang']] = self::$link->getCategoryLink((int)$id_category, $infos['link_rewrite'], $infos['id_lang']);

					self::$smarty->assign('lang_rewrite_urls', $default_rewrite);
				}

		}
		
		


	public function preProcess2()
		{
			
			$search_query = Tools::getValue('search_query');
			$is_ajax_search = Tools::getValue('is_search');
			

			
			if ($id_category = (int)Tools::getValue('id_category'))
				$this->category = new Category($id_category, self::$cookie->id_lang);

			if (!Validate::isLoadedObject($this->category))
			{
				header('HTTP/1.1 404 Not Found');
				header('Status: 404 Not Found');
			}
			else
			{
			
/*
			if (Validate::isLoadedObject($this->category))
		{}
*/

				// Automatically redirect to the canonical URL if the current in is the right one
				// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
				$currentURL = '';



				if(Tools::getValue('filter')){

					$currentURL = _PS_BASE_URL_.__PS_BASE_URI__.'category.php?id_category='.$id_category;						
					
					if($search_query){
						$search_query = preg_replace('/ /', '+', $search_query);						
						$currentURL .= '&search_query=' . $search_query;
					}

				}else{

					$currentURL = self::$link->getCategoryLink($this->category);

					$currentURL = preg_replace('/[?&].*$/', '', $currentURL);			
					
					if($search_query){
						$search_query = preg_replace('/ /', '+', $search_query);
						$currentURL .= '?search_query=' . $search_query;
					}
				}

//				echo $currentURL; die;

				if (!preg_match('/^'.Tools::pRegexp($currentURL, '/').'([&?].*)?$/', Tools::getProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) && $search_query && !Tools::getValue('filter'))
				{
					header('HTTP/1.0 301 Moved');
					if (defined('_PS_MODE_DEV_') AND _PS_MODE_DEV_ )
						die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$currentURL.'">'.$currentURL.'</a>');
					Tools::redirectLink($currentURL);
				}
			}

			
			
			parent::preProcess();
			


			if((int)(Configuration::get('PS_REWRITING_SETTINGS')))
				if ($id_category = (int)Tools::getValue('id_category'))
				{
					$rewrite_infos = Category::getUrlRewriteInformations((int)$id_category);

					$default_rewrite = array();
					foreach ($rewrite_infos AS $infos)
						$default_rewrite[$infos['id_lang']] = self::$link->getCategoryLink((int)$id_category, $infos['link_rewrite'], $infos['id_lang']);

					self::$smarty->assign('lang_rewrite_urls', $default_rewrite);
				}
		}
		

	public function process()
	{

//		parent::process(); for ajax filter reason duplicate!

		if (!($id_category = (int)Tools::getValue('id_category')) OR !Validate::isUnsignedId($id_category)){
			$this->errors[] = Tools::displayError('Missing category ID');
		}
		else
		{
			
			$filter = Tools::getValue('filter') ? Tools::getValue('filter') : 0 ;						
			$search_query = Tools::getValue('search_query');
			$is_ajax_search = Tools::getValue('is_search');

			if (!Validate::isLoadedObject($this->category))
				$this->errors[] = Tools::displayError('Category does not exist');
			elseif (!$this->category->checkAccess((int)(self::$cookie->id_customer)))
				$this->errors[] = Tools::displayError('You do not have access to this category.');
			elseif (!$this->category->active)
				self::$smarty->assign('category', $this->category);
			else
			{
				$rewrited_url = self::$link->getCategoryLink((int)$this->category->id, $this->category->link_rewrite);

				/* Scenes  (could be externalised to another controler if you need them */
				self::$smarty->assign('scenes', Scene::getScenes((int)($this->category->id), (int)(self::$cookie->id_lang), true, false));
				
				/* Scenes images formats */
				if ($sceneImageTypes = ImageType::getImagesTypes('scenes'))
				{
					foreach ($sceneImageTypes AS $sceneImageType)
					{
						if ($sceneImageType['name'] == 'thumb_scene')
							$thumbSceneImageType = $sceneImageType;
						elseif ($sceneImageType['name'] == 'large_scene')
							$largeSceneImageType = $sceneImageType;
					}
					self::$smarty->assign('thumbSceneImageType', isset($thumbSceneImageType) ? $thumbSceneImageType : NULL);
					self::$smarty->assign('largeSceneImageType', isset($largeSceneImageType) ? $largeSceneImageType : NULL);
				}

				$this->category->description = nl2br2($this->category->description);
				$subCategories = $this->category->getSubCategories((int)(self::$cookie->id_lang));
				self::$smarty->assign('category', $this->category);	
				
				if (isset($subCategories) AND !empty($subCategories) AND $subCategories)
				{
					self::$smarty->assign('subcategories', $subCategories);
					self::$smarty->assign(array(
						'subcategories_nb_total' => sizeof($subCategories),
						'subcategories_nb_half' => ceil(sizeof($subCategories) / 2)));
				}
				
				$filteredData = array();
				
				if ($this->category->id != 1)
				{

					$filter = Tools::getValue('filter') ? Tools::getValue('filter') : 0 ;

					$params = array();
					
					$limits = array();
																									
					$id_attribute = Tools::getValue('id_attribute');
					$hasColor = Tools::getValue('hasColor');
					$hasPrice = Tools::getValue('hasPrice');
					
//					$hasColor = ($hasColor || $hasPrice);
					
					$params['hasColor'] = $hasColor;
					$params['hasPrice'] = $hasPrice;
					
					$params['product_pool'] = '';
					
					$allBrand = Manufacturer::getManufacturers();
					
					$allBrandByThisCategory = Manufacturer::getBrands($this->category->id);
					
					$brand_str = '';
					
					foreach($allBrand as $brand){
						$brand_str .= $brand['id_manufacturer'];
						$brand_str .= ',';
					}
					
					$brand_str = rtrim($brand_str,',');
									
					$params['brand'] = Tools::getValue('brand') ? Tools::getValue('brand') : $brand_str;
					$price = Tools::getValue('prices') ? Tools::getValue('prices') : 0;
										
					$params['prices'] = $price ? $price : '';
					
					$params['id_attribute'] = $id_attribute ? $id_attribute : '';
					
					$allAttributesByThisCategory = Attribute::getAttributesByCategory((int)(self::$cookie->id_lang), $this->category->id);
					$allColorsByThisCategory = Attribute::getColorsByCategory((int)(self::$cookie->id_lang), $this->category->id);
										
					if($filter && (Tools::getValue('brand') || Tools::getValue('id_attribute') || Tools::getValue('prices'))){

						if($is_ajax_search){
							
//							echo 'ajax search'; die;
							
						}else{
							$nbProducts = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), 1, 1000, $this->orderBy, $this->orderWay, true);
							
							$this->pagination((int)$nbProducts);
							self::$smarty->assign('nb_products', (int)$nbProducts);
							
							$pagination  = self::$smarty->fetch(_PS_THEME_DIR_.'pagination.tpl');						
							
							$filteredData['pagination'] = $pagination;
														
						}
//							$limits = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), 1, 1000, $this->orderBy, $this->orderWay, true, true);							
//  						echo $nbProducts; die;	
					}else{	
						
						if($search_query && !$is_ajax_search){

//							echo 'no ajax search'; die;
							
						}else{
												
							$nbProducts = $this->category->getProducts(NULL, NULL, NULL, $this->orderBy, $this->orderWay, true);
							
							$this->pagination((int)$nbProducts);
							self::$smarty->assign('nb_products', (int)$nbProducts);							
						}
//							$limits = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), 1, 1000, $this->orderBy, $this->orderWay, true, true);														
					}

					$limits = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), 1, 1000, $this->orderBy, $this->orderWay, true, true);
					
										
					$this->orderBy = Tools::getValue('orderby') ? Tools::getValue('orderby') : 'date_add';
					$this->orderWay = Tools::getValue('orderway') ? Tools::getValue('orderway') : 'desc';					
				
					$cat_products = null;
					
					$orderprices = array();				
					

					$min_slider = isset($limits['min']) ? $limits['min'] - 1000 : 0;
					$max_slider = isset($limits['max']) ? $limits['max'] + 1000 : 10000000;


					if($search_query && !$is_ajax_search){
						
						$is_ajax_search = 1;

						$this->productSort();
						$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
						$this->p = abs((int)(Tools::getValue('p', 1)));

						$this->orderBy = Tools::getValue('orderby') ? Tools::getValue('orderby') : 'date_add';
						$this->orderWay = Tools::getValue('orderway') ? Tools::getValue('orderway') : 'desc';					

            if($search_query == 'new1prod2arrivals'){

  						$this->orderBy = 'date_add';
  						$this->orderWay = 'desc';					

  						$search['total'] = (int)(Product::getNewProducts((int)(self::$cookie->id_lang), isset($this->p) ? (int)($this->p) - 1 : NULL, isset($this->n) ? (int)($this->n) : NULL, true));
              
              $this->pagination($search['total']);
                // public static function getNewProducts($id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL)
              $search['result'] = Product::getNewProducts((int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);
              
              // var_dump($search['result']); die;
              if(count($search['result'])){
                $allnewproducts = Product::getNewProducts((int)(self::$cookie->id_lang), 0, 1710, false, $this->orderBy, $this->orderWay);
                
                $search['productPool'] = Attribute::build_product_pool($allnewproducts);
              }else{
                $search['productPool'] = null;
              }
              
//              $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;
              
            }elseif($search_query == 'prices1drop2specials'){

  						$this->orderBy = 'price';
  						$this->orderWay = 'asc';					

  						$search['total'] = Product::getPricesDrop((int)(self::$cookie->id_lang), NULL, NULL, true);
              
              $this->pagination($search['total']);
              
              $search['result'] = Product::getPricesDrop((int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);
              
              $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;

              
            }else{

  						$search = Search::find((int)(self::$cookie->id_lang), $search_query, $this->p, $this->n, $this->orderBy, $this->orderWay);
            }  					
  					
  					$nbProducts = $search['total'];

						$this->pagination((int)$nbProducts);
						self::$smarty->assign('nb_products', (int)$nbProducts);
            if($nbProducts){
						  $allAttributesByThisCategory = Attribute::getAttributesAfterSearch((int)(self::$cookie->id_lang), $search['productPool']);
						  $allColorsByThisCategory = Attribute::getColorsAfterSearch((int)(self::$cookie->id_lang), $search['productPool']);
						  $params['product_pool'] = $search['productPool'];
						}else{
						  $allAttributesByThisCategory = 0;
						  $allColorsByThisCategory = 0;
						  $params['product_pool'] = '';
						}
						
						$limits = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), 1, 1000, $this->orderBy, $this->orderWay, true, true);
						
						$min_slider = isset($limits['min']) ? $limits['min'] - 1000 : 0;
						$max_slider = isset($limits['max']) ? $limits['max'] + 1000 : 10000000;
						
						$nbProducts = $search['total'];
						$this->pagination($nbProducts);

						$cat_products = $search['result'];						
						
					}elseif($filter && (Tools::getValue('search_query') || Tools::getValue('brand') || Tools::getValue('id_attribute') || Tools::getValue('prices'))){

						if($is_ajax_search){

							$is_ajax_search = 1;

							$this->productSort();
							
							$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
							$this->p = abs((int)(Tools::getValue('p', 1)));

							$this->orderBy = Tools::getValue('orderby') ? Tools::getValue('orderby') : 'date_add';
							$this->orderWay = Tools::getValue('orderway') ? Tools::getValue('orderway') : 'desc';					

              if($search_query == 'new1prod2arrivals'){

                if(Attribute::isReset($params)){

      						$search['total'] = (int)(Product::getNewProducts((int)(self::$cookie->id_lang), isset($this->p) ? (int)($this->p) - 1 : NULL, isset($this->n) ? (int)($this->n) : NULL, true));

                  $this->pagination($search['total']);
                  
                  $search['result'] = Product::getNewProducts((int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);

                  $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;

                  
                }else{

      						$search['total'] = (int)(Product::getNewProductsFilter($params, (int)(self::$cookie->id_lang), isset($this->p) ? (int)($this->p) - 1 : NULL, isset($this->n) ? (int)($this->n) : NULL, true));

                  $this->pagination($search['total']);

                  $search['result'] = Product::getNewProductsFilter($params, (int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);

                  $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;
                  
                }
                

              }elseif($search_query == 'prices1drop2specials'){
              
                if(Attribute::isReset($params)){

      						$search['total'] = Product::getPricesDrop((int)(self::$cookie->id_lang), NULL, NULL, true);

                  $this->pagination($search['total']);      						

                  $search['result'] = Product::getPricesDrop((int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);

                  $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;

                  
                }else{

      						$search['total'] = Product::getPricesDropFilter($params, (int)(self::$cookie->id_lang), NULL, NULL, true);
      						
      						$this->pagination($search['total']);

                  $search['result'] = Product::getPricesDropFilter($params, (int)(self::$cookie->id_lang), (int)($this->p) - 1, (int)($this->n), false, $this->orderBy, $this->orderWay);

                  $search['productPool'] = count($search['result']) ? Attribute::build_product_pool($search['result']) : null;
                  
                }
              
              }else{

							  $search = Search::findfilter($params, (int)(self::$cookie->id_lang), $search_query, $this->p, $this->n, $this->orderBy, $this->orderWay);
              
              }							
					 // findfilter($params, $id_lang, $expr, $pageNumber = 1, $pageSize = 1, $orderBy = 'position', $orderWay = 'desc', $allPrices = false, $ajax = false, $useCookie = true)
							
							$nbProducts = $search['total'];
							$this->pagination($nbProducts);													
							self::$smarty->assign('nb_products', (int)$nbProducts);							

							$pagination  = self::$smarty->fetch(_PS_THEME_DIR_.'pagination.tpl');
							$filteredData['pagination'] = $pagination;

							$cat_products = $search['result'];						
							
						}else{							
														
							$cat_products = $this->category->getFilteredProducts($params, (int)(self::$cookie->id_lang), (int)($this->p), (int)($this->n), $this->orderBy, $this->orderWay);
							
						}						
						
						$filter_ipas = '';
						$remainAttributes = '';
						
						if(isset($cat_products) AND $cat_products){
							foreach($cat_products as $cat_product){
						
								$filter_ipas .= $cat_product['id_product_attribute'];
								$filter_ipas .= ',';
							}
				
							$filter_ipas = rtrim($filter_ipas,',');
					
							$remainAttributes = Attribute::getAttributes_id_pa($filter_ipas);
						}

					}else{

						$shop = Tools::getValue('shop') ? Tools::getValue('shop') : 0;

						$cat_products = $this->category->getProducts((int)(self::$cookie->id_lang), (int)($this->p), (int)($this->n), $this->orderBy, $this->orderWay);
						
												
					}
	
					if(isset($cat_products) AND $cat_products){
						foreach($cat_products as &$cat_product){
//							$orderprices[] = $cat_product['orderprice'];
							
							if(!$hasColor){
								$id_product = $cat_product['id_product'];
							
								$product = new Product($id_product, true, self::$cookie->id_lang);
							
								$combinationImages = $product->getCombinationImagesWithColor((int)(self::$cookie->id_lang));

								$cat_product['combinationImages'] = $combinationImages;
							}else{
								$cat_product['combinationImages'] = null;
							}
						}
					
//					sort($orderprices);
//					$interval = array('0_50','51_99','100_150','151_299','300_699','700_1000');											
					}
				}
				
				self::$smarty->assign(array(
					'search_query' => $search_query,
					'is_search' => $is_ajax_search,
					'products' => (isset($cat_products) AND $cat_products) ? $cat_products : NULL,
					'allBrandByThisCategory' => $allBrandByThisCategory,
					'allAttributesByThisCategory' => $allAttributesByThisCategory,
					'allColorsByThisCategory' => $allColorsByThisCategory,
					'min_slider' => $min_slider,
					'max_slider' => $max_slider,
					'orderprices' => $orderprices,
					'id_category' => (int)($this->category->id),
					'id_category_parent' => (int)($this->category->id_parent),
					'return_category_name' => Tools::safeOutput($this->category->name),
					'path' => Tools::getPath((int)($this->category->id)),
					'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
					'categorySize' => Image::getSize('category'),
					'mediumSize' => Image::getSize('medium'),
					'thumbSceneSize' => Image::getSize('thumb_scene'),
					'homeSize' => Image::getSize('home')
				));

				if (isset(self::$cookie->id_customer))
					self::$smarty->assign('compareProducts', CompareProduct::getCustomerCompareProducts((int)self::$cookie->id_customer));			
				elseif (isset(self::$cookie->id_guest))
					self::$smarty->assign('compareProducts', CompareProduct::getGuestCompareProducts((int)self::$cookie->id_guest));

				if($filter){
					$shop='shop'.Tools::getValue('shop');
					$catimgtpl = self::$smarty->fetch(_PS_THEME_DIR_.$shop.'/box/cat-img.tpl');
					$productList = self::$smarty->fetch(_PS_THEME_DIR_.$shop.'/product-list.tpl');
				  $pagination  = self::$smarty->fetch(_PS_THEME_DIR_.$shop.'/pagination.tpl');
					$classFilter = self::$smarty->fetch(_PS_THEME_DIR_.$shop.'/leftmenu-filter.tpl');
					$filteredData['catimgtpl'] = $catimgtpl;
					$filteredData['productList'] = $productList;
          $filteredData['pagination'] = $pagination;
					$filteredData['classFilter'] = $classFilter;
					echo json_encode($filteredData);
					die;
				}				
			}
		}

		self::$smarty->assign(array(
			'allow_oosp' => (int)(Configuration::get('PS_ORDER_OUT_OF_STOCK')),
			'comparator_max_item' => (int)(Configuration::get('PS_COMPARATOR_MAX_ITEM')),
			'suppliers' => Supplier::getSuppliers()
		));
	}

	public function displayContent()
	{
		FrontController::displayContent();
		$shop=Tools::getValue('shop');
		if ($shop) {
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_."shop$shop/category.tpl"));
		}
		else{
			self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'category.tpl'));
		}
		//self::$smarty->display(_PS_THEME_DIR_.'category.tpl');
	}
	public function setMedia()
	{
		FrontController::setMedia();
		$shop=Tools::getValue('shop');
		if ($shop) {
				Tools::addCSS(array(
			_PS_CSS_DIR_.'jquery.cluetip.css' => 'all', 
			_THEME_CSS_DIR_.'shop'.$shop.'/scenes.css' => 'all',
			_THEME_CSS_DIR_.'shop'.$shop.'/product-detail.css' => 'all',
			_THEME_CSS_DIR_.'shop'.$shop.'/category.css' => 'all',
			_THEME_CSS_DIR_.'shop'.$shop.'/product_list.css' => 'all'));

		if (Configuration::get('PS_COMPARATOR_MAX_ITEM') > 0)
			Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/products-comparison.js');
			Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop.'/jslider.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop.'/product.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop.'/product-detail.css', 'all');
			Tools::addJs(_THEME_JS_DIR_.'shop'.$shop.'/jquery.hoverIntent.minified.js');
		    Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/jquery.dependClass.js');
		    Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/jquery.slider-min.js');		
		    Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/jquery/jquery.simpletip-1.3.1.js');
		    Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/filter.js');
		      Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/category.js');
					Tools::addCSS(_PS_CSS_DIR_.'jqzoom.css', 'screen');
			
		}
		else
			{
					Tools::addCSS(array(
			_PS_CSS_DIR_.'jquery.cluetip.css' => 'all',
			_THEME_CSS_DIR_.'scenes.css' => 'all',
			_THEME_CSS_DIR_.'category.css' => 'all',
			_THEME_CSS_DIR_.'product_list.css' => 'all'));

		if (Configuration::get('PS_COMPARATOR_MAX_ITEM') > 0)
			Tools::addJS(_THEME_JS_DIR_.'products-comparison.js');
			Tools::addCSS(_THEME_CSS_DIR_.'jslider.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'product.css', 'all');
			Tools::addJs(_THEME_JS_DIR_.'jquery.hoverIntent.minified.js');
		    Tools::addJS(_THEME_JS_DIR_.'jquery.dependClass.js');
		    Tools::addJS(_THEME_JS_DIR_.'jquery.slider-min.js');		
		    Tools::addJS(_THEME_JS_DIR_.'jquery/jquery.simpletip-1.3.1.js');
		    Tools::addJS(_THEME_JS_DIR_.'filter.js');
	    }
	}

}

