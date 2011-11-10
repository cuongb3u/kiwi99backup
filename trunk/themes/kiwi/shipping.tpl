
  {*  <div class="thumb">
        <img src="{$smarty.const._THEME_DIR_}images/data/free-shipping.gif" alt="" border="0" width="288" />
    </div>*}
    <ul class="shipping_list" >
       {* <li>
            <p align="left" width="50">sub-item:</p>
            <p align="left"><span class="items" id="summary_products_quantity">{$productNumber} {if $productNumber == 1}{l s='product'}{else}{l s='products'}{/if}</span></p>
            <p align="right">&nbsp;</p>
        </li>*}
        {if $use_taxes}
            {if $priceDisplay}
                <li class="cart_total_price">
                    <span >{l s='Total products'}{if $display_tax_label} {l s='(tax excl.)'}{/if}{l s=':'}</span>
                    <div id="total_product" align="right"><span class="price">{displayPrice price=$total_products}</span></div>
                </li>
            {else}
                <li class="cart_total_price">
                    <span>{l s='Total products'}{if $display_tax_label} {l s='(tax incl.)'}{/if}{l s=':'}</span>
                    <div id="total_product" align="right"><span class="price">{displayPrice price=$total_products_wt}</span></div>
                </li>
            {/if}
        {else}
            <li class="cart_total_price">
                <span>{l s='Total products:'}</span>
                <div id="total_product" align="right"><span class="price">{displayPrice price=$total_products}</span></div>
            </li>
        {/if}
        {if $use_taxes}
            {if $priceDisplay}
                <li class="cart_total_delivery" {if $shippingCost <= 0} style="display:none;"{/if}>
                    <span >{l s='Total shipping'}{if $display_tax_label} {l s='(tax excl.)'}{/if}{l s=':'}</span>
                    <div id="total_shipping" align="right"><span class="price">{displayPrice price=$shippingCostTaxExc}</span></div>
                </li>
            {else}
                <li class="cart_total_delivery"{if $shippingCost <= 0} style="display:none;"{/if}>
                    <span>{l s='Total shipping'}{if $display_tax_label} {l s='(tax incl.)'}{/if}{l s=':'}</span>
                    <div id="total_shipping" align="right"><span class="price">{displayPrice price=$shippingCost}</span></div>
                </li>
            {/if}
        {else}
            <li class="cart_total_delivery"{if $shippingCost <= 0} style="display:none;"{/if}>
                <span >{l s='Total shipping:'}</span>
                <div id="total_shipping" align="right"><span class="price">{displayPrice price=$shippingCostTaxExc}</span></div>
            </li>
        {/if}        
        <li class="cart_total_voucher" {if $total_discounts == 0}style="display: none;"{/if}>
            <p >
                {if $use_taxes}
                    {if $priceDisplay}
                        {l s='Total vouchers'}{if $display_tax_label} {l s='(tax excl.)'}{/if}{l s=':'}
                    {else}
                        {l s='Total vouchers'}{if $display_tax_label} {l s='(tax incl.)'}{/if}{l s=':'}
                    {/if}
                {else}
                    {l s='Total vouchers:'}
                {/if}
            </p>
            <p class="price-discount" id="total_discount" align="right">
                <span class="price">
                    {if $use_taxes}
                        {if $priceDisplay}
                            {displayPrice price=$total_discounts_tax_exc}
                        {else}
                            {displayPrice price=$total_discounts}
                        {/if}
                    {else}
                        {displayPrice price=$total_discounts_tax_exc}
                    {/if}
                </span>
            </p>
        </li>
        <li class="cart_total_voucher" {if $total_wrapping == 0}style="display: none;"{/if}>
            <p >
                {if $use_taxes}
                    {if $priceDisplay}
                        {l s='Total gift-wrapping'}{if $display_tax_label} {l s='(tax excl.)'}{/if}{l s=':'}
                    {else}
                        {l s='Total gift-wrapping'}{if $display_tax_label} {l s='(tax incl.)'}{/if}{l s=':'}
                    {/if}
                {else}
                    {l s='Total gift-wrapping:'}
                {/if}
            </p>
            <p class="price-discount" id="total_wrapping" align="right">
                <div class="price">
                    {if $use_taxes}
                        {if $priceDisplay}
                            {displayPrice price=$total_wrapping_tax_exc}
                        {else}
                            {displayPrice price=$total_wrapping}
                        {/if}
                    {else}
                        {displayPrice price=$total_wrapping_tax_exc}
                    {/if}
                </div>
            </p>
        </li>	
        {if $use_taxes}
            <li class="cart_total_price">
                <span class="top-line">{l s='Total (tax excl.):'}</span>
                <div id="total_price_without_tax" class="top-line"  align="right"><span class="price">{displayPrice price=$total_price_without_tax}</span></div>
            </li>
            <li class="cart_total_tax">
                <span >{l s='Total tax:'}</span>
                <div id="total_tax" align="right"><span class="price">{displayPrice price=$total_tax}</span></div>
            </li>
              </ul>
            <p class="cart_total_price">
                <span >{l s='Total (tax incl.):'}</span>
                <span id="total_price" class="price">{displayPrice price=$total_price}</span>
          
                	</div>
                  </p>
        {else}
          </ul>
            <p class="cart_total_price">
                <span class="top-line">{l s='Total:'}</span>
              <span id="total_price" > <span class="price">{displayPrice price=$total_price_without_tax}</span>
          
              	</span>
                </p>
        {/if}
     {* <p class="cart_free_shipping" {if $free_ship <= 0 || $isVirtualCart} style="display: none;" {/if}>
            <p  style="white-space: normal;">{l s='Remaining amount to be added to your cart in order to obtain free shipping:'}</p>
            <p id="free_shipping" align="right"><div class="price">{displayPrice price=$free_ship}</div></p>
        </p
        *}
  

