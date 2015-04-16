/**
 * jQuery MoSelect
 * Version: 0.8.5
 * Copyright (c) 2014 Steffen Hoffmann
 * License: CC BY-NC-ND
 *			MoSelect von Steffen Hoffamnn steht unter einer
 *			Creative Commons Namensnennung-NichtKommerziell-KeineBearbeitung
 *			3.0 Unported Lizenz.
 */

/* My Own Select */
(function($){

	var moSelectOptionen = new Array();
	var callOptions = {};

	$.fn.moSelect = function(callOptions) {
		//get the element
		var element = $(this);
		//if at least one is there
		if (element.length > 0) {
			//for each selement
			element.each(function(){
				var eleOptions = '';
				var select = $(this);
 				// Create an ID if there is no ID
				if (!select.attr('id')) {
					var thisID = 'moSelect' + select.attr('name') + Math.floor(Math.random() + Math.random() * 1000);
					select.attr('id',thisID);
				} else { var thisID = select.attr('id'); }
				//get the element attributes if they are there options
				if (select.data('mode') || select.data('style') || select.data('value') || select.data('class')){ eleOptions = $.extend({},callOptions,select.data());} else {eleOptions = callOptions; }
				//Set the defaults and push them in the options Array
				var css = moGetRules(select);

				moSelectOptionen[thisID] = $.extend({
					mode: 'select', //link or select
					output: 'label', //value or label
					style: 'standalone', //standalone or bootstrap
					cols: 1, //one or 2 cols only bootstrap
					title: '', //one or 2 cols only bootstrap
					autowidth: false, //true or false
                    class: '', //additional class
					change: element.attr('onchange'), //get the onchange stuff of the element
					css: css
				}, eleOptions);
				//now create the select menu
				startSelect(select);
			});
		}
	}


	$.fn.moSelectSet = function(opt){
		element = $(this);
		var thisID = element.attr('id');

		if (moSelectOptionen[thisID].output == 'label') { var v = element.find('option[value="' + opt + '"]').html();
		} else { var v = opt; }

		$('a#d' + thisID).html(v + '<span class="caret"></span>');
		element.val(opt);
		eval(moSelectOptionen[thisID].change);
	}

	function startSelect(element){

		var thisID = element.attr('id');

		//check the output mode
		if (moSelectOptionen[thisID].output == 'label') { var v = element.find('option:selected').html();
		} else { var v = element.val();	}

		//Bootstrap Style or Select Style
		if (moSelectOptionen[thisID].style == 'bootstrap') {
			var two = '';
			var c = '';
			if (moSelectOptionen[thisID].title){
				var c = '<li role="presentation" class="dropdown-header">' + moSelectOptionen[thisID].title + '&nbsp;</li>';
			}
			if (moSelectOptionen[thisID].cols == '2'){
				var two = 'twocols';
			}

			//create the children
			element.children('option').each(function(){
				c = c + '<li><a data-option="' + $(this).attr('value') + '" class="' + $(this).attr('class') + '">' + $(this).html() + '</a></li>';
			});
			element.wrap('<div class="dropdown moselect" id="ds' + thisID + '"></div>').after('<a id="d' + thisID + '" data-toggle="dropdown" class="' + moSelectOptionen[thisID].mode + '" role="button" href="#" data-target="#">' + v + '<span class="caret"></span></a><ul class="dropdown-menu ' + two + ' ' + moSelectOptionen[thisID].class + '" aria-labelledby="d' + thisID + '" role="menu">' + c + '</ul>');
			$('#ds' + thisID + ' ul a').click(function(){
				element.moSelectSet($(this).data('option'));
			});
			element.hide();
		} else {
			element.wrap('<div class="default moselect" id="ds' + thisID + '"></div>').after('<a id="d' + thisID + '" class="' + moSelectOptionen[thisID].mode + '">' + v + '<span class="caret"></span></a>');
			element.css(moSelectOptionen[thisID].css);

			$('#ds' + thisID).hover(function(){
				element.css(moGetRules(element));
			});
			element.change(function() {
				$(this).moSelectSet($(this).val());
				$(this).css(moGetRules($(this)));
			});
		}
		//set the sizes of the select box
		if (moSelectOptionen[thisID].autowidth){
			$('a#d' + thisID).css({'width':'100%'}).parent().css({'width':'100%'});
			$('a#d' + thisID).css({'min-width':moSelectOptionen[thisID].css.width + 'px','min-height':element.height() + 'px'});
		}
		//$('a#d' + thisID).css({'min-width':element.width() + 'px','min-height':element.height() + 'px'});
	}

	function moGetRules (ele){
		return {
			'position': 'absolute',
			'left': 0,
			'top': 0,
			'bottom': 0,
			'right': 0,
			/*'width': ele.width(),
			'height': ele.height(),*/
			'opacity': 0.00001,
			'margin': 0,
			'padding': 0,
		};
	}

})(jQuery);