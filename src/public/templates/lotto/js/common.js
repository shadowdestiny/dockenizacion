$(document).ready(function(){
	console.log(settings);
	displayDetect();
	if(settings.pagename == undefined){ //pagesettings object
		settings.pagename = $('.pagename').attr('id');
	}
	if(settings.initOV != undefined){ //display initial overlay for all pages, if needed
		showPopup(settings.initOV.html);
	}
	//selectmenu
	$('#switchlang select').moSelect({output:'value', mode:'link'});

	//smooth scrolling
	$('a[href*=#]:not([href=#])').click(function() {
		if ($(this).data('toggle') != undefined){ return false;}
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
			  $('html,body').animate({
				scrollTop: target.offset().top
			  }, 1000);
			  return false;
	    }
	  }
	});


if (settings.pagename == 'home'){
	$('body').scrollspy({ target: '.dotmenu', offset: 160});
	var screenheight = $(window).height();
	$('#home .page').css({'height': screenheight + 'px'});

	$(window).resize(function() {
		var screenheight = $(window).height();
		$('#home .page').css({'height': screenheight + 'px'});
		$('body').scrollspy('refresh');
	});
	$('body').scrollspy('refresh');
}
//preload functionality
if (settings.pagename == 'results' || settings.pagename == 'newslist'){
	if ($('#paginator').length != 0){
		requestNextPage();
		setInterval(function(){
			if (blockNextPage == false && $('#paginator').isOnScreen()){
				showtNextPage();
			}
		},1000);
	}
}

if (settings.pagename == 'account'){

	// set binds for each .box
	function startAccountBinds(element) {
		//Initialisieren
		var position = '';
		if (element != undefined && element != ''){
			position = '#' + element + ' ';
		}

		$(position + 'a.toggle,button.toggle').click(function(e){
			e.preventDefault();
			target = '#' + $(this).data('target');
			$(target).toggleClass('closed');
			msnry.layout();
			return false;
		});
		$(position + 'form').submit(function(){
			params = formCollect($(this));
			wrapper = $(this).parent().parent().attr('id');
			$.extend(params, {'popup': 1});
			response = AjaxSubmit(params);
			if(response.success){
				$(this).parent().html(response.html);
			} else {
				alert('error');
			}
			startAccountBinds(wrapper + ' .inner.content');
			startBinds(wrapper + ' .inner.content');
			msnry.layout();
			return false;
		});
	}

	//init msnry layout
	var container = document.querySelector('#boxbox');
	msnry = new Masonry(container, {
	  // options
	  itemSelector: '.box',
	  columnWidth: 156
	});
	//update msnry layout
	$(window).resize(function(){
		msnry.layout();
	});

	//toggle boxes with dynamic content
	$('.dyn-content').click(function(){
		if($(this).hasClass('closed')){
			response = AjaxSubmit($(this).data());
			if(response.success){
				$(this).find('.content').html(response.html);
				startAccountBinds($(this).attr('id') + ' .inner.content');
				startBinds($(this).attr('id') + ' .inner.content');
			} else if(response.redirect) {
				window.location = response.redirect;
			} else {
				if(response.html){
					showPopup(response.html);
				} else {
					alert('NO JSON');
				}
			}
			$(this).toggleClass('closed');
			msnry.layout();
		}
	});

	//toggle boxes with static content
	$('.stat-content').click(function(){
		if($(this).hasClass('closed')){
			$(this).toggleClass('closed');
			msnry.layout();
		}
	});

	startAccountBinds('account');
}

if (settings.pagename == 'results'){

	$('a.toggle').click(function(){
		target = '#' + $(this).data('target');
		$(target).toggleClass('closed');
		$container.masonry('layout');
	});

	var $container = $('#boxbox').masonry({
	  itemSelector: '.box',
	  columnWidth: 314
	});

	$('#results a.btn').click(function(){
		var year = $('#year').val();
		var month = $('#month').val();
		window.location.href= archive_url + "/archive/" + year + "-" + month;
	});
}

