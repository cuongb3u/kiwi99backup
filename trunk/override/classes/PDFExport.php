<?php
/*
* 2007-2011 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 7614 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

#include_once(_PS_FPDF_PATH_.'fpdf.php');
require_once(_PS_FPDF_PATH_.'Zend/Pdf.php');
require_once(_PS_FPDF_PATH_.'My/Pdf.php');
class PDFExport 
{
	protected static $order = NULL;
	private function getProvinceName($intId) {
		if((int)$intId > 0) {
			$provinces = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
										SELECT `name`
										FROM `'._DB_PREFIX_.'provinces` 
										WHERE `provinceid`=' . $intId . '							
										ORDER BY `iseq` ASC');
			return isset($provinces[0]['name']) ? $provinces[0]['name'] : ''; 
		}
		return $intId;

	}
	private function getDistrictName($intId) {
		if((int)$intId > 0) {
			$districts = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
										SELECT `name`
										FROM `'._DB_PREFIX_.'districts` 
										WHERE `districtid`=' . $intId . '							
										ORDER BY `iseq` ASC');
			return isset($districts[0]['name']) ? $districts[0]['name'] : ''; 
		}
		return $intId;

	}
	public function export($order) {
		global $cookie;		
		if (!Validate::isLoadedObject($order) OR (!$cookie->id_employee AND (!OrderState::invoiceAvailable($order->getCurrentState()) AND !$order->invoice_number)))
			die('Invalid order or invalid order state');
		self::$order = $order;			
		$arrProduct  = self::$order->getProducts();		
		/**data*/
		$arrCurrency 		= Currency::getCurrencyInstance((int)(self::$order->id_currency));
		$strCurrency		= isset($arrCurrency->iso_code) ? strtoupper($arrCurrency->iso_code) : 'VND';
				
		$intOrderId			= (int)self::$order->id;
		$strDateAdd			= self::$order->invoice_date;
		$strPayment			= self::$order->payment;
		// total 
		$intTotalDiscount	= $this->number_format((int)self::$order->total_discounts) . ' ' . $strCurrency;
		$intTotalWrapping	= $this->number_format((int)self::$order->total_wrapping) . ' ' . $strCurrency;
		$intTotalShipping	= $this->number_format((int)self::$order->total_shipping) . ' ' . $strCurrency;
		$intTotalPaid		= $this->number_format((int)self::$order->total_paid) . ' ' . $strCurrency;		
		// giao hang
		$address_delivery 	= new Address((int)self::$order->id_address_delivery);
		$strDFullName		= 'Họ tên: ' . $address_delivery->firstname;
		$strDPhone			= 'Điện thoại: ' . $address_delivery->phone;
		$strDAddr			= 'Địa chỉ: '. $address_delivery->address1;
		$strDDis			= '';
		if($address_delivery->district > 0) {
			$strDDis		= '            ' . $this->getDistrictName($address_delivery->district);
		}
		$strDProv			= '            ' . $this->getProvinceName($address_delivery->city);
		$strDCountry		= '            ' . $address_delivery->country;			
		// thanh toan
		$address_invoice 	= new Address((int)self::$order->id_address_invoice);		
		if($address_invoice->firstname != '')
			$strFullName 	= ' ' . $address_invoice->firstname;
		else
			$strFullName	= ' bạn';
		$strIFullName		= 'Họ tên: ' . $address_invoice->firstname;
		$strIPhone			= 'Điện thoại: ' . $address_invoice->phone;
		$strIAddr			= 'Địa chỉ: '. $address_invoice->address1;
		$strIDis			= '';
		if($address_invoice->district > 0) {
			$strIDis		= '            ' . $this->getDistrictName($address_invoice->district);
		}
		$strIProv			= '            ' . $this->getProvinceName($address_invoice->city);
		$strICountry		= '            ' . $address_invoice->country;	
		
		$arrAddrDelivery 	= array(0 => "$strDFullName", 1=>"$strDPhone", 2=>"$strDAddr", 3=>"$strDDis", 4=>"$strDProv", 5=>"$strDCountry");
		$arrAddrInvoice 	= array(0 => "$strIFullName", 1=>"$strIPhone", 2=>"$strIAddr", 3=>"$strIDis", 4=>"$strIProv", 5=>"$strICountry");
		
		// height
		$intHeight 			= 0;
		$intHeight1 		= 0;
		$intMarginTop		= 0;
		$intCountPage		= 1;		
		/***/
		$pdf 		= new My_Pdf();
		$page1		= new My_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
		$arrPage	= array(); 
		array_push($arrPage,$page1);
		$font 		= Zend_Pdf_Font::fontWithPath(_PS_FPDF_PATH_ . 'tahoma.ttf');
		$page1->setFont($font, 10);
		$page1->setMargins(array(0,50,0,0)); //595:842
		$page1->drawText('Chào' . $strFullName . ',',50,90,'UTF-8');
		$page1->drawText('Cám ơn bạn đã mua hàng tại kiwi99. Chúng tôi gửi thông tin chi tiết đơn hàng của bạn như sau:',50,110,'UTF-8');
		$page1->drawText('Thông tin chi tiết',50,130,'UTF-8');
		$page1->drawText('Mã đơn hàng: #' . $intOrderId,80,150,'UTF-8');
		$page1->drawText('Ngày mua: ' . $strDateAdd,80,170,'UTF-8');
		$page1->drawText('Phương thức thanh toán: ' . $strPayment,80,190,'UTF-8');
		$table	= new My_Pdf_Table(5);
		$result = array(	0 => array(0=>'THAM KHẢO',1=>'TÊN SẢN PHẨM',2=>'ĐƠN VỊ',3=>'SL',4=>'THÀNH TIỀN')	);
		if(is_array($arrProduct)) {		
			//$i = 0;
			//while($i < 10 ) {	
				foreach($arrProduct as $val) {
					$strPrefer 	= $val['product_reference'];
					$arrExplode	= explode(' - ', $val['product_name']);
					if(count($arrExplode) > 1) {
						$strName	= trim($arrExplode[0]);
						$arrExplode2 = explode(',', trim($arrExplode[1]));
						if(count($arrExplode2) > 1 ) {						
							$strName .= "\n" . trim($arrExplode2[0]);
							$strName .= "\n" . trim($arrExplode2[1]);
						}
					}
					else {
						$strName	= $val['product_name'];				
						
					}
					$rodPrice	= $this->number_format(round($val['product_price'],2)) . ' ' . $strCurrency;
					$intQty		= (int)$val['product_quantity'];
					$rodTotalP	= $this->number_format(round($val['total_price'],2)) . ' ' . $strCurrency;
					$arrItem 	= array(0=>"$strPrefer",1=>$strName,2=>$rodPrice,3=>$intQty,4=>$rodTotalP);	
					$result[]	= $arrItem;				
				}
				//$i++;
			//}
			
		}				
		$result1 = array(	
							0=>array(0=>'Giảm giá     ',1=>"$intTotalDiscount"),
							1=>array(0=>'Gói quà       ',1=>"$intTotalWrapping"),
							2=>array(0=>'Phí vận chuyển',1=>"$intTotalShipping"),
							3=>array(0=>'Tổng cộng',1=>"$intTotalPaid"),
						);
		$result2 = array(
							0=>array(0=>'Địa chỉ giao hàng',1=>'Địa chỉ thanh toán')
						);		
		// iterate over record set
		// set up table content
		$intTotalResult = count($result);
		$intBreakPage	= 0;
		$intRecordStart	= 0;
		foreach ($result as $pk=>$record) {			
			$row = new My_Pdf_Table_Row();
			$cols = array();	
			foreach ($record as $k => $v) {
				$col = new My_Pdf_Table_Column();			
				$col->setFont($font, 10);
				$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
				$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
				if($pk == 0) {
					$col->setBorder(My_Pdf::TOP, new Zend_Pdf_Style());
					$col->setBackgroundColor(new Zend_Pdf_Color_Html('#e4e4e4'));
				}
				if($k == 0) {
					$col->setWidth('100');
				}
				elseif ($k==1) {
					$col->setWidth('210');			
				}
				elseif ($k==2) {
					$col->setWidth('100');	
					if($pk == 0) {
						$col->setAlignment(My_Pdf::CENTER);
					}
					else {
						$col->setAlignment(My_Pdf::RIGHT);
					}
				}
				elseif ($k==3) {
					$col->setWidth('30');
					if($pk == 0) {
						$col->setAlignment(My_Pdf::CENTER);
					}
					else {
						$col->setAlignment(My_Pdf::RIGHT);
					}
				}
				elseif($k==4) {
					$col->setWidth('115');
					if($pk == 0) {
						$col->setAlignment(My_Pdf::CENTER);
					}
					else {
						$col->setAlignment(My_Pdf::RIGHT);
					}
					$col->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());
				}		
				$col->setTextLineSpacing(2);			
				$col->setText($v);	
				$intLength = strlen($v);
				if($k == 1) {					
					if($intTotalResult > 0 && $intTotalResult < 4)
						$intLine = 16;
					elseif($intTotalResult > 3)
						$intLine = 15.4;
					if($k == 1) {
						$arrExplode = explode("\n", $v);						
						if(count($arrExplode) > 1) {
							$intLength = strlen($arrExplode[0]);	
							$intHeight += round($intLength/65+0.5,0)*$intLine;	
							$intHeight += ((count($arrExplode)-1) * $intLine);
						}
						else {
							$intHeight += round($intLength/65+0.5,0)*($intLine+5);
						}					 
					}					
				}
				$cols[] = $col; 				     
			}		
			$row->setColumns($cols);		
			$row->setCellPaddings(array(5,5,5,5));	
			$table->addRow($row);			
			if($intHeight > 550) {
				$intRecordStart = $pk;
				break;				
			}
		}			
		$page1->addTable($table,20,210);		
		if($intRecordStart > 0) {
			$intHeight 		 	= 0;
			$intRecordStart2	= 0;
			$intBool 		 	= true;
			$i					= 1;			
			while($intBool) {	
				${"tableExt$i"}	= new My_Pdf_Table(5);															
				foreach ($result as $pk=>$record) {					
					if($pk > $intRecordStart) {	
						$row = new My_Pdf_Table_Row();
						$cols = array();	
						foreach ($record as $k => $v) {						
							$col = new My_Pdf_Table_Column();			
							$col->setFont($font, 10);
							$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
							$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
							if($pk == 0) {
								$col->setBorder(My_Pdf::TOP, new Zend_Pdf_Style());
								$col->setBackgroundColor(new Zend_Pdf_Color_Html('#e4e4e4'));
							}
							if($k == 0) {
								$col->setWidth('100');
							}
							elseif ($k==1) {
								$col->setWidth('210');			
							}
							elseif ($k==2) {
								$col->setWidth('100');	
								if($pk == 0) {
									$col->setAlignment(My_Pdf::CENTER);
								}
								else {
									$col->setAlignment(My_Pdf::RIGHT);
								}
							}
							elseif ($k==3) {
								$col->setWidth('30');
								if($pk == 0) {
									$col->setAlignment(My_Pdf::CENTER);
								}
								else {
									$col->setAlignment(My_Pdf::RIGHT);
								}
							}
							elseif($k==4) {
								$col->setWidth('115');
								if($pk == 0) {
									$col->setAlignment(My_Pdf::CENTER);
								}
								else {
									$col->setAlignment(My_Pdf::RIGHT);
								}
								$col->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());
							}		
							$col->setTextLineSpacing(2);			
							$col->setText($v);	
							$intLength = strlen($v);
							if($k == 1) {					
								if($intTotalResult > 0 && $intTotalResult < 4)
									$intLine = 16;
								elseif($intTotalResult > 3)
									$intLine = 15.4;
								if($k == 1) {
									$arrExplode = explode("\n", $v);						
									if(count($arrExplode) > 1) {
										$intLength = strlen($arrExplode[0]);	
										$intHeight += round($intLength/65+0.5,0)*$intLine;	
										$intHeight += ((count($arrExplode)-1) * $intLine);
									}
									else {
										$intHeight += round($intLength/65+0.5,0)*($intLine+5);
									}					 
								}					
							}							
							$cols[] = $col;      
						}		
						$row->setColumns($cols);		
						$row->setCellPaddings(array(5,5,5,5));	
						${"tableExt$i"}->addRow($row);												
						if($intHeight > 750) {
							$intRecordStart2 = $pk;
							break;							
						}
					}
					
				}	
				if(${"tableExt$i"}->getRows()) {
					$intCountPage++;				
					${"page$intCountPage"}	= new My_Pdf_Page(Zend_Pdf_Page::SIZE_A4);		
					array_push($arrPage,${"page$intCountPage"});
					${"page$intCountPage"}->setMargins(array(0,50,0,0)); //595:842				
					$intMarginTop = 50;
					${"page$intCountPage"}->addTable(${"tableExt$i"}, 20, $intMarginTop);						
					$intCountPage = count($arrPage);
					if($intRecordStart2 > $intRecordStart) {
						$intRecordStart = $intRecordStart2;
						$intRecordStart2 = 0;
						$i++;						
					}
					else {
						$intMarginTop = $intHeight+40;
						break;
					}
				}
			}
		}
		else {
			$intMarginTop = 205 + $intHeight;	
		}
		$table1	= new My_Pdf_Table(2);
		foreach ($result1 as $pk=>$record) {
			$row1 = new My_Pdf_Table_Row();
			$cols1 = array();	
			foreach ($record as $k => $v) {
				$col = new My_Pdf_Table_Column();
				$col->setFont($font, 11);
				$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
				$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
				if($k==0) {		
					$col->setWidth('440');	
					$col->setAlignment(My_Pdf::RIGHT);
					$col->setPadding(My_Pdf::RIGHT,-23);					
				}
				else {
					$col->setWidth('115');
					$col->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());
					$col->setAlignment(My_Pdf::RIGHT);					
				}				
				$col->setText($v);					
				$cols1[] = $col; 
			}
			$row1->setColumns($cols1);	
			$row1->setCellPaddings(array(5,5,5,5));	
			$table1->addRow($row1);
		}
			
		${"page$intCountPage"}->addTable($table1,20,$intMarginTop);
		${"page$intCountPage"}->setFont($font, 10);
		#-------------------------- GIAO HANG & THANH TOAN ----------------------#			
		$intMarginTop += (count($result1) * 20) + 30;
		$intCountPage = count($arrPage);
		if($intMarginTop > 520 && $intCountPage > 1) {
			$intCountPage = count($arrPage)+1;
			${"page$intCountPage"}	= new My_Pdf_Page(Zend_Pdf_Page::SIZE_A4); 
			array_push($arrPage,${"page$intCountPage"});
			${"page$intCountPage"}->setMargins(array(0,50,0,0)); //595:842
			$intMarginTop = 50;
			
		}
		$intCountPage = count($arrPage);
		${"page$intCountPage"}->setFont($font, 10);
		${"page$intCountPage"}->drawText('Địa chỉ thanh toán và nhận hàng',50,$intMarginTop,'UTF-8');	
		$table2	= new My_Pdf_Table(2);
		foreach ($result2 as $pk=>$record) {
			$row2 = new My_Pdf_Table_Row();
			$cols2 = array();	
			foreach ($record as $k => $v) {
				$col = new My_Pdf_Table_Column();
				$col->setFont($font, 11);
				$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
				$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
				$col->setBorder(My_Pdf::TOP, new Zend_Pdf_Style());
				$col->setBackgroundColor(new Zend_Pdf_Color_Html('#e4e4e4'));
				$col->setWidth('240');
				if($k>0) {				
					$col->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());			
				}		
				$col->setText($v);					
				$cols2[] = $col; 
			}
			$row2->setColumns($cols2);	
			$row2->setCellPaddings(array(5,5,5,5));	
			$table2->addRow($row2);
		}
		$intMarginTop += 10;
		${"page$intCountPage"}->addTable($table2,50,$intMarginTop);
		// dia chi giao hang
		$table3	= new My_Pdf_Table(1);
		foreach ($arrAddrDelivery as $pk=>$record) {
			if($record != '') {						
				$row3 	= new My_Pdf_Table_Row();
				$cols3 	= array();	
				$col 	= new My_Pdf_Table_Column();
				$col->setFont($font, 11);
				$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
				if(count($arrAddrInvoice) - $pk == 1) {
					$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
				}
				$col->setWidth('240');	
				$col->setText($record);			
				$cols3[] = $col; 
			
				$intLength1 = strlen($record);
				$intHeight1 += round(($intLength1/50) + 0.5,0) * 22;
				$row3->setColumns($cols3);	
				$row3->setCellPaddings(array(5,5,5,5));	
				$table3->addRow($row3);	
			}
			
		}
		$intMarginTop += 20;
		${"page$intCountPage"}->addTable($table3,50,$intMarginTop);
		// data dia chi thanh toan
		$table4	= new My_Pdf_Table(1);
		foreach ($arrAddrInvoice as $pk=>$record) {	
			if($record !='') {
				$row4 	= new My_Pdf_Table_Row();
				$cols4 	= array();	
				$col 	= new My_Pdf_Table_Column();
				$col->setFont($font, 11);
				$col->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
				$col->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());
				if(count($arrAddrInvoice) - $pk == 1) {
					$col->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
				}
				$col->setWidth('240');	
				$col->setText($record);				
				$cols4[] = $col; 
				$row4->setColumns($cols4);	
				$row4->setCellPaddings(array(5,5,5,5));	
				$table4->addRow($row4);	
			}
		}			
		${"page$intCountPage"}->addTable($table4,290,$intMarginTop);			
		$intMarginTop += $intHeight1 + 20;
		${"page$intCountPage"}->setFont($font, 11);
		${"page$intCountPage"}->drawText('* Nếu có thắc mắc cần giải đáp, xin liên hệ với chúng tôi.',50,$intMarginTop,'UTF-8');
		for($i=1;$i<=$intCountPage;$i++) {
			if($i == 1) {
				$imageLogo1	= Zend_Pdf_Image::imageWithPath(_PS_FPDF_PATH_ . 'logo.png');
				${"page$i"}->drawImage($imageLogo1, 20, 786, 190, 822);
			}
			if($i == $intCountPage) {	
				${"page$i"}->drawLine(50,720,545,720);//$intMarginTop+20
				${"page$i"}->setFont($font, 10);
				${"page$i"}->drawText('Công ty kiwi99.com',50,740,'UTF-8');//$intMarginTop+40
				${"page$i"}->drawText('78 Nguyễn Khoái, Phường 2, Quận 4, Tp Hồ Chí Minh',50,760,'UTF-8');//$intMarginTop+60
				${"page$i"}->drawText('Điện thoại: (08) 3945 1900 - (08) 3945 1901',50,780,'UTF-8');//$intMarginTop+80
				${"page$i"}->drawText('Website: kiwi99.com',430,780,'UTF-8');//$intMarginTop+80
				${"page$i"}->drawText('Fax: (08) 3943 0080',50,800,'UTF-8');//$intMarginTop+100
				${"page$i"}->drawText('Email: info@kiwi99.com',430,800,'UTF-8'); #$intMarginTop+100
			}
			$pdf->pages[] 	= ${"page$i"};
		}
		$pdfData 		= $pdf->render();
		$strFileName	= "$intOrderId.pdf";
		$pdf->save(_PS_FPDF_PATH_ . $strFileName);		
		header("Content-Disposition: inline; filename=" . $strFileName);		
		header("Content-type: application/x-pdf");
		echo $pdfData;
		exit();
	}
	function number_format($number, $decimals="0", $decpoint=",", $thousandsep=".") {
		return number_format($number, $decimals, $decpoint, $thousandsep);
	}

}

