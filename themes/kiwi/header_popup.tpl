{*
Header for popup
*}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<meta name="generator" content="Wiki99" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
		<link href="http://yupplease.com/themes/kiwi/css/popup_register_form.css" rel="stylesheet" type="text/css" />
		<link href="http://yupplease.com/themes/kiwi/css/validation.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="http://yupplease.com/themes/kiwi/js/jquery/jquery-1.6.1.js">
		</script>
		<script type="text/javascript" src="http://yupplease.com/js/jquery.validate.min.js">
		</script>
		<script type="text/javascript" src="http://yupplease.com/js/validation-en.js">
		</script>
	</head>
	
	<body>
		<div id="page">
			<!-- Header -->
			<div id="header">
				<a id="header_logo" href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
					<img class="logo" src="{$img_ps_dir}logo.jpg?" alt="{$shop_name|escape:'htmlall':'UTF-8'}"/>
				</a>
			</div>

