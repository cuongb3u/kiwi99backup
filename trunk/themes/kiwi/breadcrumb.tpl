{strip}
{if isset($smarty.capture.path)}{assign var='path' value=$smarty.capture.path}{/if}
{if !isset($short)}
    <div class="navi"><h3>
{/if}
		<a href="{$base_dir}" title="{l s='return to'} {l s='Home'}">{l s='Home'}</a>{if isset($path) AND $path}{if isset($path) && !$path|strpos:'span'}<span class="navigation_page">{$path}</span>{else}{$path}{/if}{/if}
{if !isset($short)}
    </h3></div>
{/if}
{/strip}
