/**
 *  Lottery
 */
settings.lastTicketID = 3;
settings.blockAddLine = false;

/* Click on Star or Number */
function updateLineField(ele){
	target = ele.parent().parent();
	if (!ele.hasClass('selected')){
		if (checkFieldCount(target,ele)){
			ele.addClass('selected');
		}
	} else {
		ele.removeClass('selected');
	}
	updateLineCount(target);
}

/* return false if to much numbers/stars are selected */
function checkFieldCount(target,ele){

	var numbers = target.find('.selected.playnumber');
	var stars = target.find('.selected.playstar');
	var type = 'playnumber';
	if (ele.hasClass('playstar')){ type = 'playstar'}

	var maxnumb = 10; var maxstar = 5;

	if ((type == 'playnumber' && numbers.length < maxnumb) || (type == 'playstar' && stars.length < maxstar)) {
		return true;
	} else {
		return false;
	}
}

//updates each line and get all selected numbers / stars
function updateLineCount(target){
	var numbers = target.find('.selected.playnumber');
	var stars 	= target.find('.selected.playstar');
	var arrLineNum = Array();
	var arrLineSta = Array();


    //check if multi
	if (numbers.length > 5 || stars.length > 2){
		target.find('.options a.popup').addClass('selected');
	} else {
		target.find('.options a.popup').removeClass('selected');
	}

    //check finish/full
	if (numbers.length >= 5 && stars.length >= 2) {

		target.find('.playnumber.selected').each(function(){
			arrLineNum.push($(this).data('number'));
		});
		target.find('.playstar.selected').each(function(){
			arrLineSta.push($(this).data('number'));
		});

		target.find('input.num').val(arrLineNum.toString());
		target.find('input.sta').val(arrLineSta.toString());
		target.addClass('full').removeClass('required').removeClass('open');
	} else if (numbers.length == 0 && stars.length == 0) {
        target.removeClass('full').removeClass('required').removeClass('open');
    } else {
		target.find('input.num').val('');
		target.find('input.sta').val('');
		target.removeClass('full').removeClass('required').addClass('open');
	}
	updatePrice();
}

//resets a line to 0 selected items
function resetLine(id){
	var target = $('#' + id);
	var selected = target.find('.selected').removeClass('selected');
	target.find('input.num').val('');
	target.find('input.sta').val('');
	updateLineCount(target);
}

//call random function for a sepcial line
function randomSingleLine(id, nums, stars) {
	var x = 0;

	var lottoTimer = setInterval(function(){
		x++;
		if(x == 10){
			clearInterval(lottoTimer);
		}
		randomLine(id, nums, stars);

	},100);
}

//call random functionality for all lines of the page called by ".randomizeAll"
function randomMultiLine(elements) {
	var x = 0;

	var lottoTimer = setInterval(function(){
		x++;
		if(x == 5){
			clearInterval(lottoTimer);
		}
		elements.each(function(){
			randomLine($(this).attr('id'), 5, 2);
		});

	},100);
}

//random function is called by the upper functions
function randomLine(id, nums, stars){

	if (nums != undefined && stars != undefined){
		var maxNums = nums;
		var maxStars = stars;
	} else {
		var maxNums = 5;
		var maxStars = 2;
	}

	var target = $('#' + id);
	var arrStars = Array();
	var arrNumbers = Array();

	resetLine(id);

	while (arrStars.length < maxStars){
		num = getRandom(0, 10);
		if (arrStars.indexOf(num) < 0){
			arrStars.push(num);
			target.find('.playstar').eq(num-1).addClass('selected');
		}

	}

	while (arrNumbers.length < maxNums){
		num = getRandom(0, 49);
		if (arrNumbers.indexOf(num) < 0){
			arrNumbers.push(num);
			target.find('.playnumber').eq(num-1).addClass('selected');
		}
	}

	updateLineCount(target);
}

//collects all full lines and updates the displayed price
function updatePrice() {
	var factor_day = $('.day:checked').length;
	var factor_weeks = $('select[name="weeks"]').val();
	var factor = factor_day * factor_weeks;
	var price = 0;
//	var lineprice = 2.35;
	fullLines = $('#play .linebox .line.full');

	if (fullLines.length){
		fullLines.each(function(){
			var objNumbers = $(this).find('.playnumber.selected').length;
			var objStars = $(this).find('.playstar.selected').length;
			price = price + parseFloat(settings.arrPrices[objNumbers][objStars]);
		});
	}
	//if (price > 0) { $('input#addcard').attr('disabled',false); } else { $('input#addcard').attr('disabled',true); }
	price = (price * settings.changeRate) * factor;
	$('.price .amount').html(price).formatCurrency({ region: settings.currencyRegion });
}

