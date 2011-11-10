{*
* 2007-2011 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
 <!-- MAIN CONTENT WEBSITE -->
<div class="main-content">
    <!-- LEFT MENU -->
     {include file="$tpl_dir./leftmenu.tpl"}
    <!-- END LEFT MENU -->
    <!-- DATA -->
    <div class="data">
    	<div class="box-product">
        	{capture name=path}{l s='Search'}{/capture}
        	<!-- FILTER -->
            {include file="$tpl_dir./filter.tpl"}
            <!-- END FILTER -->
            {include file="$tpl_dir./breadcrumb.tpl"}   
            <div class="header-title">
            	<h3 {if isset($instantSearch) && $instantSearch}id="instant_search_results"{/if}>
                    {l s='Search'}&nbsp;{if $nbProducts > 0}"{if isset($search_query) && $search_query}{$search_query|escape:'htmlall':'UTF-8'}{elseif $search_tag}{$search_tag|escape:'htmlall':'UTF-8'}{elseif $ref}{$ref|escape:'htmlall':'UTF-8'}{/if}"{/if}
                    {if isset($instantSearch) && $instantSearch}<a href="#" class="close">{l s='Return to previous page'}</a>{/if}
                </h3>  
            </div> 
            {include file="$tpl_dir./errors.tpl"}
            {if !$nbProducts}
                <div class="items" id="product-list"> 
                    <p class="warning">
                        {if isset($search_query) && $search_query}
                            {l s='No results found for your search'}&nbsp;"{if isset($search_query)}{$search_query|escape:'htmlall':'UTF-8'}{/if}"
                        {elseif isset($search_tag) && $search_tag}
                            {l s='No results found for your search'}&nbsp;"{$search_tag|escape:'htmlall':'UTF-8'}"
                        {else}
                            {l s='Please type a search keyword'}
                        {/if}
                    </p>
                </div>
            {else}
                <div class="items" id="product-list"> 
                	<div class="sort">
                    	<h4><span class="big">{$nbProducts|intval}</span>&nbsp;{if $nbProducts == 1}{l s='result has been found.'}{else}{l s='results have been found.'}{/if}</h4>
                    	{if !isset($instantSearch) || (isset($instantSearch) && !$instantSearch)}{include file="$tpl_dir./product-sort.tpl"}{/if}
                    </div>
                    <ul>{include file="$tpl_dir./product-list.tpl" products=$search_products}</ul>
                </div>
                {if !isset($instantSearch) || (isset($instantSearch) && !$instantSearch)}{include file="$tpl_dir./pagination.tpl"}{/if}
            {/if}
   		</div>
    </div>
</div>
