{*
 login using OAUTH

*}


{include file="`$tpl_dir`errors.tpl"}
{*
{$userFB|@print_r} 
{$profileYH|@print_r}
*}


{literal}
<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		$('input').blur(function(){
			$(this).validationEngine('validateField', '#' + $(this).attr('id'));
		})
		$('input').click(function(){
			$(this).validationEngine('hide', '#' + $(this).attr('id'));
		})
		var month = [ '2', '4', '6', '9','11' ];
		$('#months').change(function() {
			value=$(this).val();
			
			if ($.inArray(value,month) != -1){
				if ($('#days option[value=31]').attr('selected')==1) {
					$('#days option[value=31]').removeAttr('selected');
					$('#days option[value=30]').attr('selected','selected');
					
				}
				$('#days option[value=31]').attr('disabled', 'disabled');
				if (value==2) {
						$('#days option[value=30]').attr('disabled', 'disabled');
						$('#days option[value=29]').attr('disabled', 'disabled');
					}
					else{
						$('#days option[value=30]').removeAttr('disabled');
						$('#days option[value=29]').removeAttr('disabled');
					}
				if((($('#years').val()%4==0&&$('#years').val()%100!=0)||($('#years').val()%400==0))&&value==2)
					{
					$('#days option[value=29]').removeAttr('disabled');
		
					}
				
				
			}
			else{
				$('#days option[value=29]').removeAttr('disabled');
				
				$('#days option[value=30]').removeAttr('disabled');
				$('#days option[value=31]').removeAttr('disabled');
			}
		});
		$('#years').change(function() {
			if((($('#years').val()%4==0&&$('#years').val()%100!=0)||($('#years').val()%400==0))&&($('#months').val()==2))
					{
						
						$('#days option[value=29]').removeAttr('disabled');
					}
					else{
						$('#days option[value=29]').attr('disabled', 'disabled');
					}
					
		});
		
	})
</script>	
{/literal}

<form action="http://kiwi99.com/en/providers.php" method="post" id="account-creation_form" class="std">
<div class="box-register">                	  
	<div class="reg-item">  	
		<div class="header-title-sub line"><h4 id="new_account_title">{l s='Your personal information'}</h4></div>
		
		<div class="user-input">
            <label>{l s='Title'} <span class="required"></span>:</label>
            <div>
               <input type="radio" selected="selected" name="id_gender" id="id_gender1" value="1" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == 1}checked="checked"{/if} class="radio"/>
				<label for="id_gender1" class="radio">{l s='Mr.'}</label>
            </div>
            <div>             
				 <input type="radio" selected="selected" name="id_gender" id="id_gender1" value="1" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == 2}checked="checked"{/if} class="radio"/>
				<label for="id_gender2" class="radio">{l s='Ms.'}</label>
            </div>
        </div> 
        
        <div class="user-input">
            <label for="customer_firstname">{l s='Tên'} <span class="required">*</span>:</label>
            <input class="validate[required,custom[onlyLetterSp]]" type="text" id="customer_firstname" name="customer_firstname" value="{if isset($smarty.post.customer_firstname)}{$smarty.post.customer_firstname}{/if}" />
        </div>    
		
		<div class="user-input">
            <label for="customer_lastname">{l s='Họ'} <span class="required">*</span>:</label>
            <input class="validate[required,custom[onlyLetterSp]]"  type="text" id="customer_lastname" name="customer_lastname" value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname}{/if}" />
    	</div>    
		
		<div class="user-input">
            <label for="email">{l s='E-mail'} <span class="required">*</span>:</label>
            <input type="text" id="email" class="validate[required,custom[email]] " name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" />
            <!--<span id="error_email" >E-mail đã được đăng ký.</span>
             <span id="success_email">E-mail chưa được đăng ký.</span>-->
        </div>
        
        
       <div class="user-input">
            <label for="passwd">{l s='Password'} <span class="required">*</span>:</label>                            
            <input type="password" class="validate[required] minSize[5]" name="passwd" id="passwd" />
            <span class="form_info">{l s='(5 characters min.)'}</span>
        </div>
                      
        <div class="user-input">
            <label for="passwd-confirm">{l s='Confirm Password'} <span class="required">*</span>:</label>                            
        	<input type="password" class="validate[required,equals[passwd]]" name="passwd-confirm" id="passwd-confirm" />
        </div>  
                       
		
		<div class="user-input">
            <label for="days">{l s='Date of Birth'} <span class="required">*</span>:</label>
                           
            <select id="days" name="days" class="third">
                {* <option value="">-</option>*}
                {foreach from=$days item=day}
            
                    <option value="{$day|escape:'htmlall':'UTF-8'}" {if ($sl_day == $day)} selected="selected"{/if}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>                  
                {/foreach}
            </select>                
            <select id="months" name="months" class="third">
                {*<option value="">-</option>*}
                {foreach from=$months key=k item=month}
                              
                    <option value="{$k|escape:'htmlall':'UTF-8'}" {if ($sl_month == $k)} selected="selected"{/if}>{l s="$month"}&nbsp;</option>
                                  
             	{/foreach}
             </select>
            <select id="years" name="years" class="third">
                {* <option value="">-</option>*}
                {foreach from=$years item=year}
                               
                    <option value="{$year|escape:'htmlall':'UTF-8'}" {if ($sl_year == $year)} selected="selected"{/if}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
                                  
                {/foreach}
            </select>                            
        </div>
		<div class="user-input">
            <label for="address1">{l s='Address'} <span class="required">*</span>:</label>
            <input type="text" name="address1" id="address1" class="validate[required]" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{/if}" />
            <span class="inline-infos">{l s='Street address, P.O. box, compagny name, c/o'}</span>
        </div>

	<input type="hidden" name="postcode" id="postcode" value="0" />	

        <div class="user-input">
            <label for="city">{l s='City'} <span class="required">*</span>:</label>
                <select id="city" name="city" onchange="changeProvince(this.value,'district');">
                                <option value="">-</option>
                                {foreach from=$provinces key=k item=row}
                                	<option value="{$row.provinceid|escape:'htmlall':'UTF-8'}" {if (isset($smarty.post.city) && $smarty.post.city == $row.provinceid ) OR $row.provinceid|escape:'htmlall':'UTF-8'	==	'1'} selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                {/foreach}
                                </select> 
        </div>
