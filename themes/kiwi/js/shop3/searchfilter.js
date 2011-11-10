$(document).ready(function() {
	
//	$('body#category div#product-filter a').live('click', function() {

		filterQuery = 'searchfilter=1&filter=1';

		$.ajax({
			type: 'GET',
			url: baseDir + 'search.php?search_query=ipod',
			async: true,
			cache: false,
			dataType: "json",
			data: filterQuery,
			beforeSend: function() {
//				$('#ajax-loading').css('display', 'block');
			},
			complete: function() {
//				$('#ajax-loading').css('display', 'none');
			},
			success: function(jsonData, textStatus, jqXHR) {

				// $('.paginationclass').replaceWith(jsonData.pagination);
				$('#product-list ul').html(jsonData.productList);
				
				console.log(jsonData.productList);
				
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('fail' + errorThrown + textStatus);
			}
		});

//		return false;

//	});
	
	
	
	
});