if (settings.pagename == 'billing'){
	if (settings.defaultOption){ //set the default selected
		$('#paytype .radio input[value="' + settings.defaultOption + '"]').click();
	}

	//update page after radio change
	$('#paytype .radio input').change(function(){
		$('#paytype input').parent().parent().removeClass('selected');
		if ($(this).is(':checked')) $(this).parent().parent().addClass('selected');
		$('input#payinamount').parent().removeClass('has-error');
		$('#paytype .error').hide();

		if ($(this).attr('id') == 'account'){
			$('#billers').hide();
		} else {
			$('#billers').show();
			$('#billers input').prop('checked',false).parent().removeClass('checked');
		}

		if ($(this).attr('id') == 'account' || $(this).attr('id') == 'deposit'){
			$('#transactionfee').hide();
		} else {
			$('#transactionfee').show();
		}

		if ($(this).attr('id') == 'account' ||  $(this).attr('id') == 'direct'){
			$('.finalprice').html($(this).data('price'));

		} else {
			$('#transactionfee').hide();
			input = $('input#payinamount');
			if ($('input#payinamount').val() > input.data('maximum') || $('input#payinamount').val() < input.data('minimum')){
				input.parent().addClass('has-error');
				return false;
			} else {
				$('.finalprice').html(input.val()).formatCurrency({ region: settings.currencyRegion });
				return false;
			}
		}

	});

/**
	$('#billers input').change(function(){
		$('#billers .error').hide();
	});
	$('#paytype input.payinamount').change(function(){
		$('.finalprice').html($(this).val()).formatCurrency({ region: settings.currencyRegion });
	});
**/
}
if (settings.pagename == 'play'){
	//hide the play now button
	$('#slideOut .the-right').css({width: $('#slideOut .the-right').width() + 'px',height: $('#slideOut .the-right').height() + 'px'}).html('&nbsp;');

	$('.randomizeAll').click(function(){
		randomMultiLine($('.linebox .line'));
	});

	//add the binds to each line
	$('.linebox .line').each(function(){
		startLineBind($(this), true);
	});

	$('.clearAll').click(function(){
		count = $('.linebox').length;
		$('.linebox').each(function(){
			if (count > 1){ $(this).remove(); }
			count = count -1;
		});
		$('.linebox .line').each(function(){
			resetLine($(this).attr('id'));
		});
		settings.lastTicketID = 3;
		$('.formfield.addlines').removeClass('disabled');
	});

	settings.objLine = $('.linebox').first().clone();

	// SLIDER FOR MOBILE
	$('.next').click(function(){
		var target = $('#ROW1 .inner');
		var parentWidth = $('#ROW1').innerWidth();
		$('.prev').removeClass('disabled');

		var tmp = target.css('margin-left');
		var marginLeft = tmp.split("px");

		if(marginLeft[0] > (parentWidth * 2 * -1)){
			var newma = marginLeft[0] - parentWidth;
			target.animate({marginLeft:newma + 'px'});

			if ((parentWidth * -1) == marginLeft[0]){
				$(this).addClass('disabled');
			}
		} else {
			$(this).addClass('disabled');
		}
	});

	$('.prev').click(function(){
		target = $('#ROW1 .inner');
		parentWidth = $('#ROW1').innerWidth();
		$('.next').removeClass('disabled');

		tmp = target.css('margin-left');
		marginLeft = tmp.split("px");

		if(marginLeft[0] < 0){
			newma = parseFloat(marginLeft[0]) + parseFloat(parentWidth);
			target.animate({marginLeft:newma + 'px'});

			if ((parentWidth * -1) == marginLeft[0]){
				$(this).addClass('disabled');
			}
		} else {
			$(this).addClass('disabled');
		}
	});

	$(window).resize(function(){
		$('#ROW1 .inner').css({marginLeft:'0px'});

		count = $('.linebox').length;

		if ($('body').hasClass('s-device')){
			$('.linebox').each(function(){
				if (count > 1){ $(this).remove(); }
				count = count -1;
			});
		}


	});
	//move left and right on touch devices
	$(".line").touchwipe({
		wipeLeft: function() { $('.next').click();},
		wipeRight: function() {$('.prev').click(); },
		/*wipeUp: function() { alert("up"); },
		wipeDown: function() { alert("down"); },*/
		min_move_x: 40,
		min_move_y: 40,
		/* preventDefaultEvents: true */
	});

	// check and submit your lines
	$('#play form').submit(function(){
		var objOpen = $('#play form .line.open');
		//Count full lines
		var fulllines = $('#play form .line.full').length;
		if (objOpen.length > 0){
			objOpen.each(function(){
				$(this).addClass('required');
			});
			showPopup(overlaySomeEmpty.html);
			return false;
		}

		if (fulllines > 0){
			return true;
		}
		showPopup(overlayAllEmpty.html);

		return false;
	});

}
//cart and billing confirm page are really similar
if (settings.pagename == 'cart' || settings.pagename == 'billingconfirm'){
	function startCartBinds(){
		$('.removeCartItem').click(function(event){
			event.stopImmediatePropagation();
			response = AjaxSubmit({act:'removefromcart',id:$(this).data('id')});
			if (response.success == true && response.id){
				$('#cart .box').hide().html(response.html).show();
				startCartBinds();
			} else {
				if(response.html){
					showPopup(response.html);
				} else {
					alert('ERROR');
				}
				console.log(response);return false;
			}
		});

		$('.cartitemtoggle').click(function(){
			var pid = $(this).data('pid');
			$('.cartItem' + pid).toggle();
			$('#cartItem' + pid + ' .icon-zoom-in,#cartItem' + pid + ' .icon-zoom-out').toggle();
		});
	}
	startCartBinds();
}

	$('#loginform').on('shown.bs.modal', function (e) {
		return false
	});
	$(window).resize(function() {
		displayDetect();
	});
	startBinds();
}); /* END ON READY */

function getRequestReturn(response){
	console.log(response);
}
