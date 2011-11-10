<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {include file="$tpl_dir./page_head.tpl"}
      {if isset($have_image)}
		<meta property="og:title" content="{$product->name}"/>
		<meta property="og:image" content="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" />
		<meta property="og:description" content="kiwi99 is a new fashion shop in Viet Nam"/>
		{/if}     
    </head>
    <body {if $page_name}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
    	<div id="overlay"></div>
        {if $page_name == 'order-opc'}
        	<div id="opc_new_account-overlay" class="opc-overlay" style="display: none;"></div>
        {/if}
        <div class="header">
        	<div class="bg_header">
        		</div>
        	<div class="header_wrapper">
            	<div id="mini_basket" class="pu-addtobag">
                    {$HOOK_RIGHT_COLUMN} 
                </div>
            	{include file="$tpl_dir./page_header.tpl"}
            </div>   
    	<div class="wrapper clearfix" id="wrapper"> 
{include file="$tpl_dir./brand_nav.tpl"}    
            <div class="menu">
				<div class="brand-top"><a href="http://kiwi99.com/thoitrang?shop=2" title="LibraModa"></a></div>
				<div class="right-menu">
					{include file="$tpl_dir./search-top.tpl"}  				 
                    {if isset($shop)}
                        {include file="$tpl_dir./shop$shop/topmenu.tpl"}            
                    {else}
                        {include file="$tpl_dir./topmenu.tpl"}
                    {/if}
                      
            	</div>        	
            </div>            
            <div class="content clearfix">
                {$HOOK_MAIN}
            </div>
            <div id="newsletter_block_left" class="block">
					<div class="brand-top">{if isset($shop)}<a href="thoitrang?shop={$shop}" ></a>{else}<img src="../img/{$page_name|escape:'htmlall':'UTF-8'}.png">{/if}</div>
					<div class="contentasset">
							
							<ul>
				    <li><a target="_blank" href="#">LibraModa</a></li>
				    <li><a class="jsBestsellerDialog" href="#">Size guide </a></li>
				    <li><font color="#0000ff"><a href="#">About VILA</a></font></li>
				    <li><a href="#">VILA locator</a></li>
				    <li><font color="#0000ff"><a href="#">One Card</a></font></li>
				
				</ul>
							
			
			
			</div>
		{$HOOK_COMMON_FOOTER}
		</div>
   
            	{include file="$tpl_dir./page_footer.tpl"}	
            </div>
            
        </div>        
        
        <div id="janrainEngageEmbed2"></div>
        
    </body>
    
</html>
