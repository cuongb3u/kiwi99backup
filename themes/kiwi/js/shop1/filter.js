
//			document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
//	document.location.href = 'index.php';

	//	  jQuery("#Slider2").slider({ from: 5000, to: 150000, heterogeneity: ['50/50000'], step: 1000, dimension: '&nbsp;$' });

$(document).ready(function() {

$('body#category form.pagination select#nb_item').change(function(){
		
		addtionQuery = '';		
	
		$('form.pagination input:hidden').each(function(){
			
			if($(this).attr('name') != 'id_category'){
				addtionQuery += '&' + $(this).attr('name') + '=' + $(this).attr('value');
			}
			
		});
	
		itemperpage =  $(this).val();

		$('body').data('itemperpage', itemperpage);

		buildQuery();

		strBrand = $('body').data('brand');
		strBrand = strBrand ? strBrand.slice(0, -1) : '';

		strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
		strSortby = strSortby ? strSortby.split(':') : '';

		strPrice = $('body').data('price') ? $('body').data('price') : 0;
		hasPrice = strPrice ? 1 : 0;

		$('body').data('hasPrice', hasPrice);

		strAttribute = $('body').data('id_attribute');
		strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

		hasColor = $('body').data('color');

		orderway = strSortby[1] ? strSortby[1] : 'asc';
		orderby = strSortby[0] ? strSortby[0] : 'orderprice';

							if(is_search && search_query){

filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage + addtionQuery + '&is_search=' + is_search + '&search_query=' + search_query;


							}else{


							filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage + addtionQuery;

							}


		id_category = $('#product-filter').attr('id_cat');

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_category + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {
				$('#ajax-loading').css('display', 'none');
			},
			success: function(jsonData, textStatus, jqXHR) {

				$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('fail' + errorThrown + textStatus);
			}
		});

	});
	

	$('a[id_cat~="' + id_category + '"]').parents('li').children('ul.child-cat').css('display', 'block');
	$('a[id_cat~="' + id_category + '"]').parents('li.first-lvl-cat').addClass('cate-chosen');

	mmin = parseInt($('#product-filter').attr('min'));
	mmax = parseInt($('#product-filter').attr('max'));

	jQuery("#Slider2").slider({
		from: mmin,
		to: mmax,
		step: 10000,
		dimension: "&nbsp;đ",
		callback: function(value) {

			value = value.replace(/;/g, "-");
			
			$('body').data('price', value);

			buildQuery();

			strBrand = $('body').data('brand');
			strBrand = strBrand ? strBrand.slice(0, -1) : '';

			strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
			strSortby = strSortby ? strSortby.split(':') : '';

			itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

			strPrice = $('body').data('price') ? $('body').data('price') : 0;
			hasPrice = strPrice ? 1 : 0;

			$('body').data('hasPrice', hasPrice);

			strAttribute = $('body').data('id_attribute');
			strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

			hasColor = $('body').data('color');

			orderway = strSortby[1] ? strSortby[1] : 'asc';
			orderby = strSortby[0] ? strSortby[0] : 'orderprice';

			
					if(is_search && search_query){

filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage + '&is_search=' + is_search + '&search_query=' + search_query;


					}else{


filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

					}
			

			id_category = $('#product-filter').attr('id_cat');

			$.ajax({
				type: 'GET',
				url: baseDir + 'category.php?id_category=' + id_category + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
				async: true,
				cache: false,
				dataType: "json",
				data: filterQuery,
				beforeSend: function() {
					$('#ajax-loading').css('display', 'block');
				},
				complete: function() {
					$('#ajax-loading').css('display', 'none');
				},
				success: function(jsonData, textStatus, jqXHR) {

					$('.paginationclass').replaceWith(jsonData.pagination);
					$('#productList').html(jsonData.productList);

				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
								console.log('fail' + errorThrown + textStatus);
				}
			});

		}

	});



	function displayColorImage(domAAroundImgThumb) {
		if (domAAroundImgThumb.attr('href')) {
			//					  var newSrc = domAAroundImgThumb.children('img').attr('src').replace('medium','home');			  
			var newSrc = domAAroundImgThumb.attr('href');
			var myAnchor = domAAroundImgThumb.parents('.pro-thumb');
			if ($('.mainpic', myAnchor).attr('src') != newSrc) {
				$('.mainpic', myAnchor).fadeOut('fast', function() {
					$(this).attr('src', newSrc).show();
				});
			}
			$('#views_block li a').removeClass('shown');
			$(domAAroundImgThumb).addClass('shown');
		}
	}


	function buildQuery() {

		color_attribute = buildStringQuery('cat-color-filter');
		size_attribute = buildStringQuery('cat-size-filter');
		brand = buildStringQuery('brand');

		sortby = $('div.sortby option:selected').val();
		itemperpage = $('body#category form.pagination select#nb_item option:selected').val();

		id_attribute = color_attribute + size_attribute;

		$('body').data('id_attribute', id_attribute);
		$('body').data('brand', brand);
		//			$('body').data('price', price);
		$('body').data('sortby', sortby);
		$('body').data('itemperpage', itemperpage);		

		if (color_attribute.length > 0) {
			$('body').data('color', 1);
		} else {
			$('body').data('color', 0);
		}

	}


	function buildStringQuery(queryType) {
		var str = '';

		$('#' + queryType + ' a.selected').each(function() {
			str += $(this).attr('id_attribute');
			str += ',';
		});
		return str;
	}


	$('.pro-thumb li a').live('click', function() {
		return false;
	});


	// $('.pro-thumb li a').live({
	// 	mouseenter: function() {
	// 
	// 		$(this).parents('.pro-thumb').find('a.current').removeClass('current');
	// 		displayColorImage($(this));
	// 		
	// 	},
	// 	mouseleave: function() {
	// 
	// 		$(this).addClass('current');
	// 
	// 	}
	// });

	$('.pro-thumb li a').live('mouseover', function(){
			if (!$(this).data('init'))  
				{  
			      $(this).data('init', true);
	           $(this).hoverIntent  
	           (  
	               function()  
	               {  
										$(this).parents('.pro-thumb').find('a.current').removeClass('current');
										displayColorImage($(this));
	               },  
	               function()  
	               {  
										$(this).addClass('current');
	               }  
	           );  
	           $(this).trigger('mouseover');  
				}	          
	});




	$('ul#productList .product_img_link').live('click', function() {
		
		id_color = $(this).parents('.pro-thumb').find('a.current').attr('id_color');

		old_href = $(this).attr('href');
		
		$(this).attr('href', old_href + '&ipaColor=' + id_color);
		
	});


	var id_attribute = '';

	$('body#category .paginationclass a').live('click', function() {

		updatedQuery = $(this).attr('href').slice(1);

		itemperpage = $('body#category form.pagination select#nb_item option:selected').val();

		pt = parseInt(updatedQuery.search('filter'));

		that = this;

		if (pt == -1) {
			orderway = 'asc';
			orderby = 'orderprice';
			pageNo = $(that).text();
			updatedQuery = 'filter=1&orderby=' + orderby + '&orderway=' + orderway + '&p=' + pageNo + '&n=' + itemperpage;
		}

		id_category = $('#product-filter').attr('id_cat');
		
		

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_category + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: updatedQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {
				$('#ajax-loading').css('display', 'none');
			},
			success: function(jsonData, textStatus, jqXHR) {
				
				$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

		return false;
	});


	$('body#category div#product-filter a').live('click', function() {

		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
			$(this).siblings('img').removeClass('current');
		} else {
			$(this).addClass('selected');
			$(this).siblings('img').addClass('current');
		}

		buildQuery();

		strBrand = $('body').data('brand');
		strBrand = strBrand ? strBrand.slice(0, -1) : '';

		strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
		strSortby = strSortby ? strSortby.split(':') : '';

		strPrice = $('body').data('price') ? $('body').data('price') : 0;

		strAttribute = $('body').data('id_attribute');
		strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

		hasColor = $('body').data('color');
		hasPrice = $('body').data('hasPrice') ? $('body').data('hasPrice') : 0;
		
		itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();		

		orderway = strSortby[1] ? strSortby[1] : 'asc';
		orderby = strSortby[0] ? strSortby[0] : 'orderprice';

		if(is_search && search_query){
			
//			alert('search');
			
			filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage + '&is_search=' + is_search + '&search_query=' + search_query;


		}else{

			// alert('no search');

		filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

		}


		id_category = $('#product-filter').attr('id_cat');

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_category + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {
				$('#ajax-loading').css('display', 'none');
			},
			success: function(jsonData, textStatus, jqXHR) {

				$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

		return false;

	});


	$('body#category #left-cat-tree a').click(function() {

		/*
			$('ul.child-cat').css('display','none');
			$(this).parents('li').children('ul.child-cat').css('display','block');
		*/
		$('.tree-menu-selected').removeClass('tree-menu-selected');
		$(this).addClass('tree-menu-selected');
		id_cat = $(this).attr('id_cat');


		if(is_search && search_query){
					document.location.href = 'category.php?id_category='+id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1];
		}


		that = this;

		strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
		strSortby = strSortby ? strSortby.split(':') : '';

		itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

		orderway = strSortby[1] ? strSortby[1] : 'asc';
		orderby = strSortby[0] ? strSortby[0] : 'orderprice';

		filterQuery = 'filter=1&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {

					mmin = parseInt($('#product-filter').attr('min'));
					mmax = parseInt($('#product-filter').attr('max'));

					repstr = '	<div class="layout-slider" style="position:absolute; margin: 0 270px; width:320px;"><input id="Slider2" type="slider" name="price" value="0;10000" /></div>';
				
					$('.layout-slider').replaceWith(repstr);
				
					jQuery("#Slider2").slider({
						from: mmin,
						to: mmax,
						step: 10000,
						dimension: "&nbsp;đ",
						callback: function(value) {

							value = value.replace(/;/g, "-");

							$('body').data('price', value);

							buildQuery();

							strBrand = $('body').data('brand');
							strBrand = strBrand ? strBrand.slice(0, -1) : '';

							strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
							strSortby = strSortby ? strSortby.split(':') : '';

							itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

							strPrice = $('body').data('price') ? $('body').data('price') : 0;
							hasPrice = strPrice ? 1 : 0;

							$('body').data('hasPrice', hasPrice);

							strAttribute = $('body').data('id_attribute');
							strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

							hasColor = $('body').data('color');

							orderway = strSortby[1] ? strSortby[1] : 'asc';
							orderby = strSortby[0] ? strSortby[0] : 'orderprice';

							filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

							$.ajax({
								type: 'GET',
								url: baseDir + 'category.php?id_category=' + id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
								async: true,
								cache: false,
								dataType: "json",
								data: filterQuery,
								beforeSend: function() {
									$('#ajax-loading').css('display', 'block');
								},
								complete: function() {
									$('#ajax-loading').css('display', 'none');
								},
								success: function(jsonData, textStatus, jqXHR) {

									$('.paginationclass').replaceWith(jsonData.pagination);
									$('#productList').html(jsonData.productList);

								},
								error: function(XMLHttpRequest, textStatus, errorThrown) {
											console.log('fail' + errorThrown + textStatus);
								}
							});

						}

					});


				$('#ajax-loading').css('display', 'none');

			},
			success: function(jsonData, textStatus, jqXHR) {

				$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);
				$('#product-filter').replaceWith(jsonData.classFilter);


				$('ul.child-cat').css('display', 'none');
				$('#left-cat-tree .cate-chosen').removeClass('cate-chosen');
				$(that).parents('li').children('ul.child-cat').css('display', 'block');
				$(that).parents('li.first-lvl-cat').addClass('cate-chosen');

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

		return false;
	});



	$('div.sortby select').change(function() {

		$('body').data('sortby', $(this).val());

		buildQuery();

		strBrand = $('body').data('brand');
		strBrand = strBrand ? strBrand.slice(0, -1) : '';

		strSortby = $('body').data('sortby');
		strSortby = strSortby ? strSortby.split(':') : '';

		itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

		strPrice = $('body').data('price') ? $('body').data('price') : 0;
		hasPrice = strPrice ? 1 : 0;

		$('body').data('hasPrice', hasPrice);

		strAttribute = $('body').data('id_attribute');
		strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

		hasColor = $('body').data('color');

		orderway = strSortby[1] ? strSortby[1] : 'asc';
		orderby = strSortby[0] ? strSortby[0] : 'orderprice';


				if(is_search && search_query){

		//			alert('search');

					filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage + '&is_search=' + is_search + '&search_query=' + search_query;


				}else{

					// alert('no search');

					filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

				}



		id_category = $('#product-filter').attr('id_cat');

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_category + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {
				$('#ajax-loading').css('display', 'none');
			},
			success: function(jsonData, textStatus, jqXHR) {

				$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

	});
	
	
	
	$('ul.main-menu li a').click(function() {

		/*
			$('ul.child-cat').css('display','none');
			$(this).parents('li').children('ul.child-cat').css('display','block');
		*/
		if ($('body').attr('id')!='category') {
			return true;
		};
		link=$(this).attr('href');
		$('.tree-menu-selected').removeClass('tree-menu-selected');
		treecate=$('body#category #left-cat-tree a[href='+link+']');
		$(treecate).addClass('tree-menu-selected');
		choosecat=$('.tree-menu-selected').parents('.child-cat');
		$('.child-cat').hide();
		$(choosecat).show();
		choosecat=$('.tree-menu-selected').parents('.first-lvl-cat').children('.child-cat');
		$(choosecat).show();
		$('.child-cat',treecate).css('display','block');
		$(treecate).parents('ul.child-cat').show();
		$(this).addClass('tree-menu-selected');
		id_cat = $(this).attr('id_cat');


		if(is_search && search_query){
					document.location.href = 'category.php?id_category='+id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1];
		}


		that = this;

		strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
		strSortby = strSortby ? strSortby.split(':') : '';

		itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

		orderway = strSortby[1] ? strSortby[1] : 'asc';
		orderby = strSortby[0] ? strSortby[0] : 'orderprice';

		filterQuery = 'filter=1&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

		$.ajax({
			type: 'GET',
			url: baseDir + 'category.php?id_category=' + id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {

					mmin = parseInt($('#product-filter').attr('min'));
					mmax = parseInt($('#product-filter').attr('max'));

					repstr = '	<div class="layout-slider" style="position:absolute; margin: 0 270px; width:320px;"><input id="Slider2" type="slider" name="price" value="0;10000" /></div>';
				
					$('.layout-slider').replaceWith(repstr);
				
					jQuery("#Slider2").slider({
						from: mmin,
						to: mmax,
						step: 10000,
						dimension: "&nbsp;đ",
						callback: function(value) {

							value = value.replace(/;/g, "-");

							$('body').data('price', value);

							buildQuery();

							strBrand = $('body').data('brand');
							strBrand = strBrand ? strBrand.slice(0, -1) : '';

							strSortby = $('body').data('sortby') ? $('body').data('sortby') : $('.sortby option:selected').val();
							strSortby = strSortby ? strSortby.split(':') : '';

							itemperpage = $('body').data('itemperpage') ? $('body').data('itemperpage') : $('body#category form.pagination select#nb_item option:selected').val();

							strPrice = $('body').data('price') ? $('body').data('price') : 0;
							hasPrice = strPrice ? 1 : 0;

							$('body').data('hasPrice', hasPrice);

							strAttribute = $('body').data('id_attribute');
							strAttribute = strAttribute ? strAttribute.slice(0, -1) : '';

							hasColor = $('body').data('color');

							orderway = strSortby[1] ? strSortby[1] : 'asc';
							orderby = strSortby[0] ? strSortby[0] : 'orderprice';

							filterQuery = 'filter=1&id_attribute=' + strAttribute + '&hasColor=' + hasColor + '&hasPrice=' + hasPrice + '&brand=' + strBrand + '&prices=' + strPrice + '&orderby=' + orderby + '&orderway=' + orderway + '&n=' + itemperpage;

							$.ajax({
								type: 'GET',
								url: baseDir + 'category.php?id_category=' + id_cat + '&' + $('#left-cat-tree a:first').attr('href').split('?',2)[1],
								async: true,
								cache: false,
								dataType: "json",
								data: filterQuery,
								beforeSend: function() {
									$('#ajax-loading').css('display', 'block');
								},
								complete: function() {
									$('#ajax-loading').css('display', 'none');
								},
								success: function(jsonData, textStatus, jqXHR) {

									//$('.paginationclass').replaceWith(jsonData.pagination);
									$('#productList').html(jsonData.productList);

								},
								error: function(XMLHttpRequest, textStatus, errorThrown) {
											console.log('fail' + errorThrown + textStatus);
								}
							});

						}

					});


				$('#ajax-loading').css('display', 'none');

			},
			success: function(jsonData, textStatus, jqXHR) {

			//	$('.paginationclass').replaceWith(jsonData.pagination);
				$('#productList').html(jsonData.productList);
				$('#product-filter').replaceWith(jsonData.classFilter);


				//$('ul.child-cat').css('display', 'none');
				$('#left-cat-tree .cate-chosen').removeClass('cate-chosen');
				//$(that).parents('li').children('ul.child-cat').css('display', 'block');
				//$(that).parents('li.first-lvl-cat').addClass('cate-chosen');

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

		return false;
	});

});