<input type="hidden" name="id_country" id="id_country" value="222" class="hidden"/>
		<div class="user-input text">
            <label for="district">{l s='Quận/huyện'}<span class="required">*</span> :</label>
                <select id="district" name="district">
                    	 <option value="">-</option>
                    	 {$districts|var_dump}
                                {foreach from=$districts key=k item=row}
                                    <option value="{$row.districtid|escape:'htmlall':'UTF-8'}" {if (isset($guestInformations.district) && $guestInformations.district == $row.districtid) || $row.districtid	==1} selected="selected"{/if}>{$row.name|escape:'htmlall':'UTF-8'}</option>                                    
                                {/foreach}
                        </select>   
		</div>



        <div class="user-input ">
            <label for="phone">{l s='Phone'} <span class="required">*</span>:</label>
            <input type="text" name="phone" id="phone" class="validate[required,custom[phone]]"  value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
        </div>
	<div  style="visibility: hidden;height: 0px;padding:0;margin:0" class="user-input" id="address_alias">
                <label for="alias">{l s='Assign an address title for future reference'} <span class="required">*</span>:</label>
                <input type="text" class="validate[required]"   name="alias" id="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{l s='My address'}{/if}" />
            </div> 
		<div class="user-input margin40">
			<label class="required" style="display:none;"></label>
			<input type="hidden" name="email_create" value="1" />
            <input type="hidden" name="is_new_customer" value="1" />
        	<input type="hidden" name="create_account" value="1" />                
            <input type="hidden" name="SubmitCreate" value="1" />
            {if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
                
			{if $newsletter}
	            <div class="user-input">
	               {* <label>{l s='Newsletter'}:</label>*}
	                <div>
	                    <input type="checkbox" name="newsletter" id="newsletter" value="1" {if isset($smarty.post.newsletter) AND $smarty.post.newsletter == 1} checked="checked"{/if} class="chk" />
	                    <label class="chk" for="newsletter">{l s='Sign up for our newsletter'}</label>
	                </div>
	                <input type="hidden" name="optin" id="optin" value="0"/>
	            </div>
	        {/if}
	        
	        <input type="submit" src="{$smarty.const._THEME_DIR_}images/btn/register.gif" name="submitAccount" id="submitAccount" value="{l s='Register'}" class="btn" />
		</div>
	</div>
</div>
</form>

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







