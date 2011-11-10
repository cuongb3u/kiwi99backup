<div class="need-help">
    <ul>
       <li class="first">
            <h3>need help?</h3>
            <div class="hot-line">
                <span class="number">+46 123 456 789</span>
                <span class="note">(Mon-Fri 08:00-12:00)</span>
            </div>
        </li>
        <li class="accordionButton">
            <h3><a href="#" id="aNeedHelp2" >return policy</a></h3>
            <div id="idNeedHelp2" class="accordionContent" >
                <div class="return-policy">
                    <p>Uncertain about a size or fit. Dont worry - we have a 30 days full right of return policy. If you are completely satisfield with your purchase, simple return the item/s to us in their original condition and we will issue a refund.</p>
                    <p><a href="#"><strong>Click here</strong></a> to out view full return policy</p>
                </div>
            </div>
        </li>
        <li class="accordionButton">
            <h3><a href="#" id="aNeedHelp3" >delivery</a></h3>
            <div id="idNeedHelp3" class="accordionContent">
                <div class="delivery">
                    <p>For Denmark, Finland or Sweden out standard delivery time is 2-4 working day. For countries outside of Scandinavia delivery time is 3-5 working days.</p>
                    <p><a href="#"><strong>Click here</strong></a> for a complete list of countries that we ship to</p>
                </div>
            </div>
        </li>
        <li class="accordionButton">
            <h3><a href="#" id="aNeedHelp4">part payment</a></h3>
            <div id="idNeedHelp4" class="accordionContent">
                <div class="part-payment">
                    <p>With part payment you have the option to pay per rate for up to 18 months. You decide what works best for you.</p>
                    <p><a href="#"><strong>Click here</strong></a> to read details.</p>
                    <p>Calculator below how much you want to pay now.</p>
                </div>
                <div class="user-input">
                    <input />
                </div>
                <div class="user-input">
                    <label>period of payment</label>
                    <select>
                        <option>select</option>
                    </select>
                    <input type="image" src="{$smarty.const._THEME_DIR_}images/btn/calculate.gif" class="btn" />
                </div>
            </div>
        </li>
        <li class="accordionButton">
            <h3><a href="#" id="aNeedHelp5">order summary</a></h3>
            <div id="idNeedHelp5" style="display:none;">
                
            </div>
        </li>         
        <li class="on-help">
            <h3 ><a href="" id="aNeedHelp1" >{l s='Customer Service'} - {if isset($customerThread) && $customerThread}{l s='Your reply'}{else}{l s='Contact us'}{/if}</a></h3>
            <div id="idNeedHelp1">
                {if isset($confirmation)}
                    <p>{l s='Your message has been successfully sent to our team.'}</p>
                    <ul class="footer_links">
                        <li><a href="{$base_dir}"><img class="icon" alt="" src="{$img_dir}icon/home.gif"/></a><a href="{$base_dir}">{l s='Home'}</a></li>
                    </ul>
                {elseif isset($alreadySent)}
                    <p>{l s='Your message has already been sent.'}</p>
                    <ul class="footer_links">
                        <li><a href="{$base_dir}"><img class="icon" alt="" src="{$img_dir}icon/home.gif"/></a><a href="{$base_dir}">{l s='Home'}</a></li>
                    </ul>
                {else}
                    <p class="bold">{l s='For questions about an order or for more information about our products'}.</p>
                    {include file="$tpl_dir./errors.tpl"}
                    <form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
                        <fieldset>
                            <h3>{l s='Send a message'}</h3>
                            <p class="select">
                                <label for="id_contact">{l s='Subject Heading'}</label>
                            	{if isset($customerThread.id_contact) && isset($contacts)}
                                    {foreach from=$contacts item=contact}
                                        {if $contact.id_contact == $customerThread.id_contact}
                                            <input type="text" id="contact_name" name="contact_name" value="{if isset($contact.name)}{$contact.name|escape:'htmlall':'UTF-8'}{/if}" readonly="readonly" />
                                            <input type="hidden" name="id_contact" value="{if isset($contact.id_contact)}{$contact.id_contact}{/if}" />
                                        {/if}
                                    {/foreach}
                            	</p>
                            {else}
                                    <select id="id_contact" name="id_contact" onchange="showElemFromSelect('id_contact', 'desc_contact')">
                                        <option value="0">{l s='-- Choose --'}</option>
                                        {if isset($contacts)}
                                            {foreach from=$contacts item=contact}
                                                <option value="{$contact.id_contact|intval}" {if isset($smarty.post.id_contact) && $smarty.post.id_contact == $contact.id_contact}selected="selected"{/if}>{$contact.name|escape:'htmlall':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </p>
                        {*	<p id="desc_contact0" class="desc_contact">&nbsp;</p>
                        *}
                        		{if isset($contacts)}
                                    {foreach from=$contacts item=contact}
                                        <p id="desc_contact{$contact.id_contact|intval}" class="desc_contact" style="display:none;">
                                            <label>&nbsp;</label>{$contact.description|escape:'htmlall':'UTF-8'}
                                        </p>
                                    {/foreach}
                                {/if}
                            {/if}
                            <p class="text">
                                <label for="email">{l s='E-mail address'}</label>
                                {if isset($customerThread.email)}
                                    <input type="text" id="email" name="from" value="{if isset($customerThread.email)}{$customerThread.email}{/if}" readonly="readonly" />
                                {else}
                                    <input type="text" id="email" name="from" value="{if isset($email)}{$email}{/if}" />
                                {/if}
                            </p>
                        {if !$PS_CATALOG_MODE}
                            {if ((!isset($customerThread.id_order) || $customerThread.id_order > 0) && isset($customerThread) )}
                            <p class="text">
                                <label for="id_order">{l s='Order ID'}</label>
                                {if !isset($customerThread.id_order) && isset($isLogged) && $isLogged == 1}
                                    <select name="id_order" ><option value="0">{l s='-- Choose --'}</option>{$orderList}</select>
                                {elseif !isset($customerThread.id_order) && !isset($isLogged)}
                                    <input type="text" name="id_order" id="id_order" value="{if isset($customerThread.id_order) && $customerThread.id_order > 0}{$customerThread.id_order|intval}{else}{if isset($smarty.post.id_order)}{$smarty.post.id_order|intval}{/if}{/if}" />
                                {elseif $customerThread.id_order > 0}
                                    <input type="text" name="id_order" id="id_order" value="{if isset($customerThread.id_order)}{$customerThread.id_order|intval}{/if}" readonly="readonly" />
                                {/if}
                            </p>
                            {/if}
                            {if isset($isLogged) && $isLogged}
                            <p class="text">
                            <label for="id_product" >{l s='Product'}</label>
                                {if !isset($customerThread.id_product)}
                                    <select id="select_product" name="id_product" width="500">
                                    	 <option value="0" >{l s='-- Choose --'}</option>
                                    	 {$count_block=0}
                                    	{foreach from=$products_order item=product name=products_order}
                                  	 
                                    	 <option value="{$product.product_id}" block="{$count_block++}" >{$product.name}</option>
                                    {/foreach}
                                    </select>
                                    <div id="product_help">
                                    	{$count_block=0}
                                    	{foreach from=$products_order item=product name=products_order}
                                    	<div class="block_product" block_product="{$count_block++}">
                                    		<a href="{$link->getProductLink($product.product_id, $product.link_rewrite,$product.id_category_default)}" class="product_help_name">{$product.name}</a>	
                                    	<a href="{$link->getProductLink($product.product_id, $product.link_rewrite,$product.id_category_default)}" alt="{$product.name}">
                                    	<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium')}"></a>
                                    	<div class="product_help_attribute">
                                    		
                                    		<ul class="product_detail">
                                    		<li>Giá : {displayWtPrice p="`$product.total_wt`"}</li>
                                    		{foreach from=$product.attrs  item=attr}
                                    		<li>{$attr}</li>
                                    		{/foreach}
                                    		</ul>
                                    	</div>
                                    	
                                    	</div>
                                    	{/foreach}
                                    </div>
                                {elseif $customerThread.id_product > 0}
                                    <input type="text" name="id_product" id="id_product" value="{if isset($customerThread.id_product)}{$customerThread.id_product|intval}{/if}" readonly="readonly" />
                                {/if}
                            </p>
                            {/if}
                        {/if}
                        {if isset($fileupload) && $fileupload == 1 && 0}
                            <p class="text">
                            <label for="fileUpload">{l s='Attach File'}</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                                <input type="file" name="fileUpload" id="fileUpload" />
                            </p>
                        {/if}
                        <p class="textarea">
                            <label for="message">{l s='Message'}</label>
                             <textarea id="message" name="message" rows="15" cols="20" style="width:190px;height:120px">{if isset($message)}{$message|escape:'htmlall':'UTF-8'|stripslashes}{/if}</textarea>
                        </p>
                        <p class="submit">
                            <input type="submit" name="submitMessage" id="submitMessage" value="{l s='Send'}" class="button_large" onclick="$(this).hide();" />
                        </p>
                    </fieldset>
                </form>
                {/if}
                                            
            </div>
        </li >
    </ul>
</div>