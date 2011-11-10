<?php 

class Product extends ProductCore{

	public function getAttributesGroupsOnly($id_lang)
	{
		return Db::getInstance()->ExecuteS('
		SELECT ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, agl.`public_name` AS public_group_name, a.`id_attribute`, al.`name` AS attribute_name,
		a.`color` AS attribute_color, group_concat(pa.`id_product_attribute`) AS id_product_attributes
		FROM `'._DB_PREFIX_.'product_attribute` pa
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
		LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON ag.`id_attribute_group` = agl.`id_attribute_group`
		WHERE pa.`id_product` = '.(int)($this->id).'
		AND al.`id_lang` = '.(int)($id_lang).'
		AND agl.`id_lang` = '.(int)($id_lang).'
		GROUP BY al.`id_attribute`
		ORDER BY ag.`id_attribute_group`, al.`name`');
	}
	
	public function getCombinationImagesWithColor($id_lang)
	{
		if (!$productAttributes = Db::getInstance()->ExecuteS('SELECT `id_product_attribute` FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product` = '.(int)($this->id)))
			return false;
		$ids = array();
		foreach ($productAttributes AS $productAttribute)
			$ids[] = (int)($productAttribute['id_product_attribute']);
		if (!$result = Db::getInstance()->ExecuteS('
			SELECT pac.`id_attribute`, pai.`id_image`, pai.`id_product_attribute`, il.`legend`
			FROM `'._DB_PREFIX_.'product_attribute_image` pai
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (il.`id_image` = pai.`id_image`)
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_image` = pai.`id_image`)
			LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON (pac.id_product_attribute = pai.id_product_attribute)
			LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.id_attribute = pac.id_attribute)
			WHERE pai.`id_product_attribute` IN ('.implode(', ', $ids).') AND il.`id_lang` = '.(int)($id_lang).' AND a.id_attribute_group = 2 group by pac.`id_attribute`'))
			return false;
		$images = array();
		foreach ($result AS $row)
			$images[$row['id_product_attribute']] = $row;
		return $images;
	}
	
	public static function getProductProperties($id_lang, $row)
		{
			if (!$row['id_product'])
				return false;

			// Product::getDefaultAttribute is only called if id_product_attribute is missing from the SQL query at the origin of it: consider adding it in order to avoid unnecessary queries
			$row['allow_oosp'] = Product::isAvailableWhenOutOfStock($row['out_of_stock']);
			if ((!isset($row['id_product_attribute']) OR !$row['id_product_attribute'])
				AND ((isset($row['cache_default_attribute']) AND ($ipa_default = $row['cache_default_attribute']) !== NULL)
					OR ($ipa_default = Product::getDefaultAttribute($row['id_product'], !$row['allow_oosp'])))
			)
				$row['id_product_attribute'] = $ipa_default;
			if (!isset($row['id_product_attribute']))
				$row['id_product_attribute'] = 0;

			// Tax
			$usetax = Tax::excludeTaxeOption();

			$cacheKey = $row['id_image'].'-'.$row['id_product'].'-'.$row['id_product_attribute'].'-'.$id_lang.'-'.(int)($usetax);

			if (array_key_exists($cacheKey, self::$producPropertiesCache))
				return self::$producPropertiesCache[$cacheKey];

			// Datas
			$link = new Link();
			$row['category'] = Category::getLinkRewrite((int)$row['id_category_default'], (int)($id_lang));
			$row['link'] = $link->getProductLink((int)$row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);
			$row['attribute_price'] = (isset($row['id_product_attribute']) AND $row['id_product_attribute']) ? (float)(Product::getProductAttributePrice($row['id_product_attribute'])) : 0;
			$row['price_tax_exc'] = Product::getPriceStatic((int)$row['id_product'], false, ((isset($row['id_product_attribute']) AND !empty($row['id_product_attribute'])) ? (int)($row['id_product_attribute']) : NULL), (self::$_taxCalculationMethod == PS_TAX_EXC ? 2 : 6));
			if (self::$_taxCalculationMethod == PS_TAX_EXC)
			{
				$row['price_tax_exc'] = Tools::ps_round($row['price_tax_exc'], 2);
				$row['price'] = Product::getPriceStatic((int)$row['id_product'], true, ((isset($row['id_product_attribute']) AND !empty($row['id_product_attribute'])) ? (int)($row['id_product_attribute']) : 	NULL), 6);
				$row['price_without_reduction'] = Product::getPriceStatic((int)$row['id_product'], false, ((isset($row['id_product_attribute']) AND !empty($row['id_product_attribute'])) ? (int)($row['id_product_attribute']) : NULL), 2, NULL, false, false);
			}
			else
			{
				$row['price'] = Tools::ps_round(Product::getPriceStatic((int)$row['id_product'], true, ((isset($row['id_product_attribute']) AND !empty($row['id_product_attribute'])) ? (int)($row['id_product_attribute']) : NULL), 2), 2);
				$row['price_without_reduction'] = Product::getPriceStatic((int)$row['id_product'], true, ((isset($row['id_product_attribute']) AND !empty($row['id_product_attribute'])) ? (int)($row['id_product_attribute']) : NULL), 6, NULL, false, false);
			}

			$row['reduction'] = Product::getPriceStatic((int)($row['id_product']), (bool)$usetax, (int)($row['id_product_attribute']), 6, NULL, true, true, 1, true, NULL, NULL, NULL, $specific_prices);
	        $row['specific_prices'] = $specific_prices;

			if ($row['id_product_attribute'])
			{
				$row['quantity_all_versions'] = $row['quantity'];
				$row['quantity'] = Product::getQuantity((int)$row['id_product'], $row['id_product_attribute'], isset($row['cache_is_pack']) ? $row['cache_is_pack'] : NULL);
			}
			$row['id_image'] = Product::defineProductImage($row, $id_lang);
			$row['features'] = Product::getFrontFeaturesStatic((int)$id_lang, $row['id_product']);
			$row['attachments'] = ((!isset($row['cache_has_attachments']) OR $row['cache_has_attachments']) ? Product::getAttachmentsStatic((int)($id_lang), $row['id_product']) : array());

			// Pack management
			$row['pack'] = (!isset($row['cache_is_pack']) ? Pack::isPack($row['id_product']) : (int)$row['cache_is_pack']);
			$row['packItems'] = $row['pack'] ? Pack::getItemTable($row['id_product'], $id_lang) : array();
			$row['nopackprice'] = $row['pack'] ? Pack::noPackPrice($row['id_product']) : 0;
			if ($row['pack'] AND !Pack::isInStock($row['id_product']))
				$row['quantity'] =  0;

			self::$producPropertiesCache[$cacheKey] = $row;
			return self::$producPropertiesCache[$cacheKey];
		}
	

  	/**
  	* Get prices drop
  	*
  	* @param integer $id_lang Language id
  	* @param integer $pageNumber Start from (optional)
  	* @param integer $nbProducts Number of products to return (optional)
  	* @param boolean $count Only in order to get total number (optional)
  	* @return array Prices drop
  	*/
  	public static function getPricesDropFilter($params, $id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL, $beginning = false, $ending = false)
  	{
  		if (!Validate::isBool($count))
  			die(Tools::displayError());

  		if ($pageNumber < 0) $pageNumber = 0;
  		if ($nbProducts < 1) $nbProducts = 10;
  		if (empty($orderBy) || $orderBy == 'position') $orderBy = 'price';
  		if (empty($orderWay)) $orderWay = 'DESC';
  		if ($orderBy == 'id_product' OR $orderBy == 'price' OR $orderBy == 'date_add')
  			$orderByPrefix = 'p';
  		elseif ($orderBy == 'name')
              $orderByPrefix = 'pl';
  		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
  			die (Tools::displayError());
  		$currentDate = date('Y-m-d H:i:s');
  		$ids_product = self::_getProductIdByDate((!$beginning ? $currentDate : $beginning), (!$ending ? $currentDate : $ending));

  		$groups = FrontController::getCurrentCustomerGroups();
  		$sqlGroups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');

      $orderprice = array();

      if(isset($params['prices']) && $params['prices']) {
    		$prices = $params['prices'];
    		$minmax = explode('-',$prices);
    		$orderprice['min'] = $minmax[0] ? (int)$minmax[0] : 0;
    		$orderprice['max']= $minmax[1] ? (int)$minmax[1] : 7000000000000;
  		}
      else{		
      	$orderprice = null;
      }

  		$product_pool = $params['product_pool'];

  		$hasProductPool = 0;

  		if(isset($product_pool) && $product_pool != '')
  			$hasProductPool = 1;
  		else
  			$hasProductPool = 0;

  		$id_manufacturers_selected = $params['brand'] ? $params['brand'] : 0;
  		$id_attributes_selected = $params['id_attribute'] ? $params['id_attribute'] : 0;

  		$hasColor = $params['hasColor'] ? $params['hasColor'] : 0;
  		$hasPrice = $params['hasPrice'] ? $params['hasPrice'] : 0;

  		$group_pattern = '';  		
  		
  		$hasAttribute = 0;
  		if($params['id_attribute']){

  			$groups = Attribute::getGroupPattern($params['id_attribute']);

  			foreach($groups as $group){
  				$group_pattern .= $group['id_attribute_group'];
  				$group_pattern .= '+';
  			}
  			$hasAttribute = 1;
  		}else
  			$group_pattern = '[1-9]+';
  		


  		if ($count)
  		{
  			$sql = '
    			SELECT group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups
    			FROM 
    			`'._DB_PREFIX_.'category_product` cp
      		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
      		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product`

      		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
      		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
      		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
    			LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pa.`id_product_attribute` = pai.`id_product_attribute`
    			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
    			LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product`) 
    			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`

  			WHERE p.`active` = 1
  			AND p.`show_price` = 1
  			'.((!$beginning AND !$ending) ? ' AND p.`id_product` IN('.((is_array($ids_product) AND sizeof($ids_product)) ? implode(', ', $ids_product) : 0).')' : '').'
  			AND ';
  			
  			if(!$hasProductPool)
    			$sql .= ($id_attributes_selected ? ' a.`id_attribute` IN ('.$id_attributes_selected.') AND ' : ' ').($id_manufacturers_selected ? ' p.`id_manufacturer` IN ('.$id_manufacturers_selected.') AND ' : ' ');
    		else{
    			$sql .= ' p.`id_product` '.$product_pool.($active ? ' AND p.`active` = 1' : '');
				}
  			
  			  $sql .= ' p.`id_product` IN (
  				SELECT cp.`id_product`
  				FROM `'._DB_PREFIX_.'category_group` cg
  				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
  				WHERE cg.`id_group` '.$sqlGroups.'
  			)';
  			
  			

              	$groupby = 'id_product';

            		if($hasColor || $hasPrice)
            			$groupby = 'hasColor';
            		if(($hasPrice && !$hasColor) || $hasProductPool)
            			$groupby = 'hasPrice';


            		switch ($groupby) {
            			case 'hasColor':
            				$sql .= ' group by pai.`id_image`';
            				break;
            			case 'hasPrice':
            				$sql .= ' group by orderprice';
            				break;
            			default:
            				$sql .= ' group by p.`id_product`';
            				break;
            		}	

            		if($hasAttribute || $orderprice)
            			$sql .= ' having ';

            		if(1)
            			$sql .= ($hasAttribute ? ' (id_attribute_groups regexp "'.$group_pattern.'") = 1 AND ' : ' AND ').( $orderprice ? ' orderprice > '.$orderprice['min'].' AND orderprice < '.$orderprice['max'] : ' 1 ');


              echo $sql; die; 

          			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
          			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->Affected_Rows();
  			
  			
  		}

  		
  		
  		$sql = '
  		SELECT p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
  		pl.`name`, p.`ean13`, p.`upc`, i.`id_image`, il.`legend`, t.`rate`, m.`name` AS manufacturer_name,
  		DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new
  		FROM `'._DB_PREFIX_.'product` p
  		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
  		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
  		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
  		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
  		                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
  	                                           	   AND tr.`id_state` = 0)
  	    LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
  		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
  		WHERE 1
  		AND p.`active` = 1
  		AND p.`show_price` = 1
  		'.((!$beginning AND !$ending) ? ' AND p.`id_product` IN ('.((is_array($ids_product) AND sizeof($ids_product)) ? implode(', ', $ids_product) : 0).')' : '').'
  		AND p.`id_product` IN (
  			SELECT cp.`id_product`
  			FROM `'._DB_PREFIX_.'category_group` cg
  			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
  			WHERE cg.`id_group` '.$sqlGroups.'
  		)
  		ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'`'.' '.pSQL($orderWay).'
  		LIMIT '.(int)($pageNumber * $nbProducts).', '.(int)($nbProducts);
  		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
  		if ($orderBy == 'price')
  			Tools::orderbyPrice($result,$orderWay);
  		if (!$result)
  			return false;
  		return Product::getProductsProperties($id_lang, $result);
  	}
	
	
  	public static function getNewProductsFilter($params, $id_lang, $pageNumber = 0, $nbProducts = 10, $count = false, $orderBy = NULL, $orderWay = NULL)
  	{
  		if ($pageNumber < 0) $pageNumber = 0;
  		if ($nbProducts < 1) $nbProducts = 10;
  		if (empty($orderBy) || $orderBy == 'position') $orderBy = 'date_add';
  		if (empty($orderWay)) $orderWay = 'DESC';
  		if ($orderBy == 'id_product' OR $orderBy == 'price' OR $orderBy == 'date_add')
  			$orderByPrefix = 'p';
  		elseif ($orderBy == 'name')
  			$orderByPrefix = 'pl';
  		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
  			die(Tools::displayError());

  		$groups = FrontController::getCurrentCustomerGroups();
  		$sqlGroups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
  		

      $orderprice = array();

      if(isset($params['prices']) && $params['prices']) {
    		$prices = $params['prices'];
    		$minmax = explode('-',$prices);
    		$orderprice['min'] = $minmax[0] ? (int)$minmax[0] : 0;
    		$orderprice['max']= $minmax[1] ? (int)$minmax[1] : 7000000000000;
  		}
      else{		
      	$orderprice = null;
      }

  		$product_pool = $params['product_pool'];

  		$hasProductPool = 0;

  		if(isset($product_pool) && $product_pool != '')
  			$hasProductPool = 1;
  		else
  			$hasProductPool = 0;

  		$id_manufacturers_selected = $params['brand'] ? $params['brand'] : 0;
  		$id_attributes_selected = $params['id_attribute'] ? $params['id_attribute'] : 0;

  		$hasColor = $params['hasColor'] ? $params['hasColor'] : 0;
  		$hasPrice = $params['hasPrice'] ? $params['hasPrice'] : 0;

  		$group_pattern = '';  		
  		
  		$hasAttribute = 0;
  		if($params['id_attribute']){

  			$groups = Attribute::getGroupPattern($params['id_attribute']);

  			foreach($groups as $group){
  				$group_pattern .= $group['id_attribute_group'];
  				$group_pattern .= '+';
  			}
  			$hasAttribute = 1;
  		}else
  			$group_pattern = '[1-9]+';
  		
  		
  		

  		if ($count)
  		{
  		  $countQuery = '
  			SELECT group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups
  			FROM 
  			`'._DB_PREFIX_.'category_product` cp
    		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
    		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product`

    		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
    		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
    		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
  			LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pa.`id_product_attribute` = pai.`id_product_attribute`
  			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
  			LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product`) 
  			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
  			
  			
  			WHERE p.`active` = 1 AND ';
  			
				if(!$hasProductPool)
    			$countQuery .= ($id_attributes_selected ? ' a.`id_attribute` IN ('.$id_attributes_selected.') AND ' : ' ').($id_manufacturers_selected ? ' p.`id_manufacturer` IN ('.$id_manufacturers_selected.') AND ' : ' ');
    		else{
    			$countQuery .= ' p.`id_product` '.$product_pool.($active ? ' AND p.`active` = 1' : '');

    //			echo $sql; die;
    		}
        
  			
  			$countQuery .= '
  			DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0
  			AND p.`id_product` IN (
  				SELECT cp.`id_product`
  				FROM `'._DB_PREFIX_.'category_group` cg
  				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
  				WHERE cg.`id_group` '.$sqlGroups.'
  			)';
  			
      	$groupby = 'id_product';

    		if($hasColor || $hasPrice)
    			$groupby = 'hasColor';
    		if(($hasPrice && !$hasColor) || $hasProductPool)
    			$groupby = 'hasPrice';


    		switch ($groupby) {
    			case 'hasColor':
    				$countQuery .= ' group by pai.`id_image`';
    				break;
    			case 'hasPrice':
    				$countQuery .= ' group by orderprice';
    				break;
    			default:
    				$countQuery .= ' group by p.`id_product`';
    				break;
    		}	

    		if($hasAttribute || $orderprice)
    			$countQuery .= ' having ';

    		if(1)
    			$countQuery .= ($hasAttribute ? ' (id_attribute_groups regexp "'.$group_pattern.'") = 1 AND ' : ' AND ').( $orderprice ? ' orderprice > '.$orderprice['min'].' AND orderprice < '.$orderprice['max'] : ' 1 ');
    			
    			
//    		echo $countQuery; die;	
    		  			
  			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($countQuery);
  			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->Affected_Rows();
  		}


