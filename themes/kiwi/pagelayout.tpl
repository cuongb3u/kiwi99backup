<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {include file="$tpl_dir./page_head.tpl"}
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
            <div class="wrapper" id="wrapper">
          {include file="$tpl_dir./brand_nav.tpl"}    
            <div class="content clearfix">
            	<div class="menu"><div class="brand-top"><img src="../img/{$page_name|escape:'htmlall':'UTF-8'}.png"	alt="My account" /></div></div>
                {$HOOK_MAIN}
            </div>
              <div id="newsletter_block_left" class="block">
					<div class="brand-top">{if isset($shop)}<a href="{$link->getPageLink('index.php')}?shop={$shop}" ></a>{else}<img src="../img/{$page_name|escape:'htmlall':'UTF-8'}.png">{/if}</div>
					<div class="contentasset"><!-- dwMarker="content" dwContentID="bfYtUiaagRtEEaaadUfcAy9CTb" -->
							
							<ul>
				    <li><a target="_blank" href="http://vila.dk">vila.dk</a></li>
				    <li><a class="jsBestsellerDialog" href="http://shop.bestseller.com/on/demandware.store/Sites-ROE-Site/en_GB/Page-Include?cid=vl-size-guide">Size guide </a></li>
				    <li><font color="#0000ff"><a href="http://shop.bestseller.com/vila/about-us/vl-about-us,en_GB,pg.html">About VILA</a></font></li>
				    <li><a href="http://shop.bestseller.com/null/vl-store-locator,en_GB,pg.html">VILA locator</a></li>
				    <li><font color="#0000ff"><a href="http://shop.bestseller.com/null/vl-one-card,en_GB,pg.html">One Card</a></font></li>
				
				</ul>
							
			
			
			</div>
		{$HOOK_COMMON_FOOTER}
		</div>
           
            	{include file="$tpl_dir./page_footer.tpl"}	
            </div>
            
         
  
        
        <div id="janrainEngageEmbed2"></div>
        
    </body>
    
</html>
