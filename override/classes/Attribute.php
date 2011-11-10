<?php

class Attribute extends AttributeCore{
  
    static public function isReset($params){
      return (empty($params['id_attribute'])  && empty($params['prices']) && empty($params['product_pool']));
    }

		static public function getAttributesByCategory($id_lang, $id_cat){
		
			$sql = 'select distinct a.`id_attribute_group`, (concat_ws("|",a.`id_attribute`,al.`name`)) as id_name 
					FROM `'._DB_PREFIX_.'category_product` cp 
					LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON cp.`id_product` = pa.`id_product` 
					LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute` 
					LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute` 
					LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).') 
					LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group` 
					LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)($id_lang).')
					WHERE cp.`id_category` = '.$id_cat.' and a.`id_attribute` is not null order by a.`id_attribute_group`';
					
//					die($sql);
					
			return Db::getInstance()->ExecuteS($sql);
		
		}
		
    static public function build_product_pool($products){
      
      if(isset($products) && is_array($products) && count($products)){
      
        $product_pool = ' IN (';
        foreach ($products as $product) {
          $product_pool .= $product['id_product'];
          $product_pool .= ',';
        }
        $product_pool = substr($product_pool,0,-1);
        $product_pool .= ') ';
      
        return $product_pool;
      }else{
        return '';
      }
      
    }

				static public function getAttributesAfterSearch($id_lang, $product_pool){

					$sql = 'select distinct a.`id_attribute_group`, (concat_ws("|",a.`id_attribute`,al.`name`)) as id_name 
							FROM `'._DB_PREFIX_.'product` p 
							LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product` 
							LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute` 							
							LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute` 
							LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).') 
							LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group` 
							LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)($id_lang).')
							WHERE p.`id_product` '.$product_pool.' and a.`id_attribute` is not null order by a.`id_attribute_group`';

//							die($sql);

					return Db::getInstance()->ExecuteS($sql);

				}


		static public function getColorsByCategory($id_lang, $id_cat){
		
			$sql = 'select distinct a.`id_attribute_group`, (concat_ws("|",a.`id_attribute`,al.`name`)) as color_info 
					FROM `'._DB_PREFIX_.'category_product` cp 
					LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON cp.`id_product` = pa.`id_product` 
					LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute` 
					LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute` 
					LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).')					
					LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group` 
					WHERE cp.`id_category` = '.$id_cat.' and a.`id_attribute_group` = 2 and a.`id_attribute` is not null order by a.`id_attribute_group`';
					
//					die($sql);
					
			return Db::getInstance()->ExecuteS($sql);
		
		}


				static public function getColorsAfterSearch($id_lang, $product_pool){

					$sql = 'select distinct a.`id_attribute_group`, (concat_ws("|",a.`id_attribute`,al.`name`)) as color_info 
							FROM `'._DB_PREFIX_.'product` p 
							LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON p.`id_product` = pa.`id_product` 
							LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute` 
							LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute` 
							LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).')					
							LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group` 
							WHERE p.`id_product` '.$product_pool.' and a.`id_attribute_group` = 2 and a.`id_attribute` is not null order by a.`id_attribute_group`';

              // die($sql);

					return Db::getInstance()->ExecuteS($sql);

				}


		
		static public function getAttributes_id_pa($ipas){
		
			$sql = 'SELECT distinct id_attribute FROM `'._DB_PREFIX_.'product_attribute_combination` pac
where pac.`id_product_attribute` in ('.$ipas.')';

						return Db::getInstance()->ExecuteS($sql);		
		}

		
		static public function getGroupPattern($id_attributes){
			$sql = 'SELECT distinct id_attribute_group FROM `'._DB_PREFIX_.'attribute` where id_attribute in ('.$id_attributes.') order by id_attribute_group asc';
						return Db::getInstance()->ExecuteS($sql);		
		}
}