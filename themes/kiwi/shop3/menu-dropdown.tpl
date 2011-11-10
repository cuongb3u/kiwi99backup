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

<li {if isset($last) && $last == 'true'}class="last"{/if}>
	 <h3><a id_cat="{$node.id}" img_link="{$link->getCatImageLink($node.link_rewrite,$node.id,'category')}" href="{$node.link}" {if isset($currentCategoryId) && ($node.id == $currentCategoryId)}class="selected"{/if} title="{$node.desc|escape:html:'UTF-8'}">{$node.name|escape:html:'UTF-8'}</a></h3>
	 <div class="dropdown-menu">
	{if $node.children|@count > 0}
	
		
		<ul>
		{foreach from=$node.children item=child name=categoryTreeBranch}
			<li {if isset($last) && $last == 'true'}class="last"{/if}>
	 <h3><a img_link="{$link->getCatImageLink($child.link_rewrite,$child.id,'category')}" id_cat="{$node.id}" href="{$child.link}" {if isset($currentCategoryId) && ($child.id == $currentCategoryId)}class="selected"{/if} title="{$child.desc|escape:html:'UTF-8'}">{$child.name|escape:html:'UTF-8'}</a></h3>
	 </li>
		{/foreach}
		</ul>
		{/if}
	<a href="{$node.link}"><img src="{$link->getCatImageLink($node.link_rewrite,$node.id,'category')}" /></a>
	</div>
	
</li>
