<div class="main-content"> 
    {if $PS_CATALOG_MODE}
        {capture name=path}{l s='Your shopping cart'}{/capture}
        <div class="navi">
             <h3>{include file="$tpl_dir./breadcrumb.tpl" short=true}
            </h3>
            <div class="shopping-continue"><strong><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="button_large" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>	
     	</div>
        <div class="box-shopping-cart"> 
        	<div class="header-title"><h3>{l s='Your shopping cart'}</h3></div>
            <div class="list">
            	<div class="item"><p class="warning">{l s='This store has not accepted your new order.'}</p></div>
           	</div>
       	</div>
    {else}
        <script type="text/javascript">
            // <![CDATA[
            var baseDir = '{$base_dir_ssl}';
            var imgDir = '{$img_dir}';
            var authenticationUrl = '{$link->getPageLink("authentication.php", true)}';
            var orderOpcUrl = '{$link->getPageLink("order-opc.php", true)}';
            var historyUrl = '{$link->getPageLink("history.php", true)}';
            var guestTrackingUrl = '{$link->getPageLink("guest-tracking.php", true)}';
            var addressUrl = '{$link->getPageLink("address.php", true)}';
            var orderProcess = 'order-opc';
            var guestCheckoutEnabled = {$PS_GUEST_CHECKOUT_ENABLED|intval};
            var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
            var currencyRate = '{$currencyRate|floatval}';
            var currencyFormat = '{$currencyFormat|intval}';
            var currencyBlank = '{$currencyBlank|intval}';
            var displayPrice = {$priceDisplay};
            var taxEnabled = {$use_taxes};
            var conditionEnabled = {$conditions|intval};
            var countries = new Array();
            var countriesNeedIDNumber = new Array();
            var countriesNeedZipCode = new Array();
            var vat_management = {$vat_management|intval};
            
            var txtWithTax = "{l s='(tax incl.)'}";
            var txtWithoutTax = "{l s='(tax excl.)'}";
            var txtHasBeenSelected = "{l s='has been selected'}";
            var txtNoCarrierIsSelected = "{l s='No carrier has been selected'}";
            var txtNoCarrierIsNeeded = "{l s='No carrier is needed for this order'}";
            var txtConditionsIsNotNeeded = "{l s='No terms of service must be accepted'}";
            var txtTOSIsAccepted = "{l s='Terms of service is accepted'}";
            var txtTOSIsNotAccepted = "{l s='Terms of service have not been accepted'}";
            var txtThereis = "{l s='There is'}";
            var txtErrors = "{l s='error(s)'}";
            var txtDeliveryAddress = "{l s='Delivery address'}";
            var txtInvoiceAddress = "{l s='Invoice address'}";
            var txtModifyMyAddress = "{l s='Modify my address'}";
            var txtInstantCheckout = "{l s='Instant checkout'}";
            var errorCarrier = "{$errorCarrier}";
            var errorTOS = "{$errorTOS}";
            var checkedCarrier = "{if isset($checked)}{$checked}{else}0{/if}";
        
            var addresses = new Array();
            var isLogged = {$isLogged|intval};
            var isGuest = {$isGuest|intval};
            var isVirtualCart = {$isVirtualCart|intval};
            var isPaymentStep = {$isPaymentStep|intval};
            //]]>
        </script>    
        <div class="box-adv">
            <img src="{$smarty.const._THEME_DIR_}images/data/adv-3.gif" alt="" border="0" />
        </div>  
        <div class="navi">
        	{capture name=path}{l s='Check out'}{/capture}            
            <h3>{include file="$tpl_dir./breadcrumb.tpl" short=true}</h3>
            {if $productNumber}
                <div class="shopping-continue"><strong><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="button_large" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>
            {else}
                <div class="shopping-continue"><strong><a href="{$link->getPageLink('index.php')}" class="button_large" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>
            {/if}
        </div>   
        <div class="box-checkout">
           	<div class="header-title"><h3>check out</h3></div>
            <div class="list"> 
                
                <div class="item">     
                	{$HOOK_PAYMENT} 	
                    <div class="header-title-sub"><h4>payment information</h4></div>  
                    <div class="notice">                           
                        <div class="error"><strong>error!</strong> please fill out all of the reqiured fields with valid information</div>
                    </div>
                    <div class="payment-method">
                        <ul>
                            <li class="first">pay by</li>
                            <li>
                                <input type="radio" />
                                <label>credit card</label>
                            </li>
                            <li>
                                <input type="radio" />
                                <label>faktura</label>
                            </li>
                        </ul>
                    </div>
                    <div class="card-info">                            	
                        <div class="user-input">
                            <label class="error-text">card number <span class="required">*</span></label>
                            <input />
                        </div>
                        <div class="user-input">
                            <label>cvc code <span class="required">*</span></label>
                            <input class="third" />
                            <a href="#" class="tooltipHelp"><img src="images/icon/question.gif" alt="" border="0" width="10" height="10"/></a>
                        </div>
                        <div class="user-input">
                            <label>expiry date <span class="required">*</span></label>
                            <select class="third">
                                <option value="">mm</option>
                            </select>
                            <select class="third">
                                <option value="">yy</option>
                            </select>
                        </div>
                        <div class="user-input">
                            <span class="required">* required field</span>
                        </div>
                    </div> 
                    <div class="card">
                        <h4>we take these cards</h4>
                        <ul>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.1.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.2.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.3.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.4.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.1.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.2.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.3.gif" alt="" border="0" />
                            </li>
                            <li>
                                <img src="{$smarty.const._THEME_DIR_}images/data/card.4.gif" alt="" border="0" />
                            </li>
                        </ul>                                
                    </div>
                    <div class="notice">                           
                        <div class="error"><strong>you cannot pay via invoice if the total amount of shopping is more than 5,000 DKK!</strong></div>
                    </div>
                </div>
                <div class="item">
                    
                    <div class="header-title-sub"><h4>billing information</h4></div>
                    <p>Please make sure that the billing address you enter is excactly the same as the on you have registered for your credit card.</p>
                    <ul class="billing">
                        <li>
                            <div class="user-input">
                                <label>first name <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>phone number <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>last name <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>country <span class="required">*</span></label>
                                <select>
                                    <option>selected</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>street address <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>email <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>city <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>confirm email <span class="required">*</span></label>
                                <input />
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>postal code <span class="required">*</span></label>
                                <input />
                            </div>
                            <div class="user-input">
                                <label>newsletter <span class="required">*</span></label>
                                <div class="chk">
                                    <input type="checkbox" />
                                    <label>subcribe to boozt.com newsletters</label>
                                </div>
                            </div>
                            <div class="user-input">
                                <span class="required">* required field</span>
                            </div>
                        </li>
                        <li>
                            <div class="user-input">
                                <label>ship to <span class="required">*</span></label>
                                <div class="radio">
                                    <span>
                                        <input type="radio" />
                                        <label>billing address</label>
                                    </span>
                                    <span>
                                        <input type="radio" />
                                        <label>another address</label>
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="action">
                        <input type="image" src="{$smarty.const._THEME_DIR_}images/btn/pay-place-order.gif"/>
                    </div>
                </div>
            </div> 
            {include file="$tpl_dir./box/need-help.tpl"}               
       	</div>
    {/if}
 </div>	