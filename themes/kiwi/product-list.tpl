{if isset($products)}
    {foreach from=$products item=product name=products}
        <li {if $smarty.foreach.products.index % 4 == 0 && $smarty.foreach.products.index gt 0}style="clear:both;"{/if}>
            {include file="$tpl_dir./box/product-item.tpl"}                
       	</li>
   	{/foreach}
{else}
	khong co san pham nao
{/if}
