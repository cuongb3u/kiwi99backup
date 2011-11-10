<?php
/*
* Override OrderController class
* Author : Truong Kim Phung <truongkimphung1982@yahoo.com >
*/
class OrderConfirmationController extends OrderConfirmationControllerCore
{
	public function setMedia()
	{
		parent::setMedia();	
			Tools::addCSS(_THEME_CSS_DIR_.'order-opc.css');
		// Adding JS files
		Tools::addJS(_THEME_JS_DIR_.'order-opc.js');	
	}
	public function getOrderDetail() {
		$idOrder = (int)(Tools::getValue('id_order'));
		$order = new Order((int)$idOrder);	
		
		if (Validate::isLoadedObject($order) AND $order->id_customer == self::$cookie->id_customer)
		{
			$id_order_state = (int)($order->getCurrentState());
			$carrier = new Carrier((int)($order->id_carrier), (int)($order->id_lang));
			$addressInvoice = new Address((int)($order->id_address_invoice));
			$addressDelivery = new Address((int)($order->id_address_delivery));
		//	$stateInvoiceAddress = new State((int)$addressInvoice->id_state);

			$inv_adr_fields = AddressFormat::getOrderedAddressFields($addressInvoice->id_country);
			$dlv_adr_fields = AddressFormat::getOrderedAddressFields($addressDelivery->id_country);
			
			$invoiceAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressInvoice, $inv_adr_fields);
			$deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressDelivery, $dlv_adr_fields);

			if ($order->total_discounts > 0)
				self::$smarty->assign('total_old', (float)($order->total_paid - $order->total_discounts));
				
			$products 	= $order->getProducts();
				
			$arrProductDetail = array();
			$arrImages	= array();
			$arrProvinces = array();
			
			if(count($products)){			
				foreach($products as $key=>$row) {
					//$arrProductDetail = new Product($row['product_id'], true, self::$cookie->id_lang);
					//$arrImages = $arrProductDetail->getImages((int)(self::$cookie->id_lang));		
					//$products[$key]['arrProductDetail']	= $arrProductDetail;
					$products[$key]['pai_id_image']	= $this->getImageAttr($order->id_cart,$row['product_id'],$row['product_attribute_id']);					
				}				
			}
			//var_dump($products );exit();
			$customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart));
			Product::addCustomizationPrice($products, $customizedDatas);
			$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
							SELECT `provinceid`, `name`
							FROM `'._DB_PREFIX_.'provinces` 							
							ORDER BY `iseq` ASC');
			if(is_array($provinces)) {
				foreach($provinces as $item) {
					$arrProvinces[$item['provinceid']] = $item['name'];
				}
			}
			$customer = new Customer($order->id_customer);			
			self::$smarty->assign(array(				
				'shop_name' => strval(Configuration::get('PS_SHOP_NAME')),
				'order' => $order,
				'return_allowed' => (int)($order->isReturnable()),
				'currency' => new Currency($order->id_currency),
				'order_state' => (int)($id_order_state),
				'invoiceAllowed' => (int)(Configuration::get('PS_INVOICE')),
				'invoice' => (OrderState::invoiceAvailable((int)($id_order_state)) AND $order->invoice_number),
				'order_history' => $order->getHistory((int)(self::$cookie->id_lang), false, true),
				'products' => $products,
				'discounts' => $order->getDiscounts(),
				'carrier' => $carrier,
				'address_invoice' => $addressInvoice,
				'invoiceState' => (Validate::isLoadedObject($addressInvoice) AND $addressInvoice->id_state) ? new State((int)($addressInvoice->id_state)) : false,
				'address_delivery' => $addressDelivery,
				'inv_adr_fields' => $inv_adr_fields,
				'dlv_adr_fields' => $dlv_adr_fields,
				'invoiceAddressFormatedValues' => $invoiceAddressFormatedValues,
				'deliveryAddressFormatedValues' => $deliveryAddressFormatedValues,
				'deliveryState' => (Validate::isLoadedObject($addressDelivery) AND $addressDelivery->id_state) ? new State((int)($addressDelivery->id_state)) : false,
				'is_guest' => false,
				'messages' => Message::getMessagesByOrderId((int)($order->id)),
				'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
				'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
				'use_tax' => Configuration::get('PS_TAX'),
				'group_use_tax' => (Group::getPriceDisplayMethod($customer->id_default_group) == PS_TAX_INC),
				'customizedDatas' => $customizedDatas,
				'arrProvince' => $arrProvinces
			));			
			unset($carrier);
			unset($addressInvoice);
			unset($addressDelivery);
		}
	}
	
	public function displayContent()
	{
		FrontController::displayContent();
		$this->getOrderDetail();		
		self::$smarty->assign('HOOK_MAIN',self::$smarty->fetch(_PS_THEME_DIR_.'order-confirmation.tpl'));
	}
	private function getImageAttr($intCartId, $intProductId,$intProductAttributeId ) {
		$strSql = '	SELECT pai.`id_image`  
					FROM `'._DB_PREFIX_.'cart_product` cp
					LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
					LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product_attribute` = cp.`id_product_attribute`)
					LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai ON (pai.`id_product_attribute` = pa.`id_product_attribute`)
					WHERE cp.`id_cart` = '.(int)$intCartId.'
						'.($intProductId ? ' AND cp.`id_product` = '.(int)$intProductId : '').'
						'.($intProductAttributeId ? ' AND cp.`id_product_attribute` = '.(int)$intProductAttributeId : '').'
						AND p.`id_product` IS NOT NULL					
					ORDER BY cp.date_add ASC';
		$arrResult = Db::getInstance()->getRow($strSql);
		return isset($arrResult['id_image']) ? $arrResult['id_image'] : 0;
	}
}
?>