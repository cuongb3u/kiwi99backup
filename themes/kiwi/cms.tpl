{if !isset($ajaxload)}

<div class="main-content">	
	{include file="$tpl_dir./leftmenu-content.tpl"}   
	<div class="data">
		{/if}
        {if isset($cms) && $cms->id != $cgv_id}
            {include file="$tpl_dir./breadcrumb.tpl"}
        {/if}
        <div class="box-cms">
            <div class="header-title">
            	<h3>
                    {if isset($cms) && !isset($category)}            
                    	{if !$cms->active} 
                            {l s='This CMS page is not visible to your customers.'}
                            <input type="hidden" id="admin-action-cms-id" value="{$cms->id}" />
                            <input type="submit" value="{l s='Publish'}" class="exclusive" onclick="submitPublishCMS('{$base_dir}{$smarty.get.ad}', 0)"/>			
                            <input type="submit" value="{l s='Back'}" class="exclusive" onclick="submitPublishCMS('{$base_dir}{$smarty.get.ad}', 1)"/>
                        {else}
                        	{$cms->meta_title|escape:'htmlall':'UTF-8'}
                        {/if}
                    {elseif isset($category)}
                    	{$category->name|escape:'htmlall':'UTF-8'}
                    {/if}
               	</h3>
            </div>
            <div class="item">
            	{if isset($cms) && !isset($category)}
                	{if $cms->active}                    	
                		{$cms->content}
                    {/if}
              	{elseif isset($category)}                   
                    {if isset($sub_category) & !empty($sub_category)}	
                        <h4>{l s='List of sub categories in '}{$category->name}{l s=':'}</h4>
                        <ul class="bullet">
                            {foreach from=$sub_category item=subcategory}
                                <li>
                                    <a href="{$link->getCMSCategoryLink($subcategory.id_cms_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                    {if isset($cms_pages) & !empty($cms_pages)}
                    	<h4>{l s='List of pages in '}{$category->name}{l s=':'}</h4>
                        <ul class="bullet">
                            {foreach from=$cms_pages item=cmspages}
                                <li>
                                    <a href="{$link->getCMSLink($cmspages.id_cms, $cmspages.link_rewrite)|escape:'htmlall':'UTF-8'}">{$cmspages.meta_title|escape:'htmlall':'UTF-8'}</a>
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                {else}
                    {l s='This page does not exist.'}
                {/if}
           	</div>
            {if isset($cms)}
            	{if $cms->id == 4 || 1}
                   {include file="$tpl_dir./box/need-help.tpl"}  
                {/if}
            {/if}  
        </div>
    {if !isset($ajaxload)}    
  	</div>
</div>
{/if}