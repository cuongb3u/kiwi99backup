<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {include file="$tpl_dir./page_head.tpl"}
    </head>
    <body {if $page_name}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
    	<div class="wrapper" id="wrapper">
        	<div class="header">
            	{include file="$tpl_dir./page_header.tpl"}
            </div>
            <div class="menu">
            	<div class="brand-top"><a href="/name-it" title="name it"></a></div>
				<div class="right-menu">				 
                    {if isset($smarty.get.shop) && $smarty.get.shop == 1}
                        {include file="$tpl_dir./topmenu_leomoda.tpl"}            
                    {else}
                        {include file="$tpl_dir./topmenu.tpl"}
                    {/if}
                    {include file="$tpl_dir./search-top.tpl"}    
            	</div> 
            </div>            
            <div class="content clearfix">
                {capture name=path}{l s='Shipping' mod='cashondelivery'}{/capture}
                {include file="$tpl_dir./breadcrumb.tpl"}             
                <div class="box-bankwire">
                    <div class="header-title"><h3>{l s='Order summary' mod='cashondelivery'}</h3></div>
                    <div class="bankwire-item">
                        {assign var='current_step' value='payment'}
                        {include file="$tpl_dir./order-steps.tpl"}
                        
                        <h3>{l s='Cash on delivery (COD) payment' mod='cashondelivery'} <a href="{$link->getPageLink('order.php', true)}?step=3" class="button_large">{l s='Other payment methods' mod='cashondelivery'}</a></h3>
                        
                        <form action="{$this_path_ssl}validation.php" method="post">
                            <input type="hidden" name="confirm" value="1" />
                            <p>
                                <img src="{$this_path}cashondelivery.jpg" alt="{l s='Cash on delivery (COD) payment' mod='cashondelivery'}" style="float:left; margin: 0px 10px 5px 0px;" />
                                {l s='You have chosen the cash on delivery method.' mod='cashondelivery'}
                                <br/><br />
                                {l s='The total amount of your order is' mod='cashondelivery'}
                                <span id="amount_{$currencies.0.id_currency}" class="price">{convertPrice price=$total}</span>
                                {if $use_taxes == 1}
                                    {l s='(tax incl.)' mod='cashondelivery'}
                                {/if}
                            </p>
                            <p>
                                <br /><br />
                                <br /><br />
                                <b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='cashondelivery'}.</b>
                            </p>
                            <p class="cart_navigation">
                                <input type="submit" name="submit" value="{l s='I confirm my order' mod='cashondelivery'}" class="exclusive_large" />
                            </p>
                        </form>
                    </div>
                </div>
                {include file="$tpl_dir./box/need-help.tpl"} 
            </div>
            <div class="footer">
            	<div class="nav-link">
                    <ul>
                        <li class="first">
                            <div class="connect-link">                            	
                                <div><span class="facebook"><a href="#">join us on facebook</a></span></div>
                                <div><span class="twitter"><a href="#">follow us on twitter</a></span></div>
                                <div>&nbsp;</div>
                            </div>
                        </li>
                        <li>
                            <div class="connect-link">
                                <div><a href="{$link->getPageLink('content/4-gioi-thieu-cong-ty')}">Giới thiệu công ty</a></div>
                                <div><a href="{$link->getPageLink('content/3-thoa-thuan-su-dung')}">Thỏa thuận sử dụng</a></div>
                                <div><a href="{$link->getPageLink('content/5-cam-ket-bao-mat')}">Cam kết bảo mật</a></div>
                            </div>
                        </li>
                        <li>
                            <div class="connect-link">
                                <div><a href="{$link->getPageLink('content/6-quy-dinh-khi-hoan-tra')}">Quy định khi hoàn trả</a></div>
                                <div><a href="{$link->getPageLink('content/2-phuong-thuc-giao-hang')}">Phương thức giao hàng</a></div>
                                <div><a href="{$link->getPageLink('content/1-qui-dinh-khi-chuyen-khoan')}">Hướng dẫn mua hàng</a></div>
                            </div>
                        </li>
                        <li>
                            <div class="connect-link">
                                <div><a href="{$link->getPageLink('content/7-hoi-dap-thuong-gap')}">Hỏi/đáp thường gặp</a></div>
                                <div>&nbsp;</div>
                                <div><span class="copy-right">&copy; Kiwi 2010 All rights reserved</span></div>
                            </div>
                        </li>        
                    </ul>      
                </div>
                <div class="nav-payment-contact">
                    <ul>
                        <li class="first">
                            
                            <span>customer service: emailaddress@mail.com</span>
                        </li>
                        <li>tel: (+848) 909.021.073</li>
                        <li>contact form &gt;</li>
                    </ul>
                </div>
            </div>
        </div>    
        <script type="text/javascript" language="javascript">
        	$('.header-title').corner("top");
			$('.bankwire-item').corner("bottom");	
        </script>   
    </body>
</html>