/**
 * Add more lines functionality at the moment the maximum is set to 6
 * If you add the edit functionality, "settings.objLine" needs to be reseted
 */
function addLines() {
	if(settings.blockAddLine == false){
		if (!$('.formfield.addlines').hasClass('disabled')) {
			settings.blockAddLine = true;
			var box = settings.objLine.clone();
			var lineID = settings.lastTicketID + 1;
			var boxID = $('.linebox').first().data('id') + 1;
			box.attr('id','ROW' + boxID).attr('data-id', boxID).data('id',boxID);
			box.find('.line').each(function(){
				$(this).find('input.num').attr('name','numbers[' + lineID + ']');
				$(this).find('input.sta').attr('name','stars[' + lineID + ']' );
				$(this).find('.options a.popup').data('line',lineID);
				$(this).attr('id','playline' + lineID);
				startLineBind($(this));
				lineID++;
			});
			box.insertBefore($(".linebox").first());
			settings.lastTicketID = lineID - 1;
			if ($('#play .line').length >= settings.maxLineCount){
				$('.formfield.addlines').addClass('disabled');
			} else {
				$('.formfield.addlines').removeClass('disabled');
			}
			settings.blockAddLine = false;
		}

	}
}

//add the binds to the lines after adding to the DOM
function startLineBind(element, popup) {
	if (!popup) {
		element.find('a.popup').click(function(){
			Popup($(this).data());
		});
	}

	element.find('a.randomLine').click(function(){
		randomSingleLine(element.attr('id'));
	});
	element.find('a.resetLine').click(function(){
		resetLine(element.attr('id'));
	});

    element.find('.playnumber').click(function(){
        updateLineField($(this));
    });

    element.find('.playstar').click(function(){
        updateLineField($(this));
    });
}

//get a random number
function getRandom(min, max) {
	if(min > max) {
		return -1;
	}

	if(min == max) {
		return min;
	}

	var r;
	do {
		r = Math.random();
	}
	while(r == 1.0);
	return min + parseInt(r * (max-min+1));
}

//updates the formular after selecting a day
function playdayselect(day){
	var selected = $('#' + day).prop('checked');

	if (!selected) {
		if (day ==  'friday'){
			target = 'tuesday';
		} else {
			target = 'friday';
		}
		if (!$('#' + target).prop('checked')) {
			$('#' + target).click();
		}
		$('select[name="weeks"]').moSelectSet('1');
	}

	$('select[name="nextdraw"] option').hide();
	$('input.day').each(function(){
		var myday = $(this).attr('id');

		if ($(this).prop('checked')){
			if (myday == 'friday'){
				$('select[name="nextdraw"] option.day5').show();
			} else {
				$('select[name="nextdraw"] option.day2').show();
			}
			var breakout = false;
			$('select[name="nextdraw"] option').each(function(){
				if ($(this).css('display') != 'none' && breakout == false){
					breakout = true;
					$('select[name="nextdraw"]').val($(this).val());
				}
			});
		}
	});

	if($('select[name="nextdraw"] option:first').css('display') != 'none'){
		selected = $('select[name="nextdraw"] option:first').val();
	} else {
		selected = $('select[name="nextdraw"] option:first').next().val();
	}
	$('select[name="nextdraw"]').moSelectSet(selected);
	updatePrice();
}

//updates the formular after selecting a duration
function playdurationselect(select){
	if (select.val() > 1){
		$('input.day').each(function(){
			if(!$(this).prop('checked')){
				$(this).click();
			}
		});
	}
	updatePrice();
}

//updates the formular after click the subscription button
function playaboselect(){
	if ($('#recurring').prop('checked')){
		$('input.day').each(function(){
			if(!$(this).prop('checked')){
				$(this).click();
			}
		});
	}
}


/* Lottery end */

//Detects the width of the browser, add a class to body.
function displayDetect(){
	var screenwith = $(window).width();
	var body = $('body');
	if (screenwith < 660){
		body.attr('class','s-device');
	} else if (screenwith < 980){
		body.attr('class','m-device');
	} else if (screenwith < 1182){
		body.attr('class','l-device');
	} else {
		body.attr('class', '');
	}
}

