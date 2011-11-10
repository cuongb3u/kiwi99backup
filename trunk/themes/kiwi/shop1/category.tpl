
<script>
	var id_category = {$id_category};
	{if isset($is_search) && $is_search}
		var is_search = {$is_search};
	{else}
		var is_search = 0;
	{/if}	
	var search_query = '{$search_query}';
</script>

 {include file="$tpl_dir./shop$shop/breadcrumb.tpl"}   
 <!-- MAIN CONTENT WEBSITE -->
<div class="main-content">
    <!-- LEFT MENU -->
     {include file="$tpl_dir./shop$shop/leftmenu.tpl"}
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
            {include file="$tpl_dir./shop$shop/filter.tpl"}
            <!-- END FILTER -->
                    
             {include file="$tpl_dir./shop$shop/pagination.tpl"}
            <div class="items" id="product-list">
            		{if $products}
                	<ul id="productList">
                        {include file="$tpl_dir./shop$shop/product-list.tpl" products=$products}
                	</ul>
               	{else}
                	<ul id="productList"><li>{l s='NO DATA'}</li></ul>
               	{/if}               	
               	<div id="ajax-loading">
               		<div id="blur-ajax-loading">
               			</div>
               		<img src="../images/ajaxloading.gif">
               		
           		</div>               	
            </div>
            {include file="$tpl_dir./shop$shop/pagination.tpl"}

        </div>
    </div>
    <!-- END DATA -->
</div>

            <script>
var idcomments_acct = 'f050d4bb826d3dc78d2cf73494f22359';
var idcomments_post_id;
var idcomments_post_url;
</script>
<span id="IDCommentsPostTitle" style="display:none"></span>
<script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>

<!-- END MAIN CONTENT WEBSITE -->