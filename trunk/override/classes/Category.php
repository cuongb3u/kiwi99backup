<?php

class Category extends CategoryCore{

		public function getFilteredProducts($params, $id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL, $getTotal = false, $allPrices = false, $active = true, $random = false, $randomNumberProducts = 1, $checkAccess = true)
	{
		global $cookie;
		
		if (!$checkAccess OR !$this->checkAccess($cookie->id_customer))
			return false;	
		
		if ($p < 1) $p = 1;

		if (empty($orderBy))
			$orderBy = 'position';
		else
			/* Fix for all modules which are now using lowercase values for 'orderBy' parameter */
			$orderBy = strtolower($orderBy);
			
		if (empty($orderWay))
			$orderWay = 'ASC';
		if ($orderBy == 'id_product' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'manufacturer')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}
		elseif ($orderBy == 'position')
			$orderByPrefix = 'cp';

		if ($orderBy == 'price')
			$orderBy = 'orderprice';
			


		if (!Validate::isBool($active) OR !Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());

//		$id_supplier = (int)(Tools::getValue('id_supplier'));
		
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

		/* Return only the number of products */
		
		if ($getTotal){
			
			if($hasProductPool)
				$sql = 'SELECT p.`price`, ';
			else
				$sql = 'SELECT p.`price`, group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups, ';
		}else{		
			$sql = '
		SELECT p.*, group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, '; 		
		
			if($hasColor || $hasPrice)
				$sql .= ' if(pai.`id_image`,pai.`id_image`,i.`id_image`) as id_image, '; 
			else
				$sql .= ' i.`id_image`, ';
		
			$sql .= ' il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, ';
		}	
		
		$sql .= ' ((IF(sp.`price`,sp.`price`,p.`price`) + IF(pa.`price`,pa.`price`,0)) * IF(sp.`reduction_type` = "percentage",1-sp.`reduction`,1) - IF(sp.`reduction_type` = "amount",sp.`reduction`,0)) AS orderprice ';
		
		$sql .= ' FROM `'._DB_PREFIX_.'category_product` cp
		LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product`
		
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`';

		if (!$getTotal)
			$sql .= ' LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON ag.`id_attribute_group` = agl.`id_attribute_group`
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)($id_lang).')
		INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')';
		
		if($hasColor || $hasPrice)
			$sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pa.`id_product_attribute` = pai.`id_product_attribute` ';
		
		$sql .= ' LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)';
		
		if (!$getTotal)
			$sql .= ' LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')';
		
		$sql .= ' LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product`) LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer` WHERE';
		
		if (!$getTotal)
			$sql .= ' al.`id_lang` = '.(int)($id_lang).'
		AND agl.`id_lang` = '.(int)($id_lang).' AND ';
		
		if(!$hasProductPool)
			$sql .= ($id_attributes_selected ? ' a.`id_attribute` IN ('.$id_attributes_selected.') AND ' : ' ').($id_manufacturers_selected ? ' p.`id_manufacturer` IN ('.$id_manufacturers_selected.') AND ' : ' ').' cp.`id_category` = '.(int)($this->id).($active ? ' AND p.`active` = 1' : '');
		else{
			$sql .= ' p.`id_product` '.$product_pool.($active ? ' AND p.`active` = 1' : '');
			
//			echo $sql; die;
		}
		// if($hasColor)		
		// 	$sql .= ' group by pai.`id_image`';
		// else
		// 	$sql .= ' group by p.`id_product`';	
		
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
			
		if(!$allPrices)
			$sql .= ($hasAttribute ? ' (id_attribute_groups regexp "'.$group_pattern.'") = 1 AND ' : ' ').( $orderprice ? ' orderprice > '.$orderprice['min'].' AND orderprice < '.$orderprice['max'] : ' 1 ');

//die($sql);

//having (id_attributes regexp '[0-9]+,[0-9]+') = 1  counting commas!!!

		if ($random === true)
		{
			$sql .= ' ORDER BY RAND()';
			$sql .= ' LIMIT 0, '.(int)($randomNumberProducts);
		}
		else
		{
			if ($getTotal){
				$sql .= ' ORDER BY orderprice asc';
				
        // die($sql);
			}else{
				$sql .= ' ORDER BY '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
			LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n);
			
      // die($sql);
			}
		}

// if($allPrices)
//   die($sql);



		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
				
		if ($getTotal){
			
			if($allPrices){
				$orderprices = array();
				$limits = array();

				if(is_array($result)){
					foreach ($result as $res_prod) {
						$orderprices[] = (int)$res_prod['orderprice'];
					}
				  $countRes = Db::getInstance(_PS_USE_SQL_SLAVE_)->Affected_Rows() - 1;
				  
          // echo (int)$result[0]['orderprice'] - 1000;
          // echo (int)$result[$countRes]['orderprice'] + 1000;
				  
					$limits['min'] = (int)$result[0]['orderprice'] - 1000;
					$limits['max'] = (int)$result[$countRes]['orderprice'] + 1000;
				}				
				return $limits;
				
			}else{
				$total = Db::getInstance(_PS_USE_SQL_SLAVE_)->Affected_Rows();
				return $total;				
			}
			
		}
		
		if ($orderBy == 'orderprice')
			Tools::orderbyPrice($result, $orderWay);

		if (!$result)
			return false;

		/* Modify SQL result */
		return Product::getProductsProperties($id_lang, $result);
	}
}