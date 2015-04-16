/**
 * jQuery moUpdateChecker
 * Version: 0.1
 * Copyright (c) 2014 Steffen Hoffmann
 * License: CC BY-NC-ND
 *			moUpdateChecker von Steffen Hoffmann steht unter einer
 *			Creative Commons Namensnennung-NichtKommerziell-KeineBearbeitung
 *			3.0 Unported Lizenz.
 */

(function($){
   var MoUpdatechecker = function(element, defaults)
   {
       var elem = $(element);
       var obj = this;
       var options = $.extend({
			name		: 'req', //status or data
			interval	: 5000, //get the onchange stuff of the element
			idleinterval: 60000, //get the onchange stuff of the element
			data_url	: '', //url to call
			status_url	: '', //url where you get the status
			type		: 'data', //status or data
			pause		: false, //status or data
			onfocus		: 'status', //which request on focus
			pausecounter: 0,
			skipfirst	: true,
			token		: '',
			running		: false,
			firstrun	: true,
			addpost		: false //additional post data
       }, defaults || {});

	   	var focusCheck = function(){
			return window.document.hasFocus();
		}

		var startInterval = function(){
			clearInterval(options.timer);
			options.timer = setInterval(function(){getRequest()},options.interval);

			$(window).focus(function(){
				//getRequest();
				clearInterval(options.timer);
				options.focusreturn = true;
				options.timer = setInterval(function(){getRequest()},options.interval);
			});
		}

		var getRequest = function(){
			//console.log(options.name,options.addpost);
			if (options.firstrun == true){ options.firstrun = false;return true;}

			if (options.focusreturn == true && options.onfocus == 'data'){
				options.focusreturn = false; getData({'focus':true});return true;
			}

			if(!focusCheck()){
				clearInterval(options.timer);
				options.timer = setInterval(function(){getRequest()},options.idleinterval);
			}

			if (options.type == 'status'){
				getStatus();
			} else {
				getData();
			}
			return true;
		}

		var getStatus = function(){
			// Using JSONP
			if (options.pause == false || options.pausecounter >= 10){
				options.pause = true;
				options.pausecounter = 0;
				$.ajax({
					url: options.status_url,
					jsonp: 'callback',
					dataType: "jsonp",
					data: {/*demo:1,*/act:options.name,token:options.token},
					success: function(response) {
						options.pause = false;
						if(response.updates){
							getData(response.updates);
						} else if(response.redirect){
							window.location.href = response.redirect;
						}
					}
				});
			}
			options.pausecounter = options.pausecounter + 1;
		}

		var getData = function(datatypes){
			if(options.running == true) {
				setTimeout(function(){getData(datatypes)},3000);
			} else {
				options.running = true;
				if (options.addpost){
					datatypes = $.extend(datatypes, options.addpost);
				}
				data = $.extend({act : options.name}, datatypes);
				$.ajax({
					url: options.data_url,
					type: "POST",
					cache: false,
					data: data,
					dataType: "text",
					success: function (retData) {

						if (retData){
							if (retData.charAt(0)  == '{'){
								reqReturn = jQuery.parseJSON(retData);
								redirect = window.location.href;
							} else {
								reqReturn = jQuery.parseJSON( '{"error":"parseerror"}');
								redirect = window.location.href;
							}

							if(reqReturn["error"] == true || reqReturn["error"] == "parseerror") {
								window.location.href = redirect;
								return false;
							} else {
								$.isFunction(options.onReturn) && options.onReturn.call(reqReturn);
							}
						}
						options.running = false;
					},
					complete: function (ddd) {
						options.running = false;

					}
				});
			}
		}

		this.getOptions = function(opt){
			if(opt != undefined){
				return options[opt];
			} else {
				return options;
			}

		};
		this.setOptions = function(updates){
			options = $.extend(options, updates);
			if(updates.interval){
				//startInterval();
			}
			return options;
		};

		focusCheck();
		setTimeout(function(){ startInterval() },1000);

   };

   $.fn.moUpdatechecker = function(options)
   {
       return this.each(function()
       {
           var element = $(this);
           // Return early if this element already has a plugin instance
           if (element.data('moUpdatechecker')) return;
           // pass options to plugin constructor
           var moUpdatechecker = new MoUpdatechecker(this, options);
           // Store plugin object in this element's data
           element.data('moUpdatechecker', moUpdatechecker);
       });
   };
})(jQuery);