/**
 * For every element with the class "popup" (usually links). The elements data (submitData) will be grabbed and submitted. Expect JSON response. Calls showPopup to display the response
 */
function Popup(submitData){
	$('body').append('<div id="loader"></div>');
	$.extend(submitData, {popup:'true'});
	var response = '';
	$.ajax({
		url: submitData.href,
		type: "POST",
		data: submitData,
		async: false,
		datatype: "JSON",
		success: function (data) {
			if (data.slice(0, 1) == '{'){
				data = jQuery.parseJSON(data);
				if(data.redirect){
					window.location = data.redirect;
				} else {
					response = data.html;
				}

			} else {
				alert('NO JSON');
				response = data;
			}
			$('#loader').remove();
			showPopup(response);
		}
	});
}


/**
 * Displays any HTML Code in an Modalbox
 */
function showPopup(c){
	resetModalHeadline();
	$('#Overlay .modal-body').html('').html(c);
	$('#Overlay').modal({backdrop:'static',keyboard:false}).modal('show');
	startBinds('Overlay');
}

/** Set a headline of the modal box **/
function setModalHeadline(e,a){
	$('#Overlay .modal-header').addClass('headline');
	$('#Overlay .modal-header strong').html(e);
	$('#Overlay .modal-header span').html(a);
}

/** resets a headline in a modal box **/
function resetModalHeadline(){
	$('#Overlay .modal-header').removeClass('headline');
	$('#Overlay .modal-header strong').html('');
	$('#Overlay .modal-header span').html('');
}


/**
 *  Initialise all default bindings. Is called directly after document ready and showPopup
**/
function startBinds(element) {
	//Initialisieren
	var position = '';
	if (element != undefined && element != ''){
		position = '#' + element + ' ';
	}

	$(position + 'select.selectmenu').moSelect({autowidth: true,style:'standalone'});

	/** Checkboxes and Radiobuttons */
	$(position + 'label.checkbox input,' + position + 'label.radio input').each(function(){

		var status = $(this).prop('checked');
		if (status == true){
			$(this).parent().addClass('checked');
		}
		if (status == false){
			$(this).parent().removeClass('checked');
		}
	});

	/** Checkboxes and Radiobuttons */
	$(position + 'label.checkbox input').click(function(){

		var status = $(this).prop('checked');
		$(this).parent().removeClass('error');
		if (status == true){
			$(this).parent().addClass('checked');
		}
		if (status == false){
			$(this).parent().removeClass('checked');
		}
	});

	$(position + 'label.radio input').click(function(){
		name = $(this).prop('name');
		status = $(this).prop('checked');
		$(position + 'label.radio input[name="' + name + '"]').parent().removeClass('checked').removeClass('error');
		$(position + 'label.radio input[name="' + name + '"]:checked').parent().addClass('checked');
	});

	$(position + ' a.popup').click(function(){
		var data = $(this).data();
		Popup(data);
		return false;
	});

	$(position + '.tooltipp').tooltip()
	$(position + 'input.error').tooltip().tooltip('show').change(function(){ resetFormErrors($(this)); });
	$(position + 'table').each(function(){
		if (!$(this).hasClass('table')){
			$(this).addClass('table').addClass('table-striped');
		}
	});
}

/** collects all formular data and returns JSON **/
function formCollect(form) {
	var objParams = {href:form.attr('action')};
	var input = form.find('input, textarea').toArray();
	var select = form.find('select').toArray();

	//input & textarea
	$.each(input, function(i, item) {
		var obj = {};
		var name = $(this).attr('name');

		if ($(this).attr('type') != 'checkbox' &&  $(this).attr('type') != 'radio') {
			var value = $(this).val();
			obj[name] = value;
			$.extend(objParams, obj);
		} else if ($(this).attr('type') == 'radio') {
			var value = form.find('input[name="' + name + '"]:checked').val();
			obj[name] = value;
			$.extend(objParams, obj);
		} else if ($(this).attr('type') == 'checkbox') {
			if($(this).prop('checked') == false || !$(this).is(':checked')){
				var value = 0;
			} else {
				var value = $(this).val();
			}

			arrSplit = name.split("[");

			if (arrSplit.length > 1) {
				if (value != 0){
					name = arrSplit[0];

					if (objParams[name] == undefined){
						value = Array(value);
					} else {
						var oldval = objParams[name];
						var newval = value;
						var value = oldval.concat(newval);
					}
					obj[name] = value;
					$.extend(objParams, obj);
				}
			} else {
				obj[name] = value;
				$.extend(objParams, obj);
			}

		}

	});

	//select
	$.each(select, function(i, item) {
		var obj = {};
		var name = $(this).attr('name');
		var value = $(this).val();
		obj[name] = value;
		$.extend(objParams, obj);
	});

	return objParams;
}

