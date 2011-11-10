
var miniBasketTimeout;
basket = null;
basketController = null;
var TabTarget = '';


//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//centering
	$("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}




$(document).ready(function() {



//	$('view_mini_basket').toggle(function(){$('mini_basket').show();},function(){$('mini_basket').hide()});

//	$('#gallery a').lightBox();

	//LOADING POPUP
	//Click the button event!
/*
	$("#sizechart").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
	});
*/
	
	
/*
	$("#sizecharticon").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
	});
*/
			
				
	//CLOSING POPUP
	//Click the x event!
/*
	$("#popupContactClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});
*/




/*
	if ((screen.width >= 1024) && (screen.height >= 768)) {
		//		alert('Screen size: 1024x768 or larger');
		//		$("link[rel=stylesheet]:not(:first)").attr({href : "detect1024.css"});
		$("#page").css('margin', '0 auto');

	} else {
		//		alert('Screen size: less than 1024x768, 800x600 maybe?');
		//		$("link[rel=stylesheet]:not(:first)").attr({href : "detect800.css"});
		$("#page").css('margin', '0');
	}
*/

/*
	var pos = $("#header").offset();
	var headerWidth = $("#header").width();
	var basketPopupWidth = $("#mini_basket").width();
	var left = pos.left + headerWidth - basketPopupWidth + 278;
	
	var leftt = pos.left + headerWidth - basketPopupWidth + 15;
	
	var top = pos.top;
	
	var leftm = pos.left + headerWidth - basketPopupWidth - 2;
	var topm = pos.top + 27;
*/

	
/*
	$("#viewed-products_block_left").css({
		top: top,
		left: left,
		display: "block"
	});
*/
	
/*
	$(".signin_menu").css({
		left: leftt
	});
*/
	
	
		$("#mini_basket").css({
			top: topm,
			left: leftm
		});

	
	
/*
	$("body#index #more_info_tabs a, body#category #more_info_tabs a").click(function(event) {
		TabTarget = $(event.target).attr("href");
		TabTarget2 = TabTarget.substr(1);
		$.ajax({
			type: 'GET',
			url: baseDir + 'index.php',
			async: true,
			dataType: "text",
			data: 'ajaxHome&tabTarget=' + TabTarget2,
			success: function(text) {
				$(TabTarget).html(text);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {}
		});
	});
*/
	
/*
	$("body#index #more_info_tabs a#more_info_tab_attachments").click();
	$(".maintop-menu").hover(function() {
		$("#viewed-products_block_left").css('z-index', '-1');
		$("#image-block a#phuongzoom").css('position','static');
		$(this).children("a.on").removeClass('on').addClass('off').addClass('pin');
		$(this).children("span.sd").show();
		$(this).children(".bx-ddmenu").stop(true, true).show();
	},
	function() {
		$("#viewed-products_block_left").css('z-index', '0');
		$("#image-block a#phuongzoom").css('position','relative');		
		$(this).children("a.pin").removeClass('pin');
		$(this).children("span.sd").hide();
		$(this).children(".bx-ddmenu").stop(true, true).hide();
		if (!$("a.pin").get(1)) {
			$(this).children("a.off").removeClass('off').addClass('on');
		}
	});
*/

	basketController = new BasketController();
	basketController.init();
	
});



BasketController = function BasketController() {
	var basketModel = null;
	var self = this;
	this.setEvent = function() {
		$(".close-mini").click(function() {
			self.hideMiniBasket();
		})
		$("#close-mini2").click(function() {
			self.hideMiniBasket();
		})
		$("#show_mini").click(function() {
			self.showMiniBasket();
		})

/*
		$(".remove-basket-mini").click(function(event) {
			$(this).text('processing...');
			var attribute_id = $(event.currentTarget).attr('rel');
			basketModel.removeItem(attribute_id);
		})
		$('.jdropdown-quantity').dropdown({
			onchange: self.quantityItemOnChange
		});
*/
		
	}
	
/*
	this.addToBag = function(attribute_id, quantity) {
		basketModel.addItem(attribute_id, quantity);
	};
*/

	this.dimScreen = function(div_id, opac) {
		var wW = $(window).width();
		var wH = $(document).height();
		if (div_id != "" && $("#" + div_id)) {
			wH = Math.max($(document).height(), pY + $("#" + div_id).height());
		}
		wW = wW - 15;
		if (opac == "") opac = "0.7";
		if (!$("#dim-bg").is(":visible")) {
			$('body').prepend("<div id='dim-bg'></div>");
			$('#dim-bg').css({
				left: 0,
				display: 'block',
				opacity: opac,
				'width': wW + 'px',
				'height': wH + 'px',
				'position': 'absolute',
				'z-index': 9998,
				'background': '#000'
			});
			$("#dim-bg").click(function() {
				self.hideMiniBasket();
			})
		}
	}
	
	this.hideMiniBasket = function() {

		$("#darkBackgroundLayer").hide();
	
		$("#dim-bg").fadeOut("fast", function() {
			$("#dim-bg").remove();
		});
		$("#mini_basket").slideUp("slow");
		if (miniBasketTimeout) {
			clearTimeout(miniBasketTimeout);
		}
		$("#mini_basket").unbind();
	}
	
	this.showMiniBasket = function() {

		$('html, body').animate({
			scrollTop: 0
		},
		'slow');
		$("#mini_basket").css({
			'z-index': 9999
		});

		self.dimScreen("", 0.7);
		
//		document.getElementsById("darkBackgroundLayer").style.display = "block";
		
/*
		$("#darkBackgroundLayer").show();
		
		$("#darkBackgroundLayer").click(function() {
				self.hideMiniBasket();
			});
*/
		
		var pos = $("#header").offset();
		var headerWidth = $("#header").width();
		var basketPopupWidth = $("#mini_basket").width();
		var left = pos.left + headerWidth - basketPopupWidth - 2;
		var top = pos.top + 27;
		$("#mini_basket").css({
			top: top,
			left: left
		});
		$("#mini_basket").bind("mouseenter", function() {
			clearTimeout(miniBasketTimeout);
		}).bind("mouseleave", function() {
			miniBasketTimeout = setTimeout(self.hideMiniBasket, 5000);
		});
		$("#mini_basket").slideDown("slow");
	}
	
	this.init = function() {
		basketModel = new BasketModel(this);
		this.setEvent();
	}
	
}
BasketModel = function BasketModel(_controller) {
	var controller = _controller;
	var self = this;
}
