<?php

class Link extends LinkCore{

	public function getPaginationLink($type, $id_object, $nb = false, $sort = false, $pagination = false, $array = false)
	{
		if ($type AND $id_object)
			$url = $this->{'get'.$type.'Link'}($id_object, NULL);
		else
		{
			$url = $this->url;
			if (Configuration::get('PS_REWRITING_SETTINGS'))
				$url = $this->getPageLink(basename($url));
		}
		$vars = (!$array ? '' : array());
		$varsNb = array('n', 'search_query');
		$varsSort = array('orderby', 'orderway');
		$varsPagination = array('p');
		
		$ajaxFilter = Tools::getValue('filter');

		$n = 0;
		foreach ($_GET AS $k => $value)
			if ($k != 'id_'.$type)
			{
				if (Configuration::get('PS_REWRITING_SETTINGS') AND ($k == 'isolang' OR $k == 'id_lang'))
					continue;
				$ifNb = (!$nb OR ($nb AND !in_array($k, $varsNb)));
				$ifSort = (!$sort OR ($sort AND !in_array($k, $varsSort)));
				$ifPagination = (!$pagination OR ($pagination AND !in_array($k, $varsPagination)));
				if ($ifNb AND $ifSort AND $ifPagination AND !is_array($value))
					!$array ? ($vars .= ((!$n++ AND ($this->allow == 1 OR $url == $this->url)) ? '?' : '&').urlencode($k).'='.urlencode($value)) : ($vars[urlencode($k)] = urlencode($value));
			}
			
		if (!$array){
			if(!$ajaxFilter)
				return $url.$vars;
			else
				return $vars;	
		}	
			
		$vars['requestUrl'] = $url;
		if ($type AND $id_object)
			$vars['id_'.$type] = (is_object($id_object) ? (int)$id_object->id : (int)$id_object);
		return $vars;
	}
		public function getProductLink($id_product, $alias = NULL, $category = NULL, $ean13 = NULL, $id_lang = NULL)
	{
		
		$shop	=	0;
		$manufacturer	=	explode('_',_MANUFACTURER_);
		if (is_object($id_product))
			{
				if (isset($id_product->id_manufacturer)) {
					$idmanufacturer	=	$id_product->id_manufacturer;
					for ($shop; $shop < count($manufacturer) ; $shop++) { 
						if ($idmanufacturer	==	$manufacturer[$shop]) {
							break;
						}
					};					
				}	
				if ($shop	==	0) {
					$shop	=	1;
				}
				if (!$shop) 
				return ($this->allow == 1)?(_PS_BASE_URL_.__PS_BASE_URI__.$this->getLangLink((int)$id_lang).((isset($id_product->category) AND !empty($id_product->category) AND $id_product->category != 'home') ? $id_product->category.'/' : '').(int)$id_product->id.'-'.$id_product->link_rewrite.($id_product->ean13 ? '-'.$id_product->ean13 : '').'.html') :
			(_PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product->id);
				else
				return ($this->allow == 1)?(_PS_BASE_URL_.__PS_BASE_URI__.$this->getLangLink((int)$id_lang).((isset($id_product->category) AND !empty($id_product->category) AND $id_product->category != 'home') ? $id_product->category.'/' : '').(int)$id_product->id.'-'.$id_product->link_rewrite.($id_product->ean13 ? '-'.$id_product->ean13 : '').'.html?shop='.(int)$shop) :
			(_PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product->id).'&shop='.(int)$shop;
		}
		elseif ($alias)
			{
				$product = new Product($id_product, true);
				$idmanufacturer	=	$product->id_manufacturer;
					for ($shop; $shop < count($manufacturer) ; $shop++) { 
						if ($idmanufacturer	==	$manufacturer[$shop]) {
							break;
						}
					};	
				
					if ($shop	==	0) {
					$shop	=	1;
				}
				if (!$shop) 
				{
			return ($this->allow == 1)?(_PS_BASE_URL_.__PS_BASE_URI__.$this->getLangLink((int)$id_lang).(($category AND $category != 'home') ? ($category.'/') : '').(int)$id_product.'-'.$alias.($ean13 ? '-'.$ean13 : '').'.html') :
			(_PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product);
			}
			else{
				return ($this->allow == 1)?(_PS_BASE_URL_.__PS_BASE_URI__.$this->getLangLink((int)$id_lang).(($category AND $category != 'home') ? ($category.'/') : '').(int)$id_product.'-'.$alias.($ean13 ? '-'.$ean13 : '').'.html?shop='.(int)$shop) :
			(_PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product).'&shop='.(int)$shop;
			}
		}
		else
			{
				$shop=Tools::getValue('shop');
				if (!$shop) 
					return _PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product;
				else
					return _PS_BASE_URL_.__PS_BASE_URI__.'product.php?id_product='.(int)$id_product.'&shop='.(int)$shop;
				
			}
	}

}