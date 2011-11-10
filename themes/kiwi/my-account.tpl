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
*  @version  Release: $Revision: 6599 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	var baseDir = '{$base_dir_ssl}';
//]]>
</script>
<!-- MAIN CONTENT WEBSITE -->

<div class="main-content">
    <!-- LEFT MENU -->
    {include file="$tpl_dir./flow-account.tpl" intCurrent=1}
    <!-- END TOP MENU -->
    <!-- DATA -->
    <div class="data">
    	{capture name=path}{l s='My account'}{/capture}
        {include file="$tpl_dir./breadcrumb.tpl"}        
        <div class="box-information">
            <div class="header-title"><h3>{l s='My account'}</h3></div>
            <div class="information-item">
            	<ul>
                    <li><a href="{$link->getPageLink('history.php', true)}" title="{l s='Orders'}"><img src="{$smarty.const._THEME_DIR_}images/icon/order.gif" alt="{l s='Orders'}" class="icon" /></a><a href="{$link->getPageLink('history.php', true)}" title="{l s='Orders'}">{l s='History and details of my orders'}</a></li>
                    {if $returnAllowed}
                        <li><a href="{$link->getPageLink('order-follow.php', true)}" title="{l s='Merchandise returns'}"><img src="{$smarty.const._THEME_DIR_}images/icon/return.gif" alt="{l s='Merchandise returns'}" class="icon" /></a><a href="{$link->getPageLink('order-follow.php', true)}" title="{l s='Merchandise returns'}">{l s='My merchandise returns'}</a></li>
                    {/if}
                    <li><a href="{$link->getPageLink('order-slip.php', true)}" title="{l s='Credit slips'}"><img src="{$smarty.const._THEME_DIR_}images/icon/slip.gif" alt="{l s='Credit slips'}" class="icon" /></a><a href="{$link->getPageLink('order-slip.php', true)}" title="{l s='Credit slips'}">{l s='My credit slips'}</a></li>
                    <li><a href="{$link->getPageLink('addresses.php', true)}" title="{l s='Addresses'}"><img src="{$smarty.const._THEME_DIR_}images/icon/addrbook.gif" alt="{l s='Addresses'}" class="icon" /></a><a href="{$link->getPageLink('addresses.php', true)}" title="{l s='Addresses'}">{l s='My addresses'}</a></li>
                    <li><a href="{$link->getPageLink('identity.php', true)}" title="{l s='Information'}"><img src="{$smarty.const._THEME_DIR_}images/icon/userinfo.gif" alt="{l s='Information'}" class="icon" /></a><a href="{$link->getPageLink('identity.php', true)}" title="{l s='Information'}">{l s='My personal information'}</a></li>
                    {if $voucherAllowed}
                        <li><a href="{$link->getPageLink('discount.php', true)}" title="{l s='Vouchers'}"><img src="{$smarty.const._THEME_DIR_}images/icon/voucher.gif" alt="{l s='Vouchers'}" class="icon" /></a><a href="{$link->getPageLink('discount.php', true)}" title="{l s='Vouchers'}">{l s='My vouchers'}</a></li>
                    {/if}
                    {$HOOK_CUSTOMER_ACCOUNT}
                    <li class="last"><a href="{$base_dir}" title="{l s='Home'}"><img src="{$smarty.const._THEME_DIR_}images/icon/home.gif" alt="{l s='Home'}" class="icon" /></a><a href="{$base_dir}" title="{l s='Home'}">{l s='Home'}</a></li>
                </ul>
            </div>
        </div>             	
    </div>
    <!-- END DATA -->
</div>
<!-- END MAIN CONTENT WEBSITE -->
