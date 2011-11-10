{if $blockCategTree.children}
	{assign var='intParentId' value="0"}
    {assign var='currentCategoryId' value="0"}
    <ul id="left-cat-tree">
        {foreach from=$blockCategTree.children item=parent name=blockCategTree}
        	<li class="first-lvl-cat">
                <h3><a id_cat="{$parent.id}" href="{$parent.link}" class="{if $smarty.foreach.blockCategTree.first}first{/if}{if (isset($currentCategoryId)) || ($intParentId == $parent.id)} current{/if}" title="{$parent.desc|escape:html:'UTF-8'}">{$parent.name|escape:html:'UTF-8'}</a></h3>
                {if $parent.children|@count > 0}
                    <ul class="child-cat" {if $parent.id != $currentCategoryId}style="display:none"{/if}>
                    	{foreach from=$parent.children item=child name=categoryTreeBranch}
                            <li>
                                <h4><a id_cat="{$child.id}" href="{$child.link}" {if isset($currentCategoryId) && ($child.id == $currentCategoryId)}class="current"{/if} title="{$child.desc|escape:html:'UTF-8'}">{$child.name|escape:html:'UTF-8'}</a></h4>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            </li>
		{/foreach}
    </ul>        
{/if}
