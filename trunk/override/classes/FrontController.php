<?php 
class FrontController extends FrontControllerCore
{

  public function preProcess()
	  {			if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
    	if (self::$cookie->isLogged())
  		{
  			self::$smarty->assign('isLogged', 1);
  			$customer = new Customer((int)(self::$cookie->id_customer));
  			if (!Validate::isLoadedObject($customer))
  				die(Tools::displayError('Customer not found'));
  			$products = array();
  			$orders = array();
  			$products_order = array();
  			$getOrders = Db::getInstance()->ExecuteS('
  				SELECT id_order
  				FROM '._DB_PREFIX_.'orders
  				WHERE id_customer = '.(int)$customer->id.' ORDER BY date_add');
  				$count	=	0;
  			foreach ($getOrders as $row)
  			{
  				$order = new Order($row['id_order']);
  				$date = explode(' ', $order->date_add);
  				$orders[$row['id_order']] = Tools::displayDate($date[0], self::$cookie->id_lang);
  				$tmp = $order->getProducts();
  				
  				foreach ($tmp as $key => $val )
  					{
  						$name = array();
  						$attrs = array();
  						$name = explode('-', $val['product_name']);
  						if (isset($name[1])) {
  							$attrs = explode(',', $name[1]);
  						}
  						//$attrs = explode(',', $name[1]);
  						$products_order[$count] = $val;
  						$products_order[$count]['name'] = $name[0];
  						
  						$products_order[$count]['attrs'] = $attrs;
  						$count++;
  						$products[$val['product_id']] = $val['product_name'];
  					}
  			}
  			self::$smarty->assign('products_order', $products_order);
  			
  			$orderList = '';
  			foreach ($orders as $key => $val)
  				$orderList .= '<option value="'.$key.'" '.((int)(Tools::getValue('id_order')) == $key ? 'selected' : '').' >'.$key.' -- '.$val.'</option>';
  			$orderedProductList = '';
			
  			foreach ($products as $key => $val)
  				$orderedProductList .= '<option value="'.$key.'" '.((int)(Tools::getValue('id_product')) == $key ? 'selected' : '').' >'.$val.'</option>';
  			self::$smarty->assign('orderList', $orderList);
  			self::$smarty->assign('orderedProductList', $orderedProductList);
  		}

  		if (Tools::isSubmit('submitMessage'))
  		{
  			
  			$fileAttachment = NULL;
  			if (isset($_FILES['fileUpload']['name']) AND !empty($_FILES['fileUpload']['name']) AND !empty($_FILES['fileUpload']['tmp_name']))
  			{
  				$extension = array('.txt', '.rtf', '.doc', '.docx', '.pdf', '.zip', '.png', '.jpeg', '.gif', '.jpg');
  				$filename = uniqid().substr($_FILES['fileUpload']['name'], -5);
  				$fileAttachment['content'] = file_get_contents($_FILES['fileUpload']['tmp_name']);
  				$fileAttachment['name'] = $_FILES['fileUpload']['name'];
  				$fileAttachment['mime'] = $_FILES['fileUpload']['type'];
  			}
  			//$message = Tools::htmlentitiesUTF8(Tools::getValue('message'));
  			$message =	(Tools::getValue('subject'));
  		//	$message .= (Tools::getValue('message'));
  			
  			if (!($from = trim(Tools::getValue('from'))) OR !Validate::isEmail($from))
  				$this->errors[] = Tools::displayError('Invalid e-mail address');
  			elseif (!($message = nl2br2($message)))
  				$this->errors[] = Tools::displayError('Message cannot be blank');
  			elseif (!Validate::isCleanHtml($message))
  				$this->errors[] = Tools::displayError('Invalid message');
  			elseif (!($id_contact = (int)(Tools::getValue('id_contact'))) OR !(Validate::isLoadedObject($contact = new Contact((int)($id_contact), (int)(self::$cookie->id_lang)))))
  				$this->errors[] = Tools::displayError('Please select a subject on the list.');
  			elseif (!empty($_FILES['fileUpload']['name']) AND $_FILES['fileUpload']['error'] != 0)
  				$this->errors[] = Tools::displayError('An error occurred during the file upload');
  			elseif (!empty($_FILES['fileUpload']['name']) AND !in_array(substr($_FILES['fileUpload']['name'], -4), $extension) AND !in_array(substr($_FILES['fileUpload']['name'], -5), $extension))
  				$this->errors[] = Tools::displayError('Bad file extension');
  			else
  			{
  				if ((int)(self::$cookie->id_customer))
  					$customer = new Customer((int)(self::$cookie->id_customer));
  				else
  				{
  					$customer = new Customer();
  					$customer->getByEmail($from);
  				}

  				$contact = new Contact($id_contact, self::$cookie->id_lang);

  				if (!((
  						$id_customer_thread = (int)Tools::getValue('id_customer_thread')
  						AND (int)Db::getInstance()->getValue('
  						SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
  						WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' AND token = \''.pSQL(Tools::getValue('token')).'\'')
  					) OR (
  						$id_customer_thread = (int)Db::getInstance()->getValue('
  						SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
  						WHERE cm.email = \''.pSQL($from).'\' AND cm.id_order = '.(int)(Tools::getValue('id_order')).'')
  					)))
  				{
  					$fields = Db::getInstance()->ExecuteS('
  					SELECT cm.id_customer_thread, cm.id_contact, cm.id_customer, cm.id_order, cm.id_product, cm.email
  					FROM '._DB_PREFIX_.'customer_thread cm
  					WHERE email = \''.pSQL($from).'\' AND ('.
  						($customer->id ? 'id_customer = '.(int)($customer->id).' OR ' : '').'
  						id_order = '.(int)(Tools::getValue('id_order')).')');
  					$score = 0;
  					foreach ($fields as $key => $row)
  					{
  						$tmp = 0;
  						if ((int)$row['id_customer'] AND $row['id_customer'] != $customer->id AND $row['email'] != $from)
  							continue;
  						if ($row['id_order'] != 0 AND Tools::getValue('id_order') != $row['id_order'])
  							continue;
  						if ($row['email'] == $from)
  							$tmp += 4;
  						if ($row['id_contact'] == $id_contact)
  							$tmp++;
  						if (Tools::getValue('id_product') != 0 AND $row['id_product'] ==  Tools::getValue('id_product'))
  							$tmp += 2;
  						if ($tmp >= 5 AND $tmp >= $score)
  						{
  							$score = $tmp;
  							$id_customer_thread = $row['id_customer_thread'];
  						}
  					}
  				}
  				$old_message = Db::getInstance()->getValue('
  					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
  					WHERE cm.id_customer_thread = '.(int)($id_customer_thread).'
  					ORDER BY date_add DESC');
  				if ($old_message == htmlentities($message, ENT_COMPAT, 'UTF-8'))
  				{
  					self::$smarty->assign('alreadySent', 1);
  					$contact->email = '';
  					$contact->customer_service = 0;
  				}
  				if (!empty($contact->email))
  				{
  					if (Mail::Send((int)(self::$cookie->id_lang), 'contact', Mail::l('Message from contact form'), array('{email}' => $from, '{message}' => stripslashes($message)), $contact->email, $contact->name, $from, ((int)(self::$cookie->id_customer) ? $customer->firstname.' '.$customer->lastname : ''), $fileAttachment)
  						AND Mail::Send((int)(self::$cookie->id_lang), 'contact_form', Mail::l('Your message has been correctly sent'), array('{message}' => stripslashes($message)), $from))
  						self::$smarty->assign('confirmation', 1);
  					else
  						$this->errors[] = Tools::displayError('An error occurred while sending message.');
  				}

  				if ($contact->customer_service)
  				{
  					if ((int)$id_customer_thread)
  					{
  						$ct = new CustomerThread($id_customer_thread);
  						$ct->status = 'open';
  						$ct->id_lang = (int)self::$cookie->id_lang;
  						$ct->id_contact = (int)($id_contact);
  						if ($id_order = (int)Tools::getValue('id_order'))
  							$ct->id_order = $id_order;
  						if ($id_product = (int)Tools::getValue('id_product'))
  							$ct->id_product = $id_product;
  						$ct->update();
  					}
  					else
  					{
  						$ct = new CustomerThread();
  						if (isset($customer->id))
  							$ct->id_customer = (int)($customer->id);
  						if ($id_order = (int)Tools::getValue('id_order'))
  							$ct->id_order = $id_order;
  						if ($id_product = (int)Tools::getValue('id_product'))
  							$ct->id_product = $id_product;
  						$ct->id_contact = (int)($id_contact);
  						$ct->id_lang = (int)self::$cookie->id_lang;
  						$ct->email = $from;
  						$ct->status = 'open';
  						$ct->token = Tools::passwdGen(12);
  						$ct->add();
  					}

  					if ($ct->id)
  					{
  						$cm = new CustomerMessage();
  						$cm->id_customer_thread = $ct->id;
  						$cm->message = htmlentities($message, ENT_COMPAT, 'UTF-8');
  						if (isset($filename) AND rename($_FILES['fileUpload']['tmp_name'], _PS_MODULE_DIR_.'../upload/'.$filename))
  							$cm->file_name = $filename;
  						$cm->ip_address = ip2long($_SERVER['REMOTE_ADDR']);
  						$cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
  						if ($cm->add())
  						{
  							if (empty($contact->email))
  								Mail::Send((int)(self::$cookie->id_lang), 'contact_form', Mail::l('Your message has been correctly sent'), array('{message}' => stripslashes($message)), $from);
  							self::$smarty->assign('confirmation', 1);
  								
  						}
  						else
  							$this->errors[] = Tools::displayError('An error occurred while sending message.');
  					}
  					else
  						$this->errors[] = Tools::displayError('An error occurred while sending message.');
  				}
  				if (count($this->errors) > 1)
  					{array_unique($this->errors);
  						echo 0;
  						die;
  						}
  				else
	  				{echo 1;die;}
  			}
  		}
    
      	$email = Tools::safeOutput(Tools::getValue('from', ((isset(self::$cookie) AND isset(self::$cookie->email) AND Validate::isEmail(self::$cookie->email)) ? self::$cookie->email : '')));
    		self::$smarty->assign(array(
    			'errors' => $this->errors,
    			'email' => $email,
    			'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD')
    		));


    		if ($id_customer_thread = (int)Tools::getValue('id_customer_thread') AND $token = Tools::getValue('token'))
    		{
    			$customerThread = Db::getInstance()->getRow('
    			SELECT cm.* FROM '._DB_PREFIX_.'customer_thread cm
    			WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' AND token = \''.pSQL($token).'\'');
    			self::$smarty->assign('customerThread', $customerThread);
    		}else{
    		  self::$smarty->assign('customerThread', 0);
    		}

    		self::$smarty->assign(array('contacts' => Contact::getContacts((int)(self::$cookie->id_lang)),
    		'message' => html_entity_decode(Tools::getValue('message'))
    		));
    
  }
  


  
 	public function process() 
  {
   	 if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
    	$email = Tools::safeOutput(Tools::getValue('from', ((isset(self::$cookie) AND isset(self::$cookie->email) AND Validate::isEmail(self::$cookie->email)) ? self::$cookie->email : '')));
  		self::$smarty->assign(array(
  			'errors' => $this->errors,
  			'email' => $email,
  			'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD')
  		));


  		if ($id_customer_thread = (int)Tools::getValue('id_customer_thread') AND $token = Tools::getValue('token'))
  		{
  			$customerThread = Db::getInstance()->getRow('
  			SELECT cm.* FROM '._DB_PREFIX_.'customer_thread cm
  			WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' AND token = \''.pSQL($token).'\'');
  			self::$smarty->assign('customerThread', $customerThread);
  		}

  		self::$smarty->assign(array('contacts' => Contact::getContacts((int)(self::$cookie->id_lang)),
  		'message' => html_entity_decode(Tools::getValue('message'))
  		));
  	
	  	
	  	
  	
  		
  		  			$update_img=explode('_',_UPDATE_IMG_);
		  		$status_img=$update_img[$shop];
		   if ($shop) {
					$full_img_path=_THEME_DIR_."images/campaign/shop$shop/"; 
	  			}
	  			else
		  			{
		  			$full_img_path=_THEME_DIR_."images/campaign/"; 
		  			}
		  		//echo $dir_date;die;
		  		if (Tools::getValue('date')&&Tools::getValue('img')) {
	  				$dir_date=Tools::getValue('date');
	  				$full_img_path .="$dir_date/"; 
	  				//echo 	$full_css_path;die;
	  			}
	  			else{			  			
				  		if ($status_img!=0) {
				  		//	echo 1;die;	
				  			$dir_date = date("Y-m-d");
				  			$full_img_path =_THEME_DIR_."images/campaign/shop$shop/$dir_date/"; 
  							$check=0;
  							while(!is_dir($_SERVER{'DOCUMENT_ROOT'}.$full_img_path)) {	
  								//echo $_SERVER{'DOCUMENT_ROOT'}.$full_tpl_path;die;
  		  		  			 $dir_date = date_create($dir_date);
  		  		  			  date_sub($dir_date, date_interval_create_from_date_string('1 days'));
  		  		  		 			$dir_date	=date_format($dir_date, 'Y-m-d');
  		  		  		 			 if ($shop) {
											$full_img_path =_THEME_DIR_."images/campaign/shop$shop/$dir_date/"; 
	  									}
			  						else
				  						{
				  						$full_img_path =_THEME_DIR_."images/campaign/$dir_date/"; 
				  						}
  		  		  			//	$full_img_path =_THEME_DIR_."images/campaign/shop$shop/$dir_date/"; 
  		  		  				$check++;
  		  		  				if ($check	>	_UPDATE_CHECK_) {
  		  		  				//	echo 1;die;
  		  		  			 		 if ($shop) {
											$full_img_path=_THEME_DIR_."images/campaign/shop$shop/"; 
	  									}
			  						else
				  						{
				  						$full_img_path=_THEME_DIR_."images/campaign/"; 
				  						}
  		  		  			 		break;
  		  		  			 	} 	 	
  		  		  			//echo $full_css_path;die;
  		  		  			//echo 1;	
  		  				}
						
		//echo $full_css_path;die;
					
					
				}
		//echo $full_path;die;
	
	}
		self::$smarty->assign('dir_campaign', $full_img_path);
  }
  
