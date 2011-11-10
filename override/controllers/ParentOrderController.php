<?php
class ParentOrderController extends ParentOrderControllerCore
{
	
	public function preProcess()
		{
			global $isVirtualCart;

			parent::preProcess();

			// Redirect to the good order process
			if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 0 AND strpos($_SERVER['PHP_SELF'], 'order.php') === false)
				Tools::redirect('order.php');
			if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1 AND strpos($_SERVER['PHP_SELF'], 'order-opc.php') === false)
			{
				if (isset($_GET['step']) AND $_GET['step'] == 3)
					Tools::redirect('order-opc.php?isPaymentStep=true');
				Tools::redirect('order-opc.php?step=' . $_GET['step']); // VINH EDIT
			}

			if (Configuration::get('PS_CATALOG_MODE'))
				$this->errors[] = Tools::displayError('This store has not accepted your new order.');

			if (Tools::isSubmit('submitReorder') AND $id_order = (int)Tools::getValue('id_order'))
			{
				$oldCart = new Cart(Order::getCartIdStatic((int)$id_order, (int)self::$cookie->id_customer));
				$duplication = $oldCart->duplicate();
				if (!$duplication OR !Validate::isLoadedObject($duplication['cart']))
					$this->errors[] = Tools::displayError('Sorry, we cannot renew your order.');
				elseif (!$duplication['success'])
					$this->errors[] = Tools::displayError('Missing items - we are unable to renew your order');
				else
				{
					self::$cookie->id_cart = $duplication['cart']->id;
					self::$cookie->write();
					if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1)
						Tools::redirect('order-opc.php');
					Tools::redirect('order.php');
				}
			}

			if ($this->nbProducts)
			{

				if (Tools::getValue('ajaxOn') AND Tools::getValue('discount_name'))
				{
				  				  
					$discountName = Tools::getValue('discount_name');
					if (!Validate::isDiscountName($discountName))
						$this->errors[] = Tools::displayError('Voucher name invalid.');
					else
					{
						$discount = new Discount((int)(Discount::getIdByName($discountName)));
						if (Validate::isLoadedObject($discount))
						{
							if ($tmpError = self::$cart->checkDiscountValidity($discount, self::$cart->getDiscounts(), self::$cart->getOrderTotal(), self::$cart->getProducts(), true))
								$this->errors[] = $tmpError;
						}
						else
							$this->errors[] = Tools::displayError('Voucher name invalid.');
						if (!sizeof($this->errors))
						{


							self::$cart->addDiscount((int)($discount->id));


							  
//							Tools::redirect('order-opc.php');
						}
					}

      		
							
										
					self::$smarty->assign(array(
						'errors' => $this->errors,
						'discount_name' => Tools::safeOutput($discountName),
						'discounts' => self::$cart->getDiscounts(false, true),
//      			'carrier' => new Carrier((int)(self::$cart->id_carrier), $cookie->id_lang),
      			'products' => self::$cart->getProducts(false),
      			'discounts' => self::$cart->getDiscounts(false, true),
      			'is_virtual_cart' => (int)self::$cart->isVirtualCart(),
      			'total_discounts' => self::$cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS),
      			'total_discounts_tax_exc' => self::$cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS),
      			'total_wrapping' => self::$cart->getOrderTotal(true, Cart::ONLY_WRAPPING),
      			'total_wrapping_tax_exc' => self::$cart->getOrderTotal(false, Cart::ONLY_WRAPPING),
      			'total_shipping' => self::$cart->getOrderShippingCost(),
      			'total_shipping_tax_exc' => self::$cart->getOrderShippingCost(NULL, false),
      			'total_products_wt' => self::$cart->getOrderTotal(true, Cart::ONLY_PRODUCTS),
      			'total_products' => self::$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS),
      			'total_price' => self::$cart->getOrderTotal(),
//      			'total_tax' => $total_tax,
      			'total_price_without_tax' => self::$cart->getOrderTotal(false)
//      			'free_ship' => $total_free_ship
						
					));
					
//					$pagination  = self::$smarty->fetch(_PS_THEME_DIR_.'pagination.tpl');					


$shoppingCart = self::$smarty->fetch(_PS_THEME_DIR_.'enteredVouchers.tpl');

$this->_assignSummaryInformations();

$this->_assignWrappingAndTOS();


$shippingClass = self::$smarty->fetch(_PS_THEME_DIR_.'shipping.tpl');					
echo json_encode(array(
	'errors' => $this->errors,
	'discount_name' => Tools::safeOutput($discountName),
	'shopping_cart' => $shoppingCart,
	'shippingClass' => $shippingClass
));
					
					die;
				  
				}
				elseif (isset($_GET['deleteDiscount']) AND Validate::isUnsignedId($_GET['deleteDiscount']))
				{
					self::$cart->deleteDiscount((int)($_GET['deleteDiscount']));
					
					Tools::redirect('order-opc.php');
				}			
				/* Is there only virtual product in cart */
				if ($isVirtualCart = self::$cart->isVirtualCart())
					$this->_setNoCarrier();
			}				
			self::$smarty->assign('back', Tools::safeOutput(Tools::getValue('back')));	

		}
}

