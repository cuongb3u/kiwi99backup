
<div id="product-filter" min="{$min_slider}" max="{$max_slider}" id_cat="{$id_category}">
	
<h2>color</h2>

<div id="cat-color-filter">
	
	<div class="navgroup refinement" id="refinement-colorFamily">
						<div class="refineattributes">


								<div class="swatches Colour">
									<ul>
											        
																        {foreach from=$allColorsByThisCategory item="attribute" key="k"}
																 					{if $attribute.id_attribute_group == 2}
																						{assign var='attributes' value='|'|explode:$attribute.color_info}
													                    					<li>
																								<a id_attribute="{$attributes[0]}" title="Màu {$attributes[1]}" href="#" class="swatchRefineLink" >
																									<span>{$attributes[1]}</span> 
																								</a>
																								<img src="{$img_col_dir}{$attributes[0]}.png" alt="Màu {$attributes[1]}" class="colorswatchpng" height="16" width="16" />
																							</li>
																 						{/if}
																        {/foreach}            	


									</ul>
								</div><!-- END: swatches -->
								<div class="clear"><!-- FLOAT CLEAR --></div>

						</div><!-- END: refineattributes -->
					</div>	
	
</div>

<h2>size</h2>
<div id="cat-size-filter">

	<ul>
	 {foreach from=$allAttributesByThisCategory item="attribute" key="k" name="sizef"}
 					{if $attribute.id_attribute_group == 1}
						{assign var='attributes' value='|'|explode:$attribute.id_name}
      <li class="half-size {if $smarty.foreach.sizef.iteration % 2 != 0}push-right{/if}"><a class="swatchRefineLink" href="#" id_attribute="{$attributes[0]}" title="Size {$attributes[1]}"><span>{$attributes[1]}</span></a></li>
 					{/if}
        {/foreach}
	</ul>
</div>

</div>