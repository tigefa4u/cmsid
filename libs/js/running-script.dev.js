
	document.createElement("article");
	document.createElement("footer");  
	document.createElement("header");
	document.createElement("hgroup");
	document.createElement("nav");
	document.createElement("menu");

	var SubMenutimer;
	var last_o;

	$(".mainMenu").ready(function() {
	
		$(".staticMenu dt a").click(function() {			
			$(".staticMenu dd ul").css({
				'position':'abolsute',
				'z-index':'4'
			});

			$(".staticMenu dd ul").not($(this).parents(".staticMenu").find("ul")).hide();
			$(".staticMenu dt a").not($(this)).removeClass("selected");
			$(this).parents(".staticMenu").find("ul").slideToggle('slow', function() {
				if($(".staticMenu dt a").parents(".staticMenu").find("ul.mainMenuSub").css("display") == "none"){
					$(".staticMenu dt a").removeClass("selected");	
				}else{
					$(".staticMenu dt a").addClass("selected");
				}
			});

			if($(this).parents(".staticMenu").find("ul.mainMenuSub").css("display") == "none"){
				$(this).removeClass("selected");
			}else{
				$(this).addClass("selected");			
			}

		});
/*
		$(".staticMenu dd ul li a").click(function() {
			var text = $(this).html();
			$(".staticMenu dt a div").html(text);
			$(".staticMenu dd ul").hide();
		});
*/
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("staticMenu")){
				$(".staticMenu dd ul").hide();
				$(".staticMenu dt a").removeClass("selected");
			}

		});
	});

	function openSubMenu(o){
		cancelSubMenuClose();

		if(last_o) $(last_o).parent().find("div").hide();

		last_o = o;
		$(o).parent().find("div").show();
	}

	function closeSubMenu(){
		SubMenutimer = setTimeout("close()",500);
	}

	function cancelSubMenuClose(){
		clearTimeout(SubMenutimer);
	}

	function close(){
		$(last_o).parent().find("div").hide();
	}
		
	function getLoad( id, url_data, s ){	
		
		var request = $.ajax({
		  url: url_data,
		  type: "GET",
		  cache: true,
		  global: false, 
		  dataType: "html",
		  beforeSend: function () {
			var ajax_loader = '<center style="padding:10px;"><img src="libs/img/ajax-loader-black.gif"><div style="clear:both">Loading...</div></center>';
			if( s ){
				$("#"+id).html('<div class="gd"><div class="gd-content">'+ajax_loader+'</div></div></div> ');
			}else{
				$("#"+id).html(ajax_loader);
			}
		  }
		});
		
		request.fail(function ( data ) {
			$("#"+id).html('<div class="padding"><p id="error_no_ani">Request failed: '+data+'</p></div>');
		});
		request.done(function ( data ) {
			$("#"+id).html(data);
		});
	}	
	
	function event_scroll(id,i){
		var div = $(id);
		if(div.length){
			
		var start = div.offset().top;
		$.event.add(window, "scroll", function() {
			var p = $(window).scrollTop();
			div.css('position',((p)>start) ? 'fixed' : '');
			div.css('z-index',((p)>start) ? i : '');
			
			$('.shadow-inside-top').css('position',((p)>start) ? 'fixed' : 'absolute');
			$('.shadow-inside-top').css('z-index',((p)>start) ? i-1 : '');
		});
		
		}
	}
	
	function lw(id,remove_id,src){
		$(id).click( function(){
			$.post("?request&load=libs/ajax/lw.php", {"v": src});
			
			$(this).removeClass(id);
			$(this).addClass(remove_id);			
				
			location.reload(); 
		});
	}

	$(document).ready(function() {
	
	event_scroll('.nav.nav-fix',9);
	
	$("#menuJump").click( function(){
		$("#menuJump .icon").toggleClass('menuJumpBack').fadeIn('slow');
		$(".menuNavJumpFirst,.menuNavJumpSecond").slideToggle('fast');
	});
	$("#menuActions").click( function(){
		$(".menuActions").toggleClass('selected').toggle();
		$(".menuActions_list").slideToggle();
	});
		
		
	$("#post-right .widget .widget-top").toggler({method: "fadeToggle"});
	
	$("textarea.grow").ata();
	
	$(this).find("img").fadeIn(6000);
	/*	
	var nav_div = $("nav.nav-fix");
	var nav_div_start = $(nav_div).offset().top;
	$.event.add(window, "scroll", function(){
		var nav_p = $(window).scrollTop();
		$(nav_div).css('position',((nav_p)>nav_div_start) ? 'fixed' : 'static');
		$(nav_div).css('top',((nav_p)>nav_div_start) ? '0px' : '');
		}
	);
	*/
	
	
	$(".progress_anim").each(function() {
		$(this)
		.data("origWidth", $(this).width())
		.width(0)
		.animate({
		width: $(this).data("origWidth")
		}, 3000);
	});
	
	$('#date-picker').datepicker({
		format: 'yyyy-mm-dd'
	});
	
	//$('.gd > .gd-content').slideDown('slow');
	
	
	// Launch TipTip tooltip
	$('.tiptip a.button, .tiptip button, ul.tiptip li .tip').tipTip();
	
	window.setTimeout("$('#success,#message,#error,.ani_fade_out').fadeOut('fast')",7000);
	window.setTimeout("$('.ani_fade_out').fadeOut('fast')",1000);
	
	
	var counterValue = parseInt($('.bubble').html()); // Get the current bubble value

	function removeAnimation(){
		setTimeout(function() {
			$('.bubble').removeClass('animating')
		}, 1000);			
	}
	
	lw(".goSingle","goFull","full");
	lw(".goFull","goWrap","wrap");
	lw(".goWrap","goSingle","single");
	
	$(".shadow-inside-top").css({
		'top':'39px',
		'position':'absolute'
	});
	/*
	if($.browser.mozilla == true){
		$(".submit_jump").css({
			'margin-left':'-29px',
		});
	}*/
	if($.browser.opera == true || $.browser.msie == true){
		if($.browser.opera == true){
			$("ul.mainMenuSub li.logout a").css({ 
				'display': 'block',
				'height': '12px',
				'line-height': '12px',
				'overflow-x': 'hidden',
				'overflow-y': 'hidden',
				'padding-bottom': '7px',
				'padding-left': '22px',
				'padding-right': '4px',
				'padding-top': '6px'
			});
		}
		/*
		$(".submit_jump").css({
			'margin-left':'-34px',
		});*/
	}
		
	});