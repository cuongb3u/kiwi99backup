
<div class="main-content"> 	
    {if $PS_CATALOG_MODE}
        {capture name=path}{l s='Your shopping cart'}{/capture}
        <div class="navi">
             <h3>{include file="$tpl_dir./breadcrumb.tpl" short=true}
            </h3>
            <div class="shopping-continue"><strong><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>	
     	</div>
        <div class="box-shopping-cart">         	
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
        	
        	{capture name=path}{l s='Your shopping cart'}{/capture}            
            <h3>{include file="$tpl_dir./breadcrumb.tpl" short=true}</h3>
            {if $productNumber}
                <div class="shopping-continue"><strong><a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index.php')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="button_large" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>
            {else}
                <div class="shopping-continue"><strong><a href="{$link->getPageLink('index.php')}" class="button_large" title="{l s='Continue shopping'}">{l s='Continue shopping'}</a></strong></div>
            {/if}
        </div>  
        <div class="box-shopping-cart">         	
            {if $productNumber}
                <div class="list">
                    <div class="item {if !$products}no-margin-bottom{/if}">
                    	<div class="payment-method">
                        	<input type="hidden" id="idProcessStep" value="0" />
                            <ul>
                                <li class="{if !isset($smarty.cookies.chooseStep) || $smarty.cookies.chooseStep == 1||1}current{/if}" id="liPM1">
                                    <input type="radio" name="paymentMethod" id="paymentMethod1" {if isset($smarty.cookies.chooseStep) && $smarty.cookies.chooseStep == 1}checked="checked"{/if} />
                                    <label for="paymentMethod1">Thông tin giao hàng</label>
                                    <span class="next_step" onclick="javascript:chooseStep(2);">NEXT STEP ></span>
                                    <span class="payment_arrow"></span>
                                </li>
                                <li class="{if isset($smarty.cookies.chooseStep) && $smarty.cookies.chooseStep == 2&&0}current{/if}" id="liPM2" >
                                    <input type="radio" name="paymentMethod" id="paymentMethod2" {if isset($smarty.cookies.chooseStep) && $smarty.cookies.chooseStep == 2}checked="checked"{/if}/>
                                    <label for="paymentMethod2">Phương thức thanh toán</label>
                                     <span class="next_step" onclick="javascript:validatorMethodPayment();" >NEXT STEP ></span>
                                    <span class="back_step" onclick="javascript:chooseStep(1);"><   BACK</span>
                                   
                                      <span class="payment_arrow"></span>
                                </li>
                            </ul>
                        </div>
                        <div id="step1" class="step" style="display:{if !isset($smarty.cookies.chooseStep) ||$smarty.cookies.chooseStep == 1||1}block{else}none{/if}">
                        	
                            {if $isLogged AND !$isGuest}
                                {include file="$tpl_dir./order-address.tpl"}   
                                                              
                            {else}
                                <!-- Create account / Guest account / Login block -->
                                {include file="$tpl_dir./order-opc-new-account.tpl"}
                                <!-- END Create account / Guest account / Login block -->
                            {/if}
                             
                       	</div>  
                        <div id="step2" class="step" style="display:{if isset($smarty.cookies.chooseStep) && $smarty.cookies.chooseStep == 2&&0}block{else}none{/if}">
                            <!-- Payment -->
                            {include file="$tpl_dir./order-payment.tpl"}
                            <!-- END Payment -->                 
                        </div>             
                    </div>                    
                </div>    
                  <div class="right-cart">
                	{include file="$tpl_dir./shopping-cart.tpl"}  
                	</div>
            {*include file="$tpl_dir./box/need-help.tpl"*}    
                   </div>          
            {else}   
            	<div class="list w100">
                    <div class="item">
                		<p class="warning warning1">{l s='Your shopping cart is empty.'}</p>
                    </div>
                </div>
                </div>
            {/if}
              
    {/if}
</div>	
	
<script type="text/javascript" language="javascript" src="{$smarty.const._THEME_DIR_}js/jquery.cookie.js"></script>
<script type="text/javascript" language="javascript">
	$('.payment-method').corner("tr");	
	$(document).ready(function() {
		var intLogin = $('#idLogin').val();
		if($('#idProcessStep').val() == 0 && $('#idSubmitBlockCart').val() > 0) {
			var intProcessStep = $('#idSubmitBlockCart').val();
		}
		else
			var intProcessStep = 0;
		if(parseInt($('.ajax_cart_quantity').html()) == 0 || intProcessStep == 1) {
			chooseStep(1);			
		}
		if(intLogin == 0) {				
			if($.cookie("chooseStep") == 3 || $.cookie("chooseStep") == 4) {				
				chooseStep(1);
			}				
			$('#paymentMethod3').attr("disabled", "disabled");
			$('#paymentMethod3').attr("checked", "");
			$('#paymentMethod4').attr("disabled", "disabled");
			$('#paymentMethod4').attr("checked", "");
			
		}
		else {
			$('#paymentMethod3').attr("disabled", "");
			$('#paymentMethod4').attr("disabled", "");
		}	
	})

	function chooseStep(intStep) {	
		if(intStep > 1) {
			$('#idProcessStep').val(0);
			var intLogin = $('#idLogin').val();
			if(intStep == 3 && intLogin == 0) {
				return false;
			}
			if(intStep == 4 && intLogin == 0)
				return false;
		}
		else {
			$('#idProcessStep').val(1);
		}
		
		$('li').removeClass('current');
		$.cookie("chooseStep","");
		$('.step').hide();
		$('#step' + intStep).show();
		$('#liPM' + intStep).addClass('current');
		$('#paymentMethod' + intStep).attr("checked", "checked");
		$.cookie("chooseStep", intStep);	
		$('#opc_new_account-overlay').hide();	
		$('#opc_delivery_methods-overlay').hide();
		$('#opc_payment_methods-overlay').hide();
		$('#idSubmitBlockCart').val(0);
	}	
</script>