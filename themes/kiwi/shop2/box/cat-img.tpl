       <div id="catimginner">
        {if file_exists("{$smarty.const._PS_ROOT_DIR_}/images/cat-imgs/{$category->id_category}.jpg")}
			<div class="align_center">
				<img src="images/cat-imgs/{$category->id_category}.jpg" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" width="{$categorySize.width}" height="{$categorySize.height}" />
			</div>
			{else}
				<div class="align_center">
				<img src="../img/Clothes.jpg" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" width="{$categorySize.width}" height="{$categorySize.height}" />
			</div>
		{/if}
		</div>
       