      $query = '
  		SELECT p.*, ((IF(sp.`price`,sp.`price`,p.`price`) + IF(pa.`price`,pa.`price`,0)) * IF(sp.`reduction_type` = "percentage",1-sp.`reduction`,1) - IF(sp.`reduction_type` = "amount",sp.`reduction`,0)) AS orderprice, group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`, p.`upc`,
  			i.`id_image`, il.`legend`, t.`rate`, m.`name` AS manufacturer_name, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new,
  			(p.`price` * ((100 + (t.`rate`))/100)) AS orderprice, pa.id_product_attribute
  		FROM `'._DB_PREFIX_.'product` p
  		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
  		LEFT OUTER JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND `default_on` = 1)

		  LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
  		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
  		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
      LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`  		
      LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON ag.`id_attribute_group` = agl.`id_attribute_group`      

      LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pa.`id_product_attribute` = pai.`id_product_attribute`      
      
      LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product`) 
            
  		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
  		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
  		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
  		   AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
  		   AND tr.`id_state` = 0)
  	    LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
  		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
  		WHERE p.`active` = 1 AND al.`id_lang` = '.(int)($id_lang).'
		AND agl.`id_lang` = '.(int)($id_lang).' AND ';
		
		if(!$hasProductPool)
			$query .= ($id_attributes_selected ? ' a.`id_attribute` IN ('.$id_attributes_selected.') AND ' : ' ').($id_manufacturers_selected ? ' p.`id_manufacturer` IN ('.$id_manufacturers_selected.') ' : ' ');
		else
			$query .= ' p.`id_product` '.$product_pool.($active ? ' AND p.`active` = 1' : '');
		
  		$query .= ' AND DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0
  		AND p.`id_product` IN (
  			SELECT cp.`id_product`
  			FROM `'._DB_PREFIX_.'category_group` cg
  			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
  			WHERE cg.`id_group` '.$sqlGroups.'
  		) ';
  		
		$groupby = 'id_product';
		
		if($hasColor || $hasPrice)
			$groupby = 'hasColor';
		if(($hasPrice && !$hasColor) || $hasProductPool)
			$groupby = 'hasPrice';
		
		
		switch ($groupby) {
			case 'hasColor':
				$query .= ' group by pai.`id_image`';
				break;
			case 'hasPrice':
				$query .= ' group by orderprice';
				break;
			default:
				$query .= ' group by p.`id_product`';
				break;
		}	
  		
  		
  		if($hasAttribute || $orderprice)
  			$query .= ' having ';

  		if(1)
  			$query .= ($hasAttribute ? ' (id_attribute_groups regexp "'.$group_pattern.'") = 1 AND ' : ' AND ').( $orderprice ? ' orderprice > '.$orderprice['min'].' AND orderprice < '.$orderprice['max'] : ' 1 ');
  		
  		
  		$query .= ' ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
  		LIMIT '.(int)($pageNumber * $nbProducts).', '.(int)($nbProducts);


  		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

  		if ($orderBy == 'price')
  			Tools::orderbyPrice($result, $orderWay);
  		if (!$result)
  			return false;

  		$productsIds = array();
  		foreach ($result as $row)
  			$productsIds[] = $row['id_product'];
  		// Thus you can avoid one query per product, because there will be only one query for all the products of the cart
  		Product::cacheFrontFeatures($productsIds, $id_lang);

  		return Product::getProductsProperties((int)$id_lang, $result);
  	}	
	
}