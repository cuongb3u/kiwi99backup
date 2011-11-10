<?php

class Search extends SearchCore{
	
	public static function find($id_lang, $expr, $pageNumber = 1, $pageSize = 1, $orderBy = 'position', $orderWay = 'desc', $ajax = false, $useCookie = true)
		{
			global $cookie;
			$db = Db::getInstance(_PS_USE_SQL_SLAVE_);

			// Only use cookie if id_customer is not present
			if ($useCookie)
				$id_customer = (int)$cookie->id_customer;
			else
				$id_customer = 0;

			// TODO : smart page management
			if ($pageNumber < 1) $pageNumber = 1;
			if ($pageSize < 1) $pageSize = 1;

			if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
				return false;

			$intersectArray = array();
			$scoreArray = array();
			$words = explode(' ', Search::sanitize($expr, (int)$id_lang));

			foreach ($words AS $key => $word)
				if (!empty($word) AND strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN'))
				{
					$word = str_replace('%', '\\%', $word);
					$word = str_replace('_', '\\_', $word);
					$intersectArray[] = 'SELECT id_product
						FROM '._DB_PREFIX_.'search_word sw
						LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
						WHERE sw.id_lang = '.(int)$id_lang.'
						AND sw.word LIKE 
						'.($word[0] == '-'
							? ' \''.pSQL(Tools::substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
							: '\''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
						);
					if ($word[0] != '-')
						$scoreArray[] = 'sw.word LIKE \''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
				}
				else
					unset($words[$key]);

			if (!sizeof($words))
				return ($ajax ? array() : array('total' => 0, 'result' => array()));

			$score = '';
			if (sizeof($scoreArray))
				$score = ',(
					SELECT SUM(weight)
					FROM '._DB_PREFIX_.'search_word sw
					LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = '.(int)$id_lang.'
					AND si.id_product = p.id_product
					AND ('.implode(' OR ', $scoreArray).')
				) position';

			$result = $db->ExecuteS('
			SELECT cp.`id_product`
			FROM `'._DB_PREFIX_.'category_group` cg
			INNER JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cg.`id_category`
			INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
			INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
			WHERE c.`active` = 1 AND p.`active` = 1 AND indexed = 1
			AND cg.`id_group` '.(!$id_customer ?  '= 1' : 'IN (
				SELECT id_group FROM '._DB_PREFIX_.'customer_group
				WHERE id_customer = '.(int)$id_customer.'
			)'), false);


			$eligibleProducts = array();
			while ($row = $db->nextRow($result))
				$eligibleProducts[] = $row['id_product'];
			foreach ($intersectArray as $query)
			{
				$result = $db->ExecuteS($query, false);
				$eligibleProducts2 = array();
				while ($row = $db->nextRow($result))
					$eligibleProducts2[] = $row['id_product'];

				$eligibleProducts = array_intersect($eligibleProducts, $eligibleProducts2);
				if (!count($eligibleProducts))
					return ($ajax ? array() : array('total' => 0, 'result' => array()));
			}
			array_unique($eligibleProducts);
			echo 'gasg';die;

			$productPool = '';
			foreach ($eligibleProducts AS $id_product)
				if ($id_product)
					$productPool .= (int)$id_product.',';
			if (empty($productPool))
				return ($ajax ? array() : array('total' => 0, 'result' => array()));
			$productPool = ((strpos($productPool, ',') === false) ? (' = '.(int)$productPool.' ') : (' IN ('.rtrim($productPool, ',').') '));

			if ($ajax)
			{
				return $db->ExecuteS('
				SELECT DISTINCT p.id_product, pl.name pname, cl.name cname,
					cl.link_rewrite crewrite, pl.link_rewrite prewrite '.$score.'
				FROM '._DB_PREFIX_.'product p
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
				INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$id_lang.')
				WHERE p.`id_product` '.$productPool.'
				ORDER BY position DESC LIMIT 10');
			}

			$queryResults = '
			SELECT p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
				tax.`rate`, i.`id_image`, il.`legend`, m.`name` manufacturer_name '.$score.', DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 new
			FROM '._DB_PREFIX_.'product p
			INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
			                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
		                                           	   AND tr.`id_state` = 0)
		    LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = tr.`id_tax`)
			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
			WHERE p.`id_product` '.$productPool.'
			'.($orderBy ? 'ORDER BY  '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
			LIMIT '.(int)(($pageNumber - 1) * $pageSize).','.(int)$pageSize;



			$result = $db->ExecuteS($queryResults);
			$total = $db->getValue('SELECT COUNT(*)
			FROM '._DB_PREFIX_.'product p
			INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
			                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
		                                           	   AND tr.`id_state` = 0)
		    LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = tr.`id_tax`)
			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
			WHERE p.`id_product` '.$productPool);

