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
	
<li class="top_menu_help" {if isset($last) && $last == 'true'}class="last"{/if}>
	 <h3><a id_cat="{$node.id}" href="{$node.link}" {if isset($currentCategoryId) && ($node.id == $currentCategoryId)}class="selected "{/if} title="{$node.desc|escape:html:'UTF-8'}" class="top_menu_help_a">{$node.name|escape:html:'UTF-8'}</a></h3>
	
	{if $node.children|@count > 0}
	 <div class="dropdown-menu dropdown-menu{$node.id}">
		
		<ul>
		
		{foreach from=$node.children item=child name=categoryTreeBranch}
			<li {if isset($last) && $last == 'true'}class="last"{/if}>
	 <h3 class="real_cat"><a id_cat="{$node.id}" href="{$child.link}" {if isset($currentCategoryId) && ($child.id == $currentCategoryId)}class="selected"{/if} title="{$child.desc|escape:html:'UTF-8'}" id_img="{$child.id}" >{$child.name|escape:html:'UTF-8'}</a></h3>
	 </li>
		{/foreach}
		</ul>
		{/if}
		{if $node.id==80}
		<a class="right_image">
			<img class="current_right_image all_right_image 80" src="../images/80/80-Libramoda-Thoi-trang-nu.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 85" src="../images/80/85-Libramoda-Ao-khoac.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 86" src="../images/80/86-Libramoda-Ao-thun.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 87" src="../images/80/87-Libramoda-Dam.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 88" src="../images/80/88-Libramoda-Ao-kieu.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			</a>
			</div>
		{/if}
		
		{if $node.id==89}
		<a class="right_image">
			<img class="current_right_image all_right_image 89" src="../images/89/89-Libramoda-Phu-kien.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 90" src="../images/89/90-Libramoda-Vong-tay.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 91" src="../images/89/91-Libramoda-Tui-xach.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 92" src="../images/89/92-Libramoda-Vi.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			<img class="all_right_image 94" src="../images/89/94-Libramoda-That-lung.jpg" title="{$node.desc|escape:html:'UTF-8'}" />
			</a>
			</div>
		{/if}
	
	
</li>
	