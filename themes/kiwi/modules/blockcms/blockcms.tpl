<!-- MODULE Block footer -->
<div class="nav-link">
    <ul>
        <li class="first">
        	<p class="title_cms">Kết nối</p>
            <div class="connect-link">                            	
                <div><span class="facebook"><a href="#">join us on facebook</a></span></div>
                <div><span class="twitter"><a href="#">follow us on twitter</a></span></div>
                <div>&nbsp;</div>
            </div>
        </li>
        <li>
        		<p class="title_cms">Giới thiệu</p>
        	<div class="connect-link">
            	<div><a href="{$link->getPageLink('content/4-gioi-thieu-cong-ty')}">Giới thiệu công ty</a></div>
                <div><a href="{$link->getPageLink('content/3-thoa-thuan-su-dung')}">Thỏa thuận sử dụng</a></div>
                <div><a href="{$link->getPageLink('content/5-cam-ket-bao-mat')}">Cam kết bảo mật</a></div>
            </div>
        </li>
        <li>
        	<p class="title_cms">Mua hàng trưc tuyến</p>
        	<div class="connect-link">
        		<div><a href="{$link->getPageLink('content/6-quy-dinh-khi-hoan-tra')}">Quy định khi hoàn trả</a></div>
                <div><a href="{$link->getPageLink('content/2-phuong-thuc-giao-hang')}">Phương thức giao hàng</a></div>
                <div><a href="{$link->getPageLink('content/1-qui-dinh-khi-chuyen-khoan')}">Hướng dẫn mua hàng</a></div>
            </div>
        </li>
        <li>
        	<p class="title_cms">Thắc mắc</p>
        	<div class="connect-link">
            	<div><a href="{$link->getPageLink('content/7-hoi-dap-thuong-gap')}">Hỏi/đáp thường gặp</a></div>
                <div>&nbsp;</div>
                <!-- <div><span class="copy-right">&copy; Kiwi 2010 All rights reserved</span></div> -->
            </div>
        </li>
        {*
        {assign var=i value=0}
        {foreach from=$cmslinks item=cmslink}        	
        	{if $i%3==0}
            	<li>
                	<div class="connect-link">
            {/if}
            	<div><a href="{$cmslink.link|addslashes}" title="{$cmslink.meta_title|escape:'htmlall':'UTF-8'}">{$cmslink.meta_title|escape:'htmlall':'UTF-8'}</a>{if $i==($cmslinks|@count-1)}<span class="copy-right">&copy; Boozt 2010 All rights reserved</span></div>{/if}</div>
            {if $i%3==2}
            		</div>
             	</li>
            {/if}           
            {math equation="x + y" x=$i y=1 assign='i'}
        {/foreach}
        {if $cmslinks|@count%3 != 0}
            </li>
        {/if}  
        *}
    </ul>      
</div>

<!-- /MODULE Block footer -->