			if (!$result)
				$resultProperties = false;
			else
				$resultProperties = Product::getProductsProperties((int)$id_lang, $result);

			return array('total' => $total,'result' => $resultProperties, 'productPool' => $productPool);				
		}
	





	
		public static function findfilter($params, $id_lang, $expr, $pageNumber = 1, $pageSize = 1, $orderBy = 'position', $orderWay = 'desc', $allPrices = false, $ajax = false, $useCookie = true)
		{
			global $cookie;
			$db = Db::getInstance(_PS_USE_SQL_SLAVE_);


			
			
			if(isset($params['prices']) && $params['prices']) {



					$prices = $params['prices'];
					$minmax = explode('-',$prices);
					$orderprice['min'] = $minmax[0] ? (int)$minmax[0] : 0;
					$orderprice['max']= $minmax[1] ? (int)$minmax[1] : 7000000000000;
					}
			else{		
				$orderprice = null;
			}
			
			
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
				
				
			

			// Only use cookie if id_customer is not present
			if ($useCookie)
				$id_customer = (int)$cookie->id_customer;
			else
				$id_customer = 0;

			// TODO : smart page management
			if ($pageNumber < 1) $pageNumber = 1;
			if ($pageSize < 1) $pageSize = 1;

			if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
				return false;

			$intersectArray = array();
			$scoreArray = array();
			$words = explode(' ', Search::sanitize($expr, (int)$id_lang));

//			var_dump($expr); die;

			foreach ($words AS $key => $word)
				if (!empty($word) AND strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN'))
				{
					$word = str_replace('%', '\\%', $word);
					$word = str_replace('_', '\\_', $word);
					$intersectArray[] = 'SELECT id_product
						FROM '._DB_PREFIX_.'search_word sw
						LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
						WHERE sw.id_lang = '.(int)$id_lang.'
						AND sw.word LIKE 
						'.($word[0] == '-'
							? ' \''.pSQL(Tools::substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
							: '\''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
						);

	//				var_dump($intersectArray);	

					if ($word[0] != '-')
						$scoreArray[] = 'sw.word LIKE \''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
				}
				else
					unset($words[$key]);

	//		die;		

//			var_dump($words); die;
			
			if (!sizeof($words))
				return ($ajax ? array() : array('total' => 0, 'result' => array()));

			$score = '';
			if (sizeof($scoreArray))
				$score = ',(
					SELECT SUM(weight)
					FROM '._DB_PREFIX_.'search_word sw
					LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = '.(int)$id_lang.'
					AND si.id_product = p.id_product
					AND ('.implode(' OR ', $scoreArray).')
				) position';

			$result = $db->ExecuteS('
			SELECT cp.`id_product`
			FROM `'._DB_PREFIX_.'category_group` cg
			INNER JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cg.`id_category`
			INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
			INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
			WHERE c.`active` = 1 AND p.`active` = 1 AND indexed = 1
			AND cg.`id_group` '.(!$id_customer ?  '= 1' : 'IN (
				SELECT id_group FROM '._DB_PREFIX_.'customer_group
				WHERE id_customer = '.(int)$id_customer.'
			)'), false);

			$eligibleProducts = array();
			while ($row = $db->nextRow($result))
				$eligibleProducts[] = $row['id_product'];
			foreach ($intersectArray as $query)
			{
				$result = $db->ExecuteS($query, false);
				$eligibleProducts2 = array();
				while ($row = $db->nextRow($result))
					$eligibleProducts2[] = $row['id_product'];

				$eligibleProducts = array_intersect($eligibleProducts, $eligibleProducts2);
				if (!count($eligibleProducts))
					return ($ajax ? array() : array('total' => 0, 'result' => array()));
			}
			array_unique($eligibleProducts);

			$productPool = '';
			foreach ($eligibleProducts AS $id_product)
				if ($id_product)
					$productPool .= (int)$id_product.',';
			if (empty($productPool))
				return ($ajax ? array() : array('total' => 0, 'result' => array()));
			$productPool = ((strpos($productPool, ',') === false) ? (' = '.(int)$productPool.' ') : (' IN ('.rtrim($productPool, ',').') '));

			if ($ajax)
			{
				return $db->ExecuteS('
				SELECT DISTINCT p.id_product, pl.name pname, cl.name cname,
					cl.link_rewrite crewrite, pl.link_rewrite prewrite '.$score.'
				FROM '._DB_PREFIX_.'product p
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
				INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$id_lang.')
				WHERE p.`id_product` '.$productPool.'
				ORDER BY position DESC LIMIT 10');
			}
			
			
			
			
			
			

			$queryResults = '
			SELECT p.*, group_concat(a.`id_attribute_group` order by a.`id_attribute_group` ASC separator "") as id_attribute_groups, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`, ';
			
			if($hasColor || $hasPrice)
				$queryResults .= ' if(pai.`id_image`,pai.`id_image`,i.`id_image`) as id_image, '; 
			else
				$queryResults .= ' i.`id_image`, ';
						
			$queryResults .= ' ((IF(sp.`price`,sp.`price`,p.`price`) + IF(pa.`price`,pa.`price`,0)) * IF(sp.`reduction_type` = "percentage",1-sp.`reduction`,1) - IF(sp.`reduction_type` = "amount",sp.`reduction`,0)) AS orderprice, ';
						
 			$queryResults .= ' il.`legend`, m.`name` manufacturer_name '.$score.', DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 new
			FROM '._DB_PREFIX_.'product p
			INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product`
			LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
			LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
			LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
			LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`
			LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON ag.`id_attribute_group` = agl.`id_attribute_group`
			LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (sp.`id_product` = p.`id_product`)';
			
			if($hasColor || $hasPrice)
				$queryResults .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON pa.`id_product_attribute` = pai.`id_product_attribute` ';
			
			$queryResults .= ' LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
			WHERE p.`id_product` '.$productPool.($id_attributes_selected ? ' AND a.`id_attribute` IN ('.$id_attributes_selected.') ' : ' ')
			.($id_manufacturers_selected ? ' AND p.`id_manufacturer` IN ('.$id_manufacturers_selected.') ' : ' ');
			
			
			$groupby = 'id_product';

			if($hasColor || $hasPrice)
				$groupby = 'hasColor';
			if($hasPrice && !$hasColor)
				$groupby = 'hasPrice';


			switch ($groupby) {
				case 'hasColor':
					$queryResults .= ' group by pai.`id_image`';
					break;
				case 'hasPrice':
					$queryResults .= ' group by orderprice';
					break;
				default:
					$queryResults .= ' group by p.`id_product`';
					break;
			}	
			
			if($hasAttribute || $orderprice)
				$queryResults .= ' having ';
			
			if(!$allPrices)
				$queryResults .= ($hasAttribute ? ' (id_attribute_groups regexp "'.$group_pattern.'") = 1 AND ' : ' AND ').( $orderprice ? ' orderprice > '.$orderprice['min'].' AND orderprice < '.$orderprice['max'] : ' 1 ');
			
			
			$queryResults .= ($orderBy ? ' ORDER BY  '.$orderBy : ' ').($orderWay ? ' '.$orderWay : '').'
			LIMIT '.(int)(($pageNumber - 1) * $pageSize).','.(int)$pageSize;



//			echo '<pre>'.$queryResults.'</pre>'; die;


			$result = $db->ExecuteS($queryResults);
			$total = $db->getValue('SELECT COUNT(*)
			FROM '._DB_PREFIX_.'product p
			INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
			                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
		                                           	   AND tr.`id_state` = 0)
		    LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = tr.`id_tax`)
			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
			WHERE p.`id_product` '.$productPool);

			if (!$result)
				$resultProperties = false;
			else
				$resultProperties = Product::getProductsProperties((int)$id_lang, $result);

			return array('total' => $total,'result' => $resultProperties, 'productPool' => $productPool);
		}
	
}