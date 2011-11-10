{if $cookie->isLogged()}
<div class="flow-account">
    <div class="space">MY ACCOUNT</div>
    <ul>    	
     {*   <li>
            
                <ul>
                    <li class="first"><a href="{$link->getPageLink('authentication.php?SubmitCreate=1', true)}" {if $intCurrent==1}class="current"{/if}>Sign in or Register</a></li>                                
                </ul>
          
        </li> 
        *}       
        <li><h3><a id="identity_link" href="{$link->getPageLink('identity.php', true)}" >My Information</a></h3></li>
        <!--<li><h3><a href="{$link->getPageLink('mywishlist.php', true)}" {if $intCurrent==3}class="current"{/if}>wishlist</a></h3></li>-->
        <li><h3><a href="{$link->getPageLink('addresses.php', true)}">My Addresses</a></h3></li>
         <li><h3><a href="{$link->getPageLink('address.php', true)}" >Address book</a></h3></li>
        <li><h3><a href="{$link->getPageLink('history.php', true)}">Order History</a></h3></li>
         <li><h3><a href="{$link->getPageLink('order-slip.php', true)}">My credit slips</a></h3></li>
         <li><h3><a href="{$link->getPageLink('discount.php', true)}" >My vouchers</a></h3></li>
    </ul>
</div>
{/if}
	