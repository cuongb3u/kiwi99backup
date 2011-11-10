<script type="text/javascript">
// <![CDATA[
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}false{/if};
countries = new Array();
countriesNeedIDNumber = new Array();
countriesNeedZipCode = new Array();
{if isset($countries)}
	{foreach from=$countries item='country'}
		{if isset($country.states) && $country.contains_states}
			countries[{$country.id_country|intval}] = new Array();
			{foreach from=$country.states item='state' name='states'}
				countries[{$country.id_country|intval}].push({ldelim}'id' : '{$state.id_state}', 'name' : '{$state.name|escape:'htmlall':'UTF-8'}'{rdelim});
			{/foreach}
		{/if}
		{if $country.need_identification_number}
			countriesNeedIDNumber.push({$country.id_country|intval});
		{/if}	
		{if isset($country.need_zip_code)}
			countriesNeedZipCode[{$country.id_country|intval}] = {$country.need_zip_code};
		{/if}
	{/foreach}
{/if}
$(function(){ldelim}
	$('.id_state option[value={if isset($smarty.post.id_state)}{$smarty.post.id_state}{else}{if isset($address)}{$address->id_state|escape:'htmlall':'UTF-8'}{/if}{/if}]').attr('selected', 'selected');
{rdelim});
//]]>
{if $vat_management}
	{literal}
	$(document).ready(function() {
		$('#company').blur(function(){
			vat_number();
		});
		vat_number();
		function vat_number()
		{
			if ($('#company').val() != '')
				$('#vat_number').show();
			else
				$('#vat_number').hide();
		}
	});
	{/literal}
{/if}
</script>
<div class="box-register{if $type!=1}-payment{/if}">                	
    <div class="header-title"><h3>{if $type==1}{l s='Create an account'}{else}Đăng ký tài khoản mới{/if}</h3></div>              
    <div class="reg-item">        
        {if $type==1}
        	{include file="$tpl_dir./errors.tpl"}
        	<div class="header-title-sub line"><h4 id="new_account_title">{l s='Your personal information'}</h4></div>
       	{else}        	
        	<div id="opc_account_choice">
				<div class="opc_float">
					<h4>{l s='Instant Checkout'}</h4>
					<p>
						<input type="button" class="btn-large" id="opc_guestCheckout" value="{l s='Checkout as guest'}" />
					</p>
				</div>
				<div class="opc_float">
					<h4>{l s='Create your account today and enjoy:'}</h4>
					<ul class="bullet">
						<li>{l s='Personalized and secure access'}</li>
						<li>{l s='Fast and easy check out'}</li>
					</ul>
					<p>
						<input type="button" class="btn-large" id="opc_createAccount" value="{l s='Create an account'}" />
					</p>
				</div>
				<div class="clear"></div>
			</div>
            <div id="opc_account_form">
            	<div id="opc_account_errors" class="error" style="display:none;"></div>
                <div class="left-column">
                	<div class="header-title-sub"><h4 id="new_account_title">{l s='Your personal information'}</h4></div>
        {/if}    
         <div class="user-input">
            <label>{l s='Xưng danh'} <span class="required"></span>:</label>
            <div>
                <input type="radio" selected="selected" name="id_gender" id="id_gender1" value="1" checked="checked" class="radio"/>
                <label for="id_gender1" class="radio">{l s='Anh'}</label>
            </div>
            <div>
                <input type="radio" name="id_gender" id="id_gender2" value="2" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == 2}checked="checked"{/if} class="radio"/>
                <label for="id_gender2" class="radio">{l s='Chị'}</label>
            </div>
        </div>  
        <div class="user-input">
            <label for="customer_firstname">{l s='Họ & Tên'} <span class="required">*</span>:</label>
            <input onkeyup="$('#firstname').val(this.value);"	class="validate[required,custom[onlyLetterSp]]" type="text" id="customer_firstname" name="customer_firstname" value="{if isset($smarty.post.customer_firstname)}{$smarty.post.customer_firstname}{/if}" />
        </div>
        {*
            <div class="user-input">
              <label for="customer_lastname">{l s='Họ'} <span class="required">*</span>:</label>
              <input onkeyup="$('#lastname').val(this.value);" class="validate[required,custom[onlyLetterSp]]"  type="text" id="customer_lastname" name="customer_lastname" value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname}{/if}" />
            </div> 
      	*}   
        <div class="user-input">
            <label for="email">{l s='E-mail'} <span class="required">*</span>:</label>
            <input type="text" id="email" class="validate[required,custom[email]] " name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" />
            <span id="error_email">E-mail đã được đăng ký.</span>
             <span id="success_email">E-mail chưa được đăng ký.</span>
        </div>      
       <div class="user-input">
           <label for="days">{l s='Ngày sinh'} <span class="required">*</span>:</label>
           
           <select id="days" name="days" class="third">
              {* <option value="">-</option>*}
               {foreach from=$days item=day}
                {if $type == 1}
                    <option value="{$day|escape:'htmlall':'UTF-8'}" {*if ($sl_day == $day)} selected="selected"{/if*}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                    {else}
                    <option value="{$day|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_day == $day)} selected="selected"{/if}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                   {/if}
               {/foreach}
           </select>                
           <select id="months" name="months" class="third">
               {*<option value="">-</option>*}
               {foreach from=$months key=k item=month}
                {if $type == 1}
                    <option value="{$k|escape:'htmlall':'UTF-8'}" {*if ($sl_month == $k)} selected="selected"{/if*}>{l s="$month"}&nbsp;</option>
                    {else}
                    <option value="{$k|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_month == $k)} selected="selected"{/if}>{l s="$month"}&nbsp;</option>
                   {/if}
               {/foreach}
           </select>
           <select id="years" name="years" class="third">
              {* <option value="">-</option>*}
               {foreach from=$years item=year}
                {if $type == 1}
                    <option value="{$year|escape:'htmlall':'UTF-8'}" {*if ($sl_year == $year)} selected="selected"{/if*}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                    {else}
                    <option value="{$year|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_year == $year)} selected="selected"{/if}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                   {/if}
               {/foreach}
           </select>                            
       </div>                  
       {if $dlv_all_fields}
        	{if $type == 1}
            	<div  style="visibility: hidden;height: 0px;padding:0;margin:0" class="header-title-sub line" ><h4>{l s='Your address'}</h4></div>
                {foreach from=$dlv_all_fields item=field_name}
                    {if $field_name eq "company"}
                        <div class="user-input is_customer_param">
                            <label for="company">{l s='Company'}</label>
                            <input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{/if}" />
                        </div>
                    {elseif $field_name eq "vat_number"}
                        <div id="vat_number" style="display:none;">
                            <div class="user-input">
                                <label for="vat_number">{l s='VAT number'}</label>
                                <input type="text" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{/if}" />
                            </div>
                        </div>
                    {elseif $field_name eq "firstname"}
                        <div  style="visibility: hidden;height: 0px;padding:0;margin:0" class="user-input">
                            <label for="firstname">{l s='First name'} <span class="required">*</span>:</label>
                            <input type="text" id="firstname" name="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{/if}" />
                        </div>
                    {elseif $field_name eq "lastname"}
                        <div  style="visibility: hidden;height: 0px;padding:0;margin:0" class="user-input">
                            <label for="lastname">{l s='Last name'} <span class="required">*</span>:</label>
                            <input type="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{/if}" />
                        </div>
                    {elseif $field_name eq "address1"}
                        <div class="user-input">
                            <label for="address1">{l s='Address'} <span class="required">*</span>:</label>
                            <input type="text" name="address1" id="address1" class="validate[required]" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{/if}" />
                            <span class="inline-infos">{l s='Street address, P.O. box, compagny name, c/o'}</span>
                        </div>
                    {elseif $field_name eq "address2"}
                        <div class="user-input is_customer_param">
                            <label for="address2">{l s='Address (Line 2)'}:</label>
                            <input type="text" name="address2" id="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{/if}" />
                            <span class="inline-infos">{l s='Apartment, suite, unit, building, floor, etc.'}</span>
                        </div>
                    {elseif $field_name eq "postcode"}
                    	<input type="hidden" name="postcode" id="postcode" value="0" />
                        <div class="user-input hidden">
                            <label for="postcode">{l s='Zip / Postal Code'} <span class="required">*</span>:</label>
                            <input type="text" name="postcode" id="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
                        </div>
                    {elseif $field_name eq "city"}
                       <div class="user-input">
                            <label for="city">{l s='City'} <span class="required">*</span>:</label>
                           
                            <select id="city" name="city">
                                {*<option value="">-</option>*}
                                {$provinces|@var_dump}
                                {foreach from=$provinces key=k item=row}
                                	<option value="{$row.provinceid|escape:'htmlall':'UTF-8'}" {if (isset($smarty.post.city) && $smarty.post.city == $row.provinceid ) OR $row.provinceid|escape:'htmlall':'UTF-8'	==	'1'} selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                {/foreach}
                            </select>
                        </div>
                        <!--
                            if customer hasn't update his layout address, country has to be verified
                            but it's deprecated
                        -->
                    {elseif $field_name eq "Country:name" || $field_name eq "country"}
                    	<input type="text" name="id_country" id="id_country" value="222" class="hidden"/>                        
                    {elseif $field_name eq "State:name"}
                        {assign var='stateExist' value=true}
                        <div class="user-input hidden">
                            <label for="id_state">{l s='State'} <span class="required">*</span>:</label>
                            <select name="id_state" id="id_state">
                                <option value="">-</option>
                            </select>
                        </div>
                    {/if}                    
                {/foreach}
           	{else}            	
            	</div>
                <div class="right-column">
            		{*<div class="header-title-sub line" style="visibility: hidden;height: 0px;padding:0;margin:0"><h4>{l s='Delivery address'}</h4></div>*}
                    {foreach from=$dlv_all_fields item=field_name}
                        {if $field_name eq "company"}
                            <div class="user-input is_customer_param">
                                <label for="company">{l s='Company'}:</label>
                                <input type="text" class="text" id="company" name="company" value="{if isset($guestInformations) && $guestInformations.company}{$guestInformations.company}{/if}" />
                            </div>
                        {elseif $field_name eq "firstname"}
                            <div class="user-input" style="visibility: hidden;height: 0px;padding:0;margin:0">
                                <label for="firstname">{l s='First name'}<span class="required">*</span> :</label>
                                <input type="text" class="text" id="firstname" name="firstname" value="{if isset($guestInformations) && $guestInformations.firstname}{$guestInformations.firstname}{/if}" />
                            </div>
                        {*elseif $field_name eq "lastname"}
                            <div class="user-input text" style="visibility: hidden;height: 0px;padding:0;margin:0">
                                <label for="lastname">{l s='Last name'}<span class="required">*</span> :</label>
                                <input type="text" class="text" id="lastname" name="lastname" value="{if isset($guestInformations) && $guestInformations.lastname}{$guestInformations.lastname}{/if}" />
                               
                            </div>
                         *}
                        {elseif $field_name eq "address1"}
                        	<div class="user-input text">
                                <label for="city">{l s='Tỉnh/thành'}<span class="required">*</span> :</label>
                                <select id="city" name="city" onchange="changeProvince(this.value,'district');">
                                    <option value="">-</option>
                                    {foreach from=$provinces key=k item=row}
                                        <option value="{$row.provinceid|escape:'htmlall':'UTF-8'}" {if (isset($guestInformations.city) && $guestInformations.city == $row.provinceid) || $row.provinceid	==1} selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                    {/foreach}
                                </select>   
                                <input type="text" name="id_country" id="id_country" value="222" style="width:1px;height:1px;border:none"/>
                            </div>
                            <div class="user-input text">
                                <label for="district">{l s='Quận/huyện'}<span class="required">*</span> :</label>
                                <select id="district" name="district">
                                    <option value="">-</option>
                                    {foreach from=$districts key=k item=row}
                                        <option value="{$row.districtid|escape:'htmlall':'UTF-8'}" {if (isset($guestInformations.district) && $guestInformations.district == $row.districtid) || $row.districtid	==1} selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                    {/foreach}
                                </select>   
                           	</div>
                            <div class="user-input text">
                                <label for="address1">{l s='Địa chỉ'}<span class="required">*</span> :</label>
                                <input type="text" class="text" name="address1" id="address1" value="{if isset($guestInformations) && $guestInformations.address1}{$guestInformations.address1}{/if}" />
                            </div>
                        {elseif $field_name eq "address2"}
                            <div class="user-input text is_customer_param">
                                <label for="address2">{l s='Address (Line 2)'}</label>
                                <input type="text" class="text" name="address2" id="address2" value="" />
                            </div>
                        {elseif $field_name eq "postcode"}
                            <input type="hidden" class="text" name="postcode" id="postcode" value="0" />
                            <div class="user-input hidden">
                                <label for="postcode">{l s='Zip / Postal code'}<span class="required">*</span> :</label>
                                <input type="text" class="text" name="postcode" id="postcode" value="{if isset($guestInformations) && $guestInformations.postcode}{$guestInformations.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
                            </div>
                        {elseif $field_name eq "city"}                            
                        {elseif $field_name eq "country" || $field_name eq "Country:name"}
                            <div class="user-input select hidden">
                                <label for="id_country">{l s='Country'}<span class="required">*</span> :</label>
                                <select name="id_country" id="id_country">
                                    <option value="">-</option>
                                    {foreach from=$countries item=v}
                                    <option value="{$v.id_country}" {if (isset($guestInformations) AND $guestInformations.id_country == $v.id_country) OR (!isset($guestInformations) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {elseif $field_name eq "vat_number"}                       
                            <div class="user-input" id="vat_number_block" style="display:none;">
                                <label for="vat_number">{l s='VAT number'}</label>
                                <input type="text" class="text" name="vat_number" id="vat_number" value="{if isset($guestInformations) && $guestInformations.vat_number}{$guestInformations.vat_number}{/if}" />
                            </div>                       
                        {/if}
                    {/foreach}
                    <div class="user-input hidden">
                        <label for="dni">{l s='Identification number'}<span class="required">*</span> :</label>
                        <input type="text" class="text" name="dni" id="dni" value="{if isset($guestInformations) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
                        <span class="form_info">{l s='DNI / NIF / NIE'}</span>
                    </div> 
                    <div class="user-input id_state select hidden">
                        <label for="id_state">{l s='State'}<span class="required">*</span> :</label>
                        <select name="id_state" id="id_state">
                            <option value="">-</option>
                        </select>
                    </div>
                    <div class="user-input is_customer_param">
                        <label for="other">{l s='Additional information'}:</label>
                        <textarea name="other" id="other" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{/if}</textarea>
                    </div>
                    <div class="user-input hidden">
                        {l s='You must register at least one phone number'} <span class="required">*</span>
                    </div>
                    <div class="user-input ">
                        <label for="phone">{l s='Số điện thoại'} <span class="required">*</span>:</label>
                        <input type="text" name="phone" class="validate[required,custom[onlyNumberSp]]" id="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
                    </div>
                    <div class="user-input is_customer_param">
                        <label for="phone_mobile">{l s='Mobile Phone'} <span class="required">*</span>:</label>
                        <input type="text" name="phone_mobile" id="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{/if}" />
                    </div>
                    <div class="user-input" id="address_alias" style="visibility: hidden;height: 0px;padding:0;margin:0">
                        <label for="alias">Tên đại diện <span class="required">*</span>:</label>
                        <input type="text" name="alias" id="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{l s='My address'}{/if}" />
                    </div> 
                    <div class="user-input checkbox" style="visibility: hidden;height: 0px;padding:0;margin:0">
                        <input type="checkbox" name="invoice_address" id="invoice_address" class="chk" />
                        <label for="invoice_address" class="chk"><strong>{l s='Please use another address for invoice'}</strong></label>
                    </div>                    
                    <div id="opc_invoice_address" class="clear">
                        <div class="header-title-sub line"><h4>{l s='Invoice address'}</h4></div>
                        {foreach from=$inv_all_fields item=field_name}
                            {if $field_name eq "company"}
                                <div class="user-input text is_customer_param">
                                    <label for="company_invoice">{l s='Company'}</label>
                                    <input type="text" class="text" id="company_invoice" name="company_invoice" value="" />
                                </div>
                            {elseif $field_name eq "vat_number"}
                                <div id="vat_number_block_invoice" class="is_customer_param" style="display:none;">
                                    <div class="user-input text">
                                        <label for="vat_number_invoice">{l s='VAT number'}</label>
                                        <input type="text" class="text" id="vat_number_invoice" name="vat_number_invoice" value="" />
                                    </div>
                                </div>
                                <div class="user-input text dni_invoice hidden">
                                    <label for="dni">{l s='Identification number'}<span class="required">*</span> :</label>
                                    <input type="text" class="text" name="dni" id="dni" value="{if isset($guestInformations) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
                                    <span class="form_info">{l s='DNI / NIF / NIE'}</span>
                               </div>
                            {elseif $field_name eq "firstname"}
                                <div class="user-input text">
                                    <label for="firstname_invoice">{l s='First name'}<span class="required">*</span> :</label>
                                    <input type="text" class="text" id="firstname_invoice" name="firstname_invoice" value="" />
                                </div>
                            {elseif $field_name eq "lastname"}
                                <div class="user-input text">
                                    <label for="lastname_invoice">{l s='Last name'}<span class="required">*</span> :</label>
                                    <input type="text" class="text" id="lastname_invoice" name="lastname_invoice" value="" />
                                </div>
                            {elseif $field_name eq "address1"}
                                <div class="user-input text">
                                    <label for="address1_invoice">{l s='Address'}<span class="required">*</span> :</label>
                                    <input type="text" class="text" name="address1_invoice" id="address1_invoice" value="" />
                                </div>
                            {elseif $field_name eq "address2"}
                                <div class="user-input text is_customer_param" style="display:none !important;">
                                    <label for="address2_invoice">{l s='Address (Line 2)'}</label>
                                    <input type="text" class="text" name="address2_invoice" id="address2_invoice" value="" />
                                </div>
                            {elseif $field_name eq "postcode"}
                                <input type="hidden" name="postcode_invoice" id="postcode_invoice" value="0"/>
                                <div class="user-input postcode text hidden">
                                    <label for="postcode_invoice">{l s='Zip / Postal Code'}<span class="required">*</span> :</label>
                                    <input type="text" class="text" name="postcode_invoice" id="postcode_invoice" value="" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
                                </div>
                            {elseif $field_name eq "city"}
                                <div class="user-input text">
                                    <label for="city_invoice">{l s='Tỉnh/thành'} <span class="required">*</span>:</label>
                                    <select name="city_invoice" id="city_invoice">
                                        <option value="">-</option>
                                        {foreach from=$provinces key=k item=row}
                                            <option value="{$row.provinceid|escape:'htmlall':'UTF-8'}">{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="user-input text">
                                    <label for="city_invoice">{l s='Quận/huyện'} <span class="required">*</span>:</label>
                                    <select name="city_invoice" id="city_invoice">
                                        <option value="">-</option>
                                        {foreach from=$districts key=k item=row}
                                            <option value="{$row.districtid|escape:'htmlall':'UTF-8'}">{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                        {/foreach}
                                    </select>
                                </div>
                            {elseif $field_name eq "country"}
                                <input type="text" name="id_country_invoice" id="id_country_invoice" value="222" style="width:1px;height:1px;border:none"/>
                                <div class="user-input select hidden">
                                    <label for="id_country_invoice">{l s='Country'}<span class="required">*</span> :</label>
                                    <select name="id_country_invoice" id="id_country_invoice">
                                        <option value="">-</option>
                                        {foreach from=$countries item=v}
                                        <option value="{$v.id_country}" {if ($sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            {elseif $field_name eq "state"}
                                <div class="user-input id_state_invoice select" style="display:none;">
                                    <label for="id_state_invoice">{l s='State'}</label>
                                    <select name="id_state_invoice" id="id_state_invoice">
                                        <option value="">-</option>
                                    </select>
                                    <sup>*</sup>
                                </div>
                            {/if}
                        {/foreach}
                        <div class="user-input is_customer_param">
                            <label for="other_invoice">{l s='Additional information'}</label>
                            <textarea name="other_invoice" id="other_invoice" cols="26" rows="3"></textarea>
                        </div>
                        <div class="user-input">
                            <label for="phone_invoice">{l s='Phone'} <span class="required">*</span>:</label>
                            <input type="text" class="text validate[required,custom[onlyNumberSp]]" name="phone_invoice" id="phone_invoice" value="" />
                        </div>
                        <div class="user-input is_customer_param">
                            <label for="phone_mobile_invoice">{l s='Mobile Phone'} <span class="required">*</span></label>
                            <input type="text" class="text" name="phone_mobile_invoice" id="phone_mobile_invoice" value="" />
                        </div>
                        <input type="hidden" name="alias_invoice" id="alias_invoice" value="{l s='My Invoice address'}" />
                    </div>                    
             	</div>
          	{/if}            
        {/if} 
        {if $type == 1}
        	<div class="user-input is_customer_param">
                <label for="other">{l s='Additional information'}:</label>
                <textarea name="other" id="other" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{/if}</textarea>
            </div>
            <div class="user-input hidden">
                {l s='You must register at least one phone number'} <span class="required">*</span>
            </div>
            <div class="user-input ">
                <label for="phone">{l s='Phone'} <span class="required">*</span>:</label>
                <input type="text" name="phone" id="phone" class="validate[required,custom[onlyNumberSp]]"  value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
            </div>
            <div class="user-input is_customer_param">
                <label for="phone_mobile">{l s='Mobile Phone'} <span class="required">*</span>:</label>
                <input type="text" name="phone_mobile" id="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{/if}" />
            </div>
            <div  style="visibility: hidden;height: 0px;padding:0;margin:0" class="user-input" id="address_alias">
                <label for="alias">{l s='Assign an address title for future reference'} <span class="required">*</span>:</label>
                <input type="text" class="validate[required]"   name="alias" id="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{l s='My address'}{/if}" />
            </div>        	
        {/if}
        <div class="user-input">
        	<input type="checkbox" name="pagree" id="pagree" value="1" {if isset($smarty.post.pagree) && $smarty.post.pagree == 1}checked="checked"{/if} onclick="showHidePass();" style="border:none;width:auto;margin-left:140px;" />
            <label for="pagree" style="width:auto;padding-left:3px;margin:0;">Tôi đồng ý làm thành viên website kiwi99.com</label>
        </div>
        <div id="hidePass" style="display:{if isset($smarty.post.pagree) && $smarty.post.pagree == 1}block{else}none{/if}">
        	<div class="user-input">
               <label for="passwd">{l s='Mật khẩu'} <span class="required">*</span>:</label>                            
               <input type="password" class="validate[required] minSize[5]" name="passwd" id="passwd" />
               <span class="form_info">{l s='(Tối thiểu 5 ký tự)'}</span>
            </div>
          
            <div class="user-input">
               <label for="passwd-confirm">{l s='Xác nhận mật khẩu'} <span class="required">*</span>:</label>                            
               <input type="password" class="validate[required,equals[passwd]]" name="passwd-confirm" id="passwd-confirm" />
           </div>
        </div>
        <div class="user-input{if $type==1} margin40{else} large margin20{/if}">
            <label class="required" style="display:none;"></label>
                       
            {if $type == 1}
                <input type="hidden" name="email_create" value="1" />
                <input type="hidden" name="is_new_customer" value="1" />
                <input type="hidden" name="create_account" value="1" />                
                <input type="hidden" name="SubmitCreate" value="1" />
                {if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}               
            {else}
            	<input type="hidden" name="alias" id="alias" value="{l s='My address'}" />
            	<input type="hidden" id="is_new_customer" name="is_new_customer" value="0" />
				<input type="hidden" id="opc_id_customer" name="opc_id_customer" value="{if isset($guestInformations) && $guestInformations.id_customer}{$guestInformations.id_customer}{else}0{/if}" />
				<input type="hidden" id="opc_id_address_delivery" name="opc_id_address_delivery" value="{if isset($guestInformations) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />
				<input type="hidden" id="opc_id_address_invoice" name="opc_id_address_invoice" value="{if isset($guestInformations) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />              	
            {/if}
             {if $newsletter}
            <div class="user-input">
               {* <label>{l s='Newsletter'}:</label>*}
                <div>
                    <input type="checkbox" name="newsletter" id="newsletter" value="1" {if isset($smarty.post.newsletter) AND $smarty.post.newsletter == 1} checked="checked"{/if} class="chk" />
                    <label class="chk" for="newsletter">{l s='Đăng ký nhận thông tin từ kiwi99.com'}</label>
                </div>
                <input type="hidden" name="optin" id="optin" value="0"/>
            </div>
        {/if}
            <input type="submit" value="Đăng ký" name="submitAccount" id="submitAccount" value="{l s='Register'}" class="btn" />
        </div>
        {if $type == 2}
        	<div class="user-input" style="float: right;color: green;display: none;" id="opc_account_saved">
            	{l s='Account information saved successfully'}
            </div>
        </div>
        {/if}
    </div>
</div>
<script type="text/javascript" language="javascript">
	$('.box-register-payment').corner();	
	showHidePass = function() {ldelim}	
		if($('#pagree').is(":checked")) 
			$('#hidePass').show();
		else 
			$('#hidePass').hide();
	{rdelim}
	changeProvince = function (intId, strId){ldelim}		
		$.getJSON(
			'{$link->getPageLink("province-ajax.php", true)}?provinceid=' + intId + '&callback=?',		
			function(response) {ldelim}	
				$('#' + strId).html(response['data']);
			{rdelim}
		);
	{rdelim}
</script>


