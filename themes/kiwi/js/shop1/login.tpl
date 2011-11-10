 	 <li class="first" >                
                {l s='Welcome' mod='blockuserinfo'},  
                <br/>                    
              <a href="{$link->getPageLink('my-account.php', true)}" class ="user_name" title="{l s='Your Account' mod='blockuserinfo'}">  {$cookie->customer_firstname} {$cookie->customer_lastname}</a>
                (<a href="{$link->getPageLink('index.php')}index.php?mylogout=1" id="log_out" title="{l s='Log me out' mod='blockuserinfo'}">{l s='Log out' mod='blockuserinfo'}</a>)
            </li>

