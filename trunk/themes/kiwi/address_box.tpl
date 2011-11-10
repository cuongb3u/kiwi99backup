    <div class="box-address">
            <div class="address-item ajax-content" {if isset($ajaxload)}style="background-color:white;margin-top:-20px;"{/if}>
                {include file="$tpl_dir./errors.tpl"}                
                <form action="{$link->getPageLink('address.php', true)}" method="post" class="std">                   
                 	<!--h3>{if isset($id_address)}{l s='Your address'}{else}{l s='New address'}{/if}</h3-->
                 	{*
                    <div class="user-input text dni">
                        <label for="dni">{l s='Identification number'} <span class="required">*</span>:</label>
                        <input type="text" class="text validate[required]" name="dni" id="dni" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{else}{if isset($address->dni)}{$address->dni|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                        <span class="form_info">{l s='DNI / NIF / NIE'}</span>
                          
                    </div>
                    *}
                    {if $vat_display == 2}
                        <div id="vat_area">
                    {elseif $vat_display == 1}
                        <div id="vat_area" style="display: none;">
                    {else}
                        <div style="display: none;">
                    {/if}
                        <div id="vat_number">
                            <div class="user-input text">
                                <label for="vat_number">{l s='VAT number'}</label>
                                <input type="text" class="text" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{else}{if isset($address->vat_number)}{$address->vat_number|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                            </div>
                        </div>
                   	</div>
                    {assign var="stateExist" value="false"}
                    {foreach from=$ordered_adr_fields item=field_name}
                        {if $field_name eq 'company'}
                            <div class="user-input is_customer_param">
                            <input type="hidden" name="token" value="{$token}" />
                            <label for="company">{l s='Company'}</label>
                            <input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{if isset($address->company)}{$address->company|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                        </div>
                        {/if}
                        {if $field_name eq 'firstname'}
                        <div class="user-input text">
                            <label for="firstname">{l s='Họ tên'} <span class="required">*</span>:</label>
                            <input type="text" class="validate[required,custom[onlyLetterSp]]" name="firstname" id="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{if isset($address->firstname)}{$address->firstname|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                            
                        </div>
                        {/if}
                        {*
                        {if $field_name eq 'lastname'}
                        <div class="user-input text">
                            <label for="lastname">{l s='Last name'} <span class="required">*</span>:</label>
                            <input type="text" class="validate[required,custom[onlyLetterSp]]"  id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{if isset($address->lastname)}{$address->lastname|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                            
                        </div>
                        {/if}
                        *}
                        {if $field_name eq 'address1'}
                        	<div class="user-input text">
                                <label for="city">{l s='Tỉnh/thành'} <span class="required">*</span>:</label>
                                <select id="city" name="city" onchange="changeProvince(this.value,'district')">
                                    {foreach from=$provinces key=k item=row}
                                        <option value="{$row.provinceid|escape:'htmlall':'UTF-8'}" {if isset($smarty.post.city) && $smarty.post.city == $row.provinceid}selected="selected"{elseif isset($address->city) && $address->city==$row.provinceid}selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                    {/foreach}
                                </select>
                            </div>
                            <div class="user-input text">
                                <label for="distirct">{l s='Quận/huyện'} <span class="required">*</span>:</label>
                                <select id="district" name="district">
                                	{if isset($districts) && $districts|@count}
                                        {foreach from=$districts key=k item=row}
                                            <option value="{$row.districtid|escape:'htmlall':'UTF-8'}" {if isset($smarty.post.city) && $smarty.post.district == $row.districtid}selected="selected"{elseif isset($address->district) && $address->district==$row.districtid}selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                        {/foreach}
                                    {/if}
                                </select>
                            </div>
                            <div class="user-input text">
                                <label for="address1">{l s='Địa chỉ'} <span class="required">*</span>:</label>
                                <input type="text" class="validate[required]"  id="address1" name="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{if isset($address->address1)}{$address->address1|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                                
                            </div>
                        {/if}
                        {if $field_name eq 'address2'}
                        <div class="user-input is_customer_param">
                            <label for="address2">{l s='Address (Line 2)'}</label>
                            <input type="text" id="address2" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{if isset($address->address2)}{$address->address2|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                        </div>
                        {/if}
                        {if $field_name eq 'postcode'}
                         <input type="hidden" id="postcode" name="postcode" value="0" />
                        <div class="user-input postcode text hidden">
                            <label for="postcode">{l s='Zip / Postal Code'} <span class="required">*</span>:</label>
                            <input type="text" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{if isset($address->postcode)}{$address->postcode|escape:'htmlall':'UTF-8'}{/if}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
                            
                        </div>
                        {/if}
                        {if $field_name eq 'city'}
                        
                        <!--
                            if customer hasn't update his layout address, country has to be verified
                            but it's deprecated
                        -->
                        {/if}
                        {if $field_name eq 'Country:name' || $field_name eq 'country'}
                        <input type="text" name="id_country" id="id_country" value="222" class="hidden"/>
                        <div class="user-input select hidden">
                            <label for="id_country">{l s='Country'} <span class="required">*</span>:</label>
                            <select id="id_country" name="id_country">{$countries_list}</select>
                            
                        </div>
                        {if $vatnumber_ajax_call}
                        <script type="text/javascript">
                        var ajaxurl = '{$ajaxurl}';
                        {literal}
                                $(document).ready(function(){
                                    $('#id_country').change(function() {
                                        $.ajax({
                                            type: "GET",
                                            url: ajaxurl+"vatnumber/ajax.php?id_country="+$('#id_country').val(),
                                            success: function(isApplicable){
                                                if(isApplicable == "1")
                                                {
                                                    $('#vat_area').show();
                                                    $('#vat_number').show();
                                                }
                                                else
                                                {
                                                    $('#vat_area').hide();
                                                }
                                            }
                                        });
                                    });
                
                                });
                        {/literal}
                        </script>
                        {/if}
                        {/if}
                        {if $field_name eq 'State:name'}
                            {assign var="stateExist" value="true"}
                            <div class="user-input id_state select hidden">
                                <label for="id_state">{l s='State'} <span class="required">*</span>:</label>
                                <select name="id_state" id="id_state">
                                    <option value="">-</option>
                                </select>
                                
                            </div>
                        {/if}
                        {/foreach}
                        {if $stateExist eq "false"}
                        <div class="user-input id_state select hidden">
                            <label for="id_state">{l s='State'} <span class="required">*</span>:</label>
                            <select name="id_state" id="id_state">
                                <option value="">-</option>
                            </select>
                            
                        </div>
                        {/if}
                        <div class="user-input textarea is_customer_param">
                            <label for="other">{l s='Additional information'}</label>
                            <textarea id="other" name="other" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{else}{if isset($address->other)}{$address->other|escape:'htmlall':'UTF-8'}{/if}{/if}</textarea>
                        </div>
                        <div class="user-input hidden">
                        	<p>{l s='You must register at least one phone number'} <sup style="color:red;">*</sup></p>
                        </div>
                        <div class="user-input text">
                            <label for="phone">{l s='Phone'} <span class="required">*</span>:</label>
                            <input type="text"  class="validate[required]"  id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{if isset($address->phone)}{$address->phone|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                        </div>
                        <div class="user-input text  is_customer_param">
                            <label for="phone_mobile">{l s='Mobile Phone'}</label>
                            <input type="text" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{if isset($address->phone_mobile)}{$address->phone_mobile|escape:'htmlall':'UTF-8'}{/if}{/if}" />
                        </div>
                        <div class="user-input text" id="address_alias">
                            <label for="alias">{l s='Tên đại diện'}</label>
                            <input type="text" id="alias"  class="validate[required]"  name="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{if isset($address->alias)}{$address->alias|escape:'htmlall':'UTF-8'}{/if}{if isset($select_address)}{else}{l s='My address'}{/if}{/if}" />
                            
                        </div>
                        <div class="user-input margin20">                        	
                            {if isset($id_address)}<input type="hidden" name="id_address" value="{$id_address|intval}" />{/if}
                            {if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if}
                            {if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
                            {if isset($select_address)}<input type="hidden" name="select_address" value="{$select_address|intval}" />{/if}
                            <input type="submit" value="Cập nhật" name="submitAddress" id="submitAddress" value="{l s='Save'}" class="btn" />
                        </div>
                        <div class="user-input"><em>(*) Các mục bắt buộc phải nhập</em></div>
                </form>
            </div>
        </div>
<script type="text/javascript" language="javascript">
	changeProvince = function (intId, strId){ldelim}		
		$.getJSON(
			'{$link->getPageLink("province-ajax.php", true)}?provinceid=' + intId + '&callback=?',		
			function(response) {ldelim}	
				$('#' + strId).html(response['data']);
			{rdelim}
		);
	{rdelim}
</script>
