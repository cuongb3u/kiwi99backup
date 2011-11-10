<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
{if isset($have_image)}
		<meta property="og:title" content="{$product->name}"/>
		<meta property="og:image" content="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" />
		<meta property="og:description" content="kiwi99 is a new fashion shop in VietNam"/>
{/if}
		<link rel="icon" type="image/vnd.microsoft.icon" href="http://kiwi99.com/img/favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="http://kiwi99.com/img/favicon.ico" />
		<script type="text/javascript">
			var baseDir = '{$content_dir}';
			var static_token = '{$static_token}';
			var token = '{$token}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
			var priceDisplayMethod = {$priceDisplay};
			var roundMode = {$roundMode};
		</script>
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}
{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}
{$HOOK_HEADER}