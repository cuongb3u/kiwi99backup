
<div class="sorthitscontainer">
	                
	                



  

<div class="sortby">
	<form action="javascript:void(0)">
		<fieldset>
			<label>Sort By:</label>
			<select>
				
	            <option selected="selected" value="date_add:desc" >newest</option>
	            <option value="orderprice:desc" >price high-low</option>
	            <option value="orderprice:asc" >price low-high</option>
	            <option value="name:asc" >name A - Z</option>
	            <option value="name:desc" >name Z - A</option>
	            <option value="quantity:desc" >in stock first</option>

				
			</select>
		</fieldset>
	</form>
</div><!-- END: sortby -->

	       	
	       {if isset($p) AND $p}
			{if isset($smarty.get.id_category) && $smarty.get.id_category && isset($category)}
				{assign var='requestPage' value=$link->getPaginationLink('category', $category, false, false, true, false)}
				{assign var='requestNb' value=$link->getPaginationLink('category', $category, true, false, false, true)}
			{elseif isset($smarty.get.id_manufacturer) && $smarty.get.id_manufacturer && isset($manufacturer)}
				{assign var='requestPage' value=$link->getPaginationLink('manufacturer', $manufacturer, false, false, true, false)}
				{assign var='requestNb' value=$link->getPaginationLink('manufacturer', $manufacturer, true, false, false, true)}
			{elseif isset($smarty.get.id_supplier) && $smarty.get.id_supplier && isset($supplier)}
				{assign var='requestPage' value=$link->getPaginationLink('supplier', $supplier, false, false, true, false)}
				{assign var='requestNb' value=$link->getPaginationLink('supplier', $supplier, true, false, false, true)}
			{else}
				{assign var='requestPage' value=$link->getPaginationLink(false, false, false, false, true, false)}
				{assign var='requestNb' value=$link->getPaginationLink(false, false, true, false, false, true)}
			{/if}


	
	<div class="itemsperpage">fdsaf
		{if $nb_products > 0 || 1}
			<form action="{if !is_array($requestNb)}{$requestNb}{else}{$requestNb.requestUrl}{/if}" method="get" class="pagination">
				<p>
					{if isset($search_query) AND $search_query}<input type="hidden" name="search_query" value="{$search_query|escape:'htmlall':'UTF-8'}" />{/if}
					{if isset($tag) AND $tag AND !is_array($tag)}<input type="hidden" name="tag" value="{$tag|escape:'htmlall':'UTF-8'}" />{/if}
					<label for="nb_item">{l s='items:'}</label>
					<select name="n" id="nb_item">
					{assign var="lastnValue" value="0"}
					{foreach from=$nArray item=nValue}
						{if $lastnValue <= $nb_products}
							<option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $n == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
						{/if}
						{assign var="lastnValue" value=$nValue}
					{/foreach}
					</select>
					{if is_array($requestNb)}
						{foreach from=$requestNb item=requestValue key=requestKey}
							{if $requestKey != 'requestUrl'}
								<input type="hidden" name="{$requestKey}" value="{$requestValue}" />
							{/if}
						{/foreach}
					{/if}
				</p>
			</form>
		{/if}
	</div><!-- END: itemsperpage -->
		{/if}	
	
	<div class="layout-slider" style="float:left; margin-left: 110px; width:320px;">

	  <input id="Slider2" type="slider" name="price" value="{$min_slider};{$max_slider}" />
	</div>
<!-- END: resultshits -->

	                <div class="clear"><!-- FLOAT CLEAR --></div>
	            </div>

