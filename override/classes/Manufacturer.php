<?php

class Manufacturer extends ManufacturerCore{

	static public function getBrands($id_category = 1, $getNbProducts = false, $id_lang = 0, $active = true, $p = false, $n = false, $all_group = false)
	{
		if (!$id_lang)
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$sql = 'SELECT distinct m.id_manufacturer, m.name';
		$sql.= ' FROM `'._DB_PREFIX_.'manufacturer` m
		LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int)($id_lang).') 
		left join `'._DB_PREFIX_.'product` p on (p.`id_manufacturer` = m.`id_manufacturer`)
		left join `'._DB_PREFIX_.'category_product` cp on (p.`id_product` = cp.`id_product`)
		where m.`id_manufacturer` is not null and cp.`id_category` = '.$id_category.' '.($active ? ' and m.`active` = 1' : '');
		
		$sql.= ' ORDER BY m.`name` ASC'.($p ? ' LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n) : '');
		$manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
		if ($manufacturers === false)
			return false;
		if ($getNbProducts)
		{
			$sqlGroups = '';
			if (!$all_group)
			{
				$groups = FrontController::getCurrentCustomerGroups();
				$sqlGroups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
			}
			foreach ($manufacturers as $key => $manufacturer)
			{
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT p.`id_product`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` as m ON (m.`id_manufacturer`= p.`id_manufacturer`)
				WHERE m.`id_manufacturer` = '.(int)($manufacturer['id_manufacturer']).
				($active ? ' AND p.`active` = 1 ' : '').
				($all_group ? '' : ' AND p.`id_product` IN (
					SELECT cp.`id_product`
					FROM `'._DB_PREFIX_.'category_group` cg
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
					WHERE cg.`id_group` '.$sqlGroups.')'));

				$manufacturers[$key]['nb_products'] = sizeof($result);
			}
		}
		for ($i = 0; $i < sizeof($manufacturers); $i++)
			if ((int)(Configuration::get('PS_REWRITING_SETTINGS')))
				$manufacturers[$i]['link_rewrite'] = Tools::link_rewrite($manufacturers[$i]['name'], false);
			else
				$manufacturers[$i]['link_rewrite'] = 0;
		return $manufacturers;
	}

}