	public function setMedia()
	{
		global $cookie;
	//	$shop=Tools::getValue('shop');
	//echo 1;die;
				
	  		
	  		if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
		  		
		  		$update_css=explode('_',_UPDATE_CSS_);
		  		$status_css=$update_css[$shop];
		   if ($shop) {
					$full_css_path=_THEME_CSS_DIR_."shop$shop/";
	  			}
	  			else
		  			{
		  			$full_css_path =_THEME_CSS_DIR_; 
		  			}
		  		//echo $dir_date;die;
		  		if (Tools::getValue('date')&&Tools::getValue('css')) {
	  				$dir_date=Tools::getValue('date');
	  				$full_css_path .="$dir_date/"; 
	  				//echo 	$full_css_path;die;
	  			}
	  			else{			  			
				  		if ($status_css!=0) {
				  		//	echo 1;die;	
				  			$dir_date = date("Y-m-d");
				  			$full_css_path =_THEME_CSS_DIR_."shop$shop/$dir_date/"; 
  							$check=0;
  							while(!is_dir($_SERVER{'DOCUMENT_ROOT'}.$full_css_path)) {	
  								//echo $_SERVER{'DOCUMENT_ROOT'}.$full_tpl_path;die;
  		  		  			 $dir_date = date_create($dir_date);
  		  		  			  date_sub($dir_date, date_interval_create_from_date_string('1 days'));
  		  		  		 			$dir_date	=date_format($dir_date, 'Y-m-d');
  		  		  				if ($shop) {
											$full_css_path =_THEME_CSS_DIR_."shop$shop/$dir_date/"; 
							  			}
							  			else
								  			{
								  			$full_css_path =_THEME_CSS_DIR_."$dir_date/"; 
								  			}
  		  		  				$check++;
  		  		  				if ($check	>	_UPDATE_CHECK_) {
  		  		  			 		if ($shop) {
											$full_css_path=_THEME_CSS_DIR_."shop$shop/";
							  			}
							  			else
								  			{
								  			$full_css_path =_THEME_CSS_DIR_; 
								  			}
  		  		  			 		break;
  		  		  			 	} 	 	
  		  		  			//echo $full_css_path;die;
  		  		  			//echo 1;	
  		  				}
						
		//echo $full_css_path;die;
					}
					
				}
			// update JS
			$update_js=explode('_',_UPDATE_JS_);
		  		$status_js=$update_js[$shop];
		  		if ($shop) {
		  		$full_js_path=_THEME_JS_DIR_."shop$shop/";
	  			}
	  			else
		  			{
		  			$full_js_path =_THEME_JS_DIR_; 
		  			}
		  		//echo $dir_date;die;
		  		if (Tools::getValue('date')&&Tools::getValue('js')) {
	  				$dir_date=Tools::getValue('date');
	  				$full_js_path .="$dir_date/"; 
	  				//echo 	$full_css_path;die;
	  			}
	  			else{			  			
				  		if ($status_js!=0) {
				  		//	echo 1;die;	
				  			$dir_date = date("Y-m-d");
				  			$full_js_path =_THEME_JS_DIR_."shop$shop/$dir_date/"; 
  							$check=0;
  							while(!is_dir($_SERVER{'DOCUMENT_ROOT'}.$full_js_path)) {	
  								//echo $_SERVER{'DOCUMENT_ROOT'}.$full_tpl_path;die;
  		  		  			 $dir_date = date_create($dir_date);
  		  		  			  date_sub($dir_date, date_interval_create_from_date_string('1 days'));
  		  		  		 			$dir_date	=date_format($dir_date, 'Y-m-d');
  		  		  		 			if ($shop) {
											$full_js_path =_THEME_JS_DIR_."shop$shop/$dir_date/"; 
							  			}
							  			else
								  			{
								  			$full_js_path =_THEME_JS_DIR_."$dir_date/"; 
								  			}
  		  		  			//	$full_js_path =_THEME_JS_DIR_."shop$shop/$dir_date/";  
  		  		  				$check++;
  		  		  				if ($check	>	_UPDATE_CHECK_) {
  		  		  			 		if ($shop) {
											$full_js_path=_THEME_JS_DIR_."shop$shop/";
							  			}
							  			else
								  			{
								  			$full_js_path =_THEME_JS_DIR_; 
								  			}
  		  		  			 		break;
  		  		  			 	} 		
  		  		  			//echo $full_css_path;die;
  		  		  			//echo 1;	
  		  				}
						
	//	echo $full_js_path;die;
					}
					
				}
		//$full_css_path.=$dir_date;
		
		//echo $full_css_path;die;
		if ($shop) {
			self::$smarty->assign('shop', $shop);
			Tools::addCSS($full_css_path."global.css", 'all');
			Tools::addJS(array(_PS_JS_DIR_.'jquery/jquery-1.4.4.min.js', _PS_JS_DIR_.'jquery/jquery.easing.1.3.js', _PS_JS_DIR_.'tools.js'));
			if (Tools::isSubmit('live_edit') AND Tools::getValue('ad') AND (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_)))
			{
				Tools::addJS(array(
								_PS_JS_DIR_.'jquery/jquery-ui-1.8.10.custom.min.js',
								_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js',
								_PS_JS_DIR_.'hookLiveEdit.js')
								);
				Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css');
			}
			$language = new Language($cookie->id_lang);
			Tools::addCSS($full_css_path.'body.css');
			Tools::addCSS($full_css_path."menu.css");
			Tools::addCSS($full_css_path."content.css");
			if ($language->is_rtl)
			Tools::addCSS($full_css_path."rtl.css");
			
			Tools::addCSS($full_css_path."tag.css", 'all');
		//	Tools::addCSS(_THEME_CSS_DIR_.'shop'.$shop."/style.css", 'all');
			Tools::addCSS($full_css_path."icon.css", 'all');
			Tools::addCSS($full_css_path."dropdown-sub-menu.css", 'all');
			Tools::addCSS($full_css_path."colour.css", 'all');
			Tools::addCSS($full_css_path."validation.css", 'all');
			Tools::addCSS($full_css_path."openid.css", 'all');
	   		 Tools::addCSS(_THEME_CSS_DIR_."contact-form.css");	
	    	Tools::addCSS(_THEME_CSS_DIR_."ajaxloading.css");
	   		 Tools::addCSS(_THEME_CSS_DIR_.'jquery.alert.css');
	    	 Tools::addCSS(_THEME_CSS_DIR_."header.css");
	    	  Tools::addCSS(_THEME_CSS_DIR_."footer.css");
	/*
	@import url('dropdown-sub-menu.css');
	@import url('colour.css');
	*/
			
			//Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/openid-jquery.js');  
			//Tools::addJS(_THEME_JS_DIR_.'shop'.$shop.'/openid-en.js');  
			Tools::addJS(_PS_JS_DIR_.'jquery.alert.js');
	//		Tools::addJS(_THEME_JS_DIR_.'jquery/jquery-1.6.1.js');
			Tools::addJS($full_js_path.'jquery/jquery.corner.js');
			Tools::addJS($full_js_path.'layout.js');
			Tools::addJS($full_js_path.'.menu.js');
			Tools::addJS($full_js_path.'mainshop.js');
			Tools::addJS(_PS_JS_DIR_.'hoverDelay.js');
			Tools::addJS(_PS_JS_DIR_.'dropdownbanner.js');
			Tools::addJS($full_js_path.'menu-help.js');
			Tools::addJs(_THEME_JS_DIR_.'shop'.$shop.'/jquery.hoverIntent.minified.js');
			Tools::addJS(_PS_JS_DIR_.'jquery.validate.min.js');
			Tools::addJS(_PS_JS_DIR_.'validation-en.js');
			Tools::addJS(_PS_JS_DIR_.'ajaxloading.js');
			Tools::addJS(_PS_JS_DIR_.'main.js');
			Tools::addJS(_PS_JS_DIR_.'jquery.maskedinput1.3.js');
		}
		else{
			Tools::addCSS(_THEME_CSS_DIR_.'global.css', 'all');
			Tools::addJS(array(_PS_JS_DIR_.'jquery/jquery-1.4.4.min.js', _PS_JS_DIR_.'jquery/jquery.easing.1.3.js', _PS_JS_DIR_.'tools.js'));
			if (Tools::isSubmit('live_edit') AND Tools::getValue('ad') AND (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_)))
			{
				Tools::addJS(array(
								_PS_JS_DIR_.'jquery/jquery-ui-1.8.10.custom.min.js',
								_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js',
								_PS_JS_DIR_.'hookLiveEdit.js')
								);
				Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css');
			}
			$language = new Language($cookie->id_lang);
			Tools::addCSS(_THEME_CSS_DIR_.'body.css');
			Tools::addCSS(_THEME_CSS_DIR_.'menu.css');
			Tools::addCSS(_THEME_CSS_DIR_.'content.css');
			if ($language->is_rtl)
			Tools::addCSS(_THEME_CSS_DIR_.'rtl.css');
			Tools::addCSS(_THEME_CSS_DIR_.'tag.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'style.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'icon.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'dropdown-sub-menu.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'colour.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'validation.css', 'all');
			Tools::addCSS(_THEME_CSS_DIR_.'openid.css', 'all');
	   		 Tools::addCSS(_THEME_CSS_DIR_.'contact-form.css');	
	    	Tools::addCSS(_THEME_CSS_DIR_.'ajaxloading.css');
	   		 Tools::addCSS(_THEME_CSS_DIR_.'jquery.alert.css');
	    	 
	/*
	@import url('dropdown-sub-menu.css');
	@import url('colour.css');
	*/
			
			Tools::addJS(_THEME_JS_DIR_.'openid-jquery.js');  
			Tools::addJS(_THEME_JS_DIR_.'openid-en.js');  
			Tools::addJS(_PS_JS_DIR_.'jquery.alert.js');
	//		Tools::addJS(_THEME_JS_DIR_.'jquery/jquery-1.6.1.js');
			Tools::addJS(_THEME_JS_DIR_.'jquery/jquery.corner.js');
			Tools::addJS(_THEME_JS_DIR_.'layout.js');
			Tools::addJS(_THEME_JS_DIR_.'menu.js');
			Tools::addJS(_PS_JS_DIR_.'main.js');
			Tools::addJS(_PS_JS_DIR_.'hoverDelay.js');
			Tools::addJS(_PS_JS_DIR_.'dropdownbanner.js');
			Tools::addJS(_THEME_JS_DIR_.'menu-help.js');
			Tools::addJs(_THEME_JS_DIR_.'jquery.hoverIntent.minified.js');
			Tools::addJS(_PS_JS_DIR_.'jquery.validate.min.js');
			Tools::addJS(_PS_JS_DIR_.'validation-en.js');
			Tools::addJS(_PS_JS_DIR_.'ajaxloading.js');
				Tools::addJS(_PS_JS_DIR_.'jquery.maskedinput1.3.js');
		}
	}

	public function init()
		{
			if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
			global $cookie, $smarty, $cart, $iso, $defaultCountry, $protocol_link, $protocol_content, $link, $css_files, $js_files;

			if (self::$initialized)
				return;
			self::$initialized = true;

			$css_files = array();
			$js_files = array();


			if ($this->ssl AND (empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) == 'off') AND Configuration::get('PS_SSL_ENABLED'))
			{
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: '.Tools::getShopDomainSsl(true).$_SERVER['REQUEST_URI']);
				exit();
			}

			ob_start();

			/* Loading default country */
			$defaultCountry = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
			$cookieLifetime = (time() + (((int)Configuration::get('PS_COOKIE_LIFETIME_FO') > 0 ? (int)Configuration::get('PS_COOKIE_LIFETIME_FO') : 1)* 3600));
			$cookie = new Cookie('ps', '', $cookieLifetime);
			$link = new Link();

			if ($this->auth AND !$cookie->isLogged($this->guestAllowed))
				Tools::redirect('authentication.php'.($this->authRedirection ? '?back='.$this->authRedirection : ''));

			/* Theme is missing or maintenance */
			if (!is_dir(_PS_THEME_DIR_))
				die(Tools::displayError('Current theme unavailable. Please check your theme directory name and permissions.'));
			elseif (basename($_SERVER['PHP_SELF']) != 'disabled.php' AND !(int)(Configuration::get('PS_SHOP_ENABLE')))
				$this->maintenance = true;
			elseif (Configuration::get('PS_GEOLOCATION_ENABLED'))
				$this->geolocationManagement();

			// Switch language if needed and init cookie language
			if ($iso = Tools::getValue('isolang') AND Validate::isLanguageIsoCode($iso) AND ($id_lang = (int)(Language::getIdByIso($iso))))
				$_GET['id_lang'] = $id_lang;

			Tools::switchLanguage();
			Tools::setCookieLanguage();

			/* attribute id_lang is often needed, so we create a constant for performance reasons */
			if (!defined('_USER_ID_LANG_'))
				define('_USER_ID_LANG_', (int)$cookie->id_lang);

			if (isset($_GET['logout']) OR ($cookie->logged AND Customer::isBanned((int)$cookie->id_customer)))
			{
				$cookie->logout();
				Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL);
			}
			elseif (isset($_GET['mylogout']))
			{
				$cookie->mylogout();
				Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL);
			}
      
			global $currency;
			$currency = Tools::setCurrency();

			// $_MODULES = array();

			/* Cart already exists */
			if ((int)$cookie->id_cart)
			{
				$cart = new Cart((int)$cookie->id_cart);
				if ($cart->OrderExists())
					unset($cookie->id_cart, $cart, $cookie->checkedTOS);
				/* Delete product of cart, if user can't make an order from his country */
				elseif (intval(Configuration::get('PS_GEOLOCATION_ENABLED')) AND 
						!in_array(strtoupper($cookie->iso_code_country), explode(';', Configuration::get('PS_ALLOWED_COUNTRIES'))) AND 
						$cart->nbProducts() AND intval(Configuration::get('PS_GEOLOCATION_NA_BEHAVIOR')) != -1 AND
						!self::isInWhitelistForGeolocation())
					unset($cookie->id_cart, $cart);
				elseif ($cookie->id_customer != $cart->id_customer OR $cookie->id_lang != $cart->id_lang OR $cookie->id_currency != $cart->id_currency)
				{
					if ($cookie->id_customer)
						$cart->id_customer = (int)($cookie->id_customer);
					$cart->id_lang = (int)($cookie->id_lang);
					$cart->id_currency = (int)($cookie->id_currency);
					$cart->update();
				}
				/* Select an address if not set */
				if (isset($cart) && (!isset($cart->id_address_delivery) || $cart->id_address_delivery == 0 || 
					!isset($cart->id_address_invoice) || $cart->id_address_invoice == 0) && $cookie->id_customer)
				{
					$to_update = false;
					if (!isset($cart->id_address_delivery) || $cart->id_address_delivery == 0)
					{
						$to_update = true;
						$cart->id_address_delivery = (int)Address::getFirstCustomerAddressId($cart->id_customer);
					}
					if (!isset($cart->id_address_invoice) || $cart->id_address_invoice == 0)
					{
						$to_update = true;
						$cart->id_address_invoice = (int)Address::getFirstCustomerAddressId($cart->id_customer);
					}
					if ($to_update)
						$cart->update();
				}
			}


			if (!isset($cart) OR !$cart->id)
			{
				$cart = new Cart();
				$cart->id_lang = (int)($cookie->id_lang);
				$cart->id_currency = (int)($cookie->id_currency);
				$cart->id_guest = (int)($cookie->id_guest);
				if ($cookie->id_customer)
				{
					$cart->id_customer = (int)($cookie->id_customer);
					$cart->id_address_delivery = (int)(Address::getFirstCustomerAddressId($cart->id_customer));
					$cart->id_address_invoice = $cart->id_address_delivery;
				}
				else
				{
					$cart->id_address_delivery = 0;
					$cart->id_address_invoice = 0;
				}
			}
			if (!$cart->nbProducts())
				$cart->id_carrier = NULL;

			$locale = strtolower(Configuration::get('PS_LOCALE_LANGUAGE')).'_'.strtoupper(Configuration::get('PS_LOCALE_COUNTRY').'.UTF-8');
			setlocale(LC_COLLATE, $locale);
			setlocale(LC_CTYPE, $locale);
			setlocale(LC_TIME, $locale);
			setlocale(LC_NUMERIC, 'en_US.UTF-8');

			if (Validate::isLoadedObject($currency))
				$smarty->ps_currency = $currency;
			if (Validate::isLoadedObject($ps_language = new Language((int)$cookie->id_lang)))
				$smarty->ps_language = $ps_language;

			/* get page name to display it in body id */
			// $pathinfo = pathinfo(__FILE__);
			// $page_name = basename($_SERVER['PHP_SELF'], '.'.$pathinfo['extension']);
			// $page_name = (preg_match('/^[0-9]/', $page_name)) ? 'page_'.$page_name : $page_name;
			// $smarty->assign(Tools::getMetaTags($cookie->id_lang, $page_name));
			// $smarty->assign('request_uri', Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])));

			/* get page name to display it in body id */
			$page_name = (isset($this->php_self) ? preg_replace('/\.php$/', '', $this->php_self) : '');
			$smarty->assign(Tools::getMetaTags($cookie->id_lang, $page_name));
			$smarty->assign('request_uri', Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])));

			/* Breadcrumb */
			$navigationPipe = (Configuration::get('PS_NAVIGATION_PIPE') ? Configuration::get('PS_NAVIGATION_PIPE') : '>');
			$smarty->assign('navigationPipe', $navigationPipe);

			$protocol_link = (Configuration::get('PS_SSL_ENABLED') OR (!empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
			$protocol_content = ((isset($useSSL) AND $useSSL AND Configuration::get('PS_SSL_ENABLED')) OR (!empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
			if (!defined('_PS_BASE_URL_'))
				define('_PS_BASE_URL_', Tools::getShopDomain(true));
			if (!defined('_PS_BASE_URL_SSL_'))
				define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
		
			$link->preloadPageLinks();

      // match not only /thoitrang but also: /thoitrang?abc=4&xyz=2
//			if(Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])) != '/thoitrang')

      //mphuongz index.html => welcome page
			if(Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])) == '/' || Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])) == '/en/'){
				Tools::redirectLink(_PS_BASE_URL_.'/thoitrang?shop=2');
			}
      elseif(!preg_match("/(^\/thoitrang)/i", Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])))){
				$this->canonicalRedirection();
      }

			Product::initPricesComputation();

			$display_tax_label = $defaultCountry->display_tax_label;
			if ($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')})
			{
				$infos = Address::getCountryAndState((int)($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
				$country = new Country((int)$infos['id_country']);
				if (Validate::isLoadedObject($country))
					$display_tax_label = $country->display_tax_label;
			}

			$smarty->assign(array(
				'link' => $link,
				'cart' => $cart,
				'currency' => $currency,
				'cookie' => $cookie,
				'page_name' => $page_name,
				'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
				'base_dir_ssl' => $protocol_link.Tools::getShopDomainSsl().__PS_BASE_URI__,
				'content_dir' => $protocol_content.Tools::getShopDomain().__PS_BASE_URI__,
				'tpl_dir' => _PS_THEME_DIR_,
				'modules_dir' => _MODULE_DIR_,
				'mail_dir' => _MAIL_DIR_,
				'lang_iso' => $ps_language->iso_code,
				'come_from' => Tools::getHttpHost(true, true).Tools::htmlentitiesUTF8(str_replace('\'', '', urldecode($_SERVER['REQUEST_URI']))),
				'cart_qties' => (int)$cart->nbProducts(),
				'currencies' => Currency::getCurrencies(),
				'languages' => Language::getLanguages(),
				'priceDisplay' => Product::getTaxCalculationMethod(),
				'add_prod_display' => (int)Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'shop_name' => Configuration::get('PS_SHOP_NAME'),
				'roundMode' => (int)Configuration::get('PS_PRICE_ROUND_MODE'),
				'use_taxes' => (int)Configuration::get('PS_TAX'),
				'display_tax_label' => (bool)$display_tax_label,
				'vat_management' => (int)Configuration::get('VATNUMBER_MANAGEMENT'),
				'opc' => (bool)Configuration::get('PS_ORDER_PROCESS_TYPE'),
				'PS_CATALOG_MODE' => (bool)Configuration::get('PS_CATALOG_MODE')
			));

			// Deprecated
			$smarty->assign(array(
				'id_currency_cookie' => (int)$currency->id,
				'logged' => $cookie->isLogged(),
				'customerName' => ($cookie->logged ? $cookie->customer_firstname.' '.$cookie->customer_lastname : false)
			));

			// TODO for better performances (cache usage), remove these assign and use a smarty function to get the right media server in relation to the full ressource name
			$assignArray = array(
				'img_ps_dir' => _PS_IMG_,
				'img_cat_dir' => _THEME_CAT_DIR_,
				'img_lang_dir' => _THEME_LANG_DIR_,
				'img_prod_dir' => _THEME_PROD_DIR_,
				'img_manu_dir' => _THEME_MANU_DIR_,
				'img_sup_dir' => _THEME_SUP_DIR_,
				'img_ship_dir' => _THEME_SHIP_DIR_,
				'img_store_dir' => _THEME_STORE_DIR_,
				'img_col_dir' => _THEME_COL_DIR_,
				'img_dir' => _THEME_IMG_DIR_,
				'css_dir' => _THEME_CSS_DIR_,
				'js_dir' => _THEME_JS_DIR_,
				'pic_dir' => _THEME_PROD_PIC_DIR_
			);

			foreach ($assignArray as $assignKey => $assignValue)
				if (substr($assignValue, 0, 1) == '/' OR $protocol_content == 'https://')
					$smarty->assign($assignKey, $protocol_content.Tools::getMediaServer($assignValue).$assignValue);
				else
					$smarty->assign($assignKey, $assignValue);

			// setting properties from global var
			self::$cookie = $cookie;
			self::$cart = $cart;
			self::$smarty = $smarty;
			self::$link = $link;

			if ($this->maintenance)
				$this->displayMaintenancePage();
			if ($this->restrictedCountry)
				$this->displayRestrictedCountryPage();

			//live edit
			if (Tools::isSubmit('live_edit') AND $ad = Tools::getValue('ad') AND (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_)))
				if (!is_dir(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$ad))
					die(Tools::displayError());


			$this->iso = $iso;
			
			if (!Tools::getValue('ajaxload')) {
				$this->setMedia();
				
			}
		}	
	
	public function displayHeader()
	{
		if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
		global $css_files, $js_files;

		if (!self::$initialized)
			$this->init();

		// P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
		header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

		/* Hooks are volontary out the initialize array (need those variables already assigned) */
		self::$smarty->assign(array(
			'time' => time(),
			'img_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
			'static_token' => Tools::getToken(false),
			'token' => Tools::getToken(),
			'logo_image_width' => Configuration::get('SHOP_LOGO_WIDTH'),
			'logo_image_height' => Configuration::get('SHOP_LOGO_HEIGHT'),
			'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
			'content_only' => (int)(Tools::getValue('content_only'))
		));
		self::$smarty->assign(array(
			'HOOK_HEADER' => Module::hookExec('header'),
			'HOOK_TOP' => Module::hookExec('top'),
			'HOOK_LEFT_COLUMN' => Module::hookExec('leftColumn')
		));
		
		if ((Configuration::get('PS_CSS_THEME_CACHE') OR Configuration::get('PS_JS_THEME_CACHE')) AND is_writable(_PS_THEME_DIR_.'cache'))
		{
			// CSS compressor management
			if (Configuration::get('PS_CSS_THEME_CACHE'))
				Tools::cccCss();

			//JS compressor management
			if (Configuration::get('PS_JS_THEME_CACHE'))
				Tools::cccJs();
		}
		
//		var_dump($css_files);
		
		self::$smarty->assign('css_files', $css_files);
		self::$smarty->assign('js_files', array_unique($js_files));
		//self::$smarty->display(_PS_THEME_DIR_.'header.tpl');
	}
	
	public function displayFooter()
	{
		if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		  	else{
		  			$shop=0;
		  		}
//		global $cookie;
		if (!self::$initialized)
			$this->init();

		self::$smarty->assign(array(
			'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn', array('cart' => self::$cart)),
			'HOOK_FOOTER' => Module::hookExec('footer'),
				'HOOK_COMMON_FOOTER' => Module::hookExec('commonFooter'),
			'content_only' => (int)(Tools::getValue('content_only'))));
		//self::$smarty->display(_PS_THEME_DIR_.'footer.tpl');
		//live edit
		if (Tools::isSubmit('live_edit') AND $ad = Tools::getValue('ad') AND (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_)))
		{
			self::$smarty->assign(array('ad' => $ad, 'live_edit' => true));
			//self::$smarty->display(_PS_ALL_THEMES_DIR_.'live_edit.tpl');
		}
		else
			Tools::displayError();
	}
	
	public function displayPagelayout()
	{
		if (Tools::getValue('shop')) {
			  				$shop=Tools::getValue('shop');
			  	}
		else{
		  			$shop=0;
		  		}
		  		$update_tpl=explode('_',_UPDATE_TPL_);
		  		$status_tpl=$update_tpl[$shop];
		 if ($shop) {
					$full_tpl_path=_PS_THEME_DIR_."shop$shop/";
	  			}
	  			else
		  			{
		  			$full_tpl_path =_PS_THEME_DIR_; 
		  			}
		  		//echo $dir_date;die;
		  		if (Tools::getValue('date')&&Tools::getValue('tpl')) {
	  				$dir_date=Tools::getValue('date');
	  				$full_tpl_path .="$dir_date/"; 
	  				//echo 	$full_css_path;die;
	  			}
	  			else{			  			
				  		if ($status_tpl!=0) {
				  		//	echo 1;die;	
				  			$dir_date = date("Y-m-d");
				  			$full_tpl_path =_PS_THEME_DIR_."shop$shop/$dir_date/"; 
  							$check=0;
  							while(!is_dir($full_tpl_path)) {	
  								//echo $_SERVER{'DOCUMENT_ROOT'}.$full_tpl_path;die;
  		  		  			 $dir_date = date_create($dir_date);
  		  		  			  date_sub($dir_date, date_interval_create_from_date_string('1 days'));
  		  		  		 			$dir_date	=date_format($dir_date, 'Y-m-d');
  		  		  		 			if ($shop) {
												$full_tpl_path =_PS_THEME_DIR_."shop$shop/$dir_date/"; 
							  			}
							  			else
								  			{
								  				$full_tpl_path =_PS_THEME_DIR_."$dir_date/"; 
								  			}
  		  		  				//$full_tpl_path =_PS_THEME_DIR_."shop$shop/$dir_date/";  
  		  		  				$check++;
  		  		  				if ($check	>	_UPDATE_CHECK_) {
  		  		  			 		if ($shop) {
											$full_tpl_path=_PS_THEME_DIR_."shop$shop/";
							  			}
							  			else
								  			{
								  			$full_tpl_path =_PS_THEME_DIR_; 
								  			}
  		  		  			 		break;
  		  		  			 	} 		
  		  		  			//echo  	$_SERVER{'DOCUMENT_ROOT'}.$full_tpl_path;die;
  		  		  			//echo 1;	
  		  				}
						
		
					}
					
				}
				
	  $_POST = array_merge($_POST, $_GET);	
	 // $shop=Tools::getValue('shop');		
		self::$smarty->display($full_tpl_path.'pagelayout.tpl');
	
	}

	public function pagination($nbProducts = 10)
		{
			if (!self::$initialized)
				$this->init();

			$nArray = (int)(Configuration::get('PS_PRODUCTS_PER_PAGE')) != 10 ? array((int)(Configuration::get('PS_PRODUCTS_PER_PAGE')), 16, 20, 36) : array(10, 20, 50);
			asort($nArray);
			$this->n = abs((int)(Tools::getValue('n', ((isset(self::$cookie->nb_item_per_page) AND self::$cookie->nb_item_per_page >= 10) ? self::$cookie->nb_item_per_page : (int)(Configuration::get('PS_PRODUCTS_PER_PAGE'))))));
			$this->p = abs((int)(Tools::getValue('p', 1)));

			$range = 2; /* how many pages around page selected */

			if ($this->p < 0)
				$this->p = 0;

			if (isset(self::$cookie->nb_item_per_page) AND $this->n != self::$cookie->nb_item_per_page AND in_array($this->n, $nArray))
				self::$cookie->nb_item_per_page = $this->n;

			$this->n = $this->n ? $this->n : '16';

			if ($this->p > ($nbProducts / $this->n))
				$this->p = ceil($nbProducts / $this->n);
			$pages_nb = ceil($nbProducts / (int)($this->n));



			$start = (int)($this->p - $range);
			if ($start < 1)
				$start = 1;
			$stop = (int)($this->p + $range);
			if ($stop > $pages_nb)
				$stop = (int)($pages_nb);
			self::$smarty->assign('nb_products', $nbProducts);

			$pagination_infos = array(
				'pages_nb' => (int)($pages_nb),
				'p' => (int)($this->p),
				'n' => (int)($this->n),
				'nArray' => $nArray,
				'range' => (int)($range),
				'start' => (int)($start),
				'stop' => (int)($stop)
			);

	//		var_dump($pagination_infos); die;

			self::$smarty->assign($pagination_infos);
		}
	
	public function run()
	{
		if (Tools::getValue('ajaxload')) {
			$this->init();
			$this->preProcess();
			$this->process();
			die;
		};
		$this->init();
		$this->preProcess();
		$this->displayHeader();
		$this->process();
		$this->displayContent();		
		$this->displayFooter();
		$this->displayPagelayout();
	}
}