//Submit JSON to the ajr-(AJAX)-controller. Expect JSON response, return JSON
function AjaxSubmit(params) {
	var returnvar = '';
	var url = '/' + lang + '/ajr';

	if (params.href){
		url = params.href;
		delete params.href;
	}

	$.ajax({
		type: "POST",
		url: url,
		async: false,
		data: params,
		datatype: "JSON",
		success: function(data) {
			if (data){
				if (data.slice(0, 1) == '{'){
					returnvar = jQuery.parseJSON(data);
				} else {
					returnvar = jQuery.parseJSON( '{"error":"parse_error"}' );
				}
			} else { returnvar = jQuery.parseJSON( '{"error":"parse_error"}' ); }
		}
	});

	return returnvar;
}

var errorclass = 'error';
var checkboxerrorclass = 'error';

/**
 *
 * detects if the given element is visible and on screen
 * THX -> http://upshots.org/javascript/jquery-test-if-element-is-in-viewport-visible-on-screen
 **/

$.fn.isOnScreen = function(){

    var win = $(window);
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

	var bounds = this.offset();
	bounds.right = bounds.left + this.outerWidth();
	bounds.bottom = bounds.top + this.outerHeight();

	var status = (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
	if (this.is(':hidden')) {
		status = false;
	}
	return status ;
};

var objNextPage = {};
var blockNextPage = false;
//preload functionality for news and results
function requestNextPage(){
	currentPage = currentPage + 1;
	if (currentPage > pageCount) { objNextPage = {};return false;}
	var response = AjaxSubmit({href: window.location,ajaxload: true, ajaxpage: currentPage});
	if (response.success){
		objNextPage = response;
	} else {
		showPopup(response.html);
	}
}
//display the preloaded page
function showtNextPage(){
	if (blockNextPage == false){
		blockNextPage = true;
		if (objNextPage.html && (currentPage  <= pageCount)){
			showtNextPageReturn(objNextPage);
		}
		requestNextPage();
		blockNextPage = false;
	}
}

function showtNextPageReturn(objNextPage){
	if(settings.pagename == 'results'){
		$('#' + settings.pagename + ' .container').append(objNextPage.html);
		elems = $('#' + settings.pagename + ' .container .box.masnews');
		$('#boxbox').masonry('appended', elems);
		$('.masnews a.toggle').click(function(){
			target = '#' + $(this).data('target');
			$(target).toggleClass('closed');
			$('#boxbox').masonry('layout');
		});
		$('#' + settings.pagename + ' .container .box.masnews').removeClass('masnews');
		$('#' + settings.pagename + ' #paginator').html(objNextPage.pagination);

	} else {
		if (currentPage <= pageCount){
			$('#paginator').remove();
		}
		$('#' + settings.pagename + ' .container').append(objNextPage.html);
	}
}

/** check if everything is ok **/
function checkBillingFormular(){
	if ($('input[name="billing_type"]:checked').val()){
		if ($('input[name="billing_type"]:checked').attr('id') == 'deposit'){
			input = $('input#payinamount')
			if ($('input#payinamount').val() > input.data('maximum') || $('input#payinamount').val() < input.data('minimum')){
				input.parent().addClass('has-error');
				$('#paytype .error').show();
				return false;
			}
		} else if ($('input[name="billing_type"]:checked').attr('id') == 'account') {
			$('input[name="biller"]').removeAttr('checked');
			return true;
		}
	} else {
		alert('you need to choose xyz');
		return false;
	}
	if (!$('input[name="biller"]:checked').val()){ $('#billers .error').show(); return false;}

}

//checks if the selected country has in the ibancoutry list
function checkIbanCountry(){
	tval = $('#country').val();
	if ($.inArray(tval,ibanonly) >= 0){
		return true;
	}
	return false
}

function initIBAN(){
	if(checkIbanCountry()){
		$('#accountnumber').attr('disabled','disabled').val('');
		$('#iban').removeAttr('disabled');
	} else {
		$('#accountnumber,#iban').removeAttr('disabled');
	}
}