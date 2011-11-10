
<script>
	var id_category = {$id_category};
	{if isset($is_search) && $is_search}
		var is_search = {$is_search};
	{else}
		var is_search = 0;
	{/if}	
	var search_query = '{$search_query}';
</script>

 {include file="$tpl_dir./breadcrumb.tpl"}   
 <!-- MAIN CONTENT WEBSITE -->
<div class="main-content">
    <!-- LEFT MENU -->
     {include file="$tpl_dir./leftmenu.tpl"}
    <!-- END LEFT MENU -->

    <!-- DATA -->
    <div class="data">
        <div class="box-adv">
        {if $category->id_image}
			<div class="align_center">
				<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" width="{$categorySize.width}" height="{$categorySize.height}" />
			</div>
		{/if}
        </div>
        <div class="box-product">
        
        
            <!-- FILTER -->
            {include file="$tpl_dir./filter.tpl"}
            <!-- END FILTER -->
                    
             {include file="$tpl_dir./pagination.tpl"}
            <div class="items" id="product-list">
            		{if $products}
                	<ul id="productList">
                        {include file="$tpl_dir./product-list.tpl" products=$products}
                	</ul>
               	{else}
                	<div class="not-content">{l s='NO DATA'}</div>
               	{/if}               	
               	<div id="ajax-loading"><img src="img/ajaxloader.gif"></div>               	
            </div>
            {include file="$tpl_dir./pagination.tpl"}

        </div>
    </div>
    <!-- END DATA -->
</div>
<!-- END MAIN CONTENT WEBSITE -->