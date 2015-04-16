/**
 * jQuery MoValidator
 * Version: 1.0
 * Copyright (c) 2014 Steffen Hoffmann
 * License: CC BY-NC-ND
 *			MoSlider von Steffen Hoffamnn steht unter einer
 *			Creative Commons Namensnennung-NichtKommerziell-KeineBearbeitung
 *			3.0 Unported Lizenz.
 * Requires: JS Translate
 * 
 * Features:
 *
 *  <input>:
 *  *	reqiured and check to placeholder
 *  *   not empty
 *  *   minlength && maxlenght
 *  *   TYPE: text, checkbox
 *  *   type=email hack with class
 *  *   add and remove error class
 *
 *  <textarea>:
 *  *	reqiured and check to placeholder
 *  *   not empty
 *  *	minlength && maxlenght
 *  *	add and remove error class
 *
 *  Coming Soon:
 *  *   Check Equual fields
*/

//Debug Modus
var debug = true;
var stopp = false;
var error = false;
var msgcount = 0;
var message = new Array();

function checkForm(element) {

	stopp = false;
	if ($.isPlainObject(element)){
		form = element;
	} else {
		form = $('#' + element);
	}

	/* Input Fields */
	form.find('input').each(function() {
		if (checkFormElement(form, $(this), 'input') == 'ErrFail'){ stopp = true;}
	});

	/* Textarea */
	form.find('textarea').each(function() {
		if (checkFormElement(form, $(this), 'textarea') == 'ErrFail'){ stopp = true;}
	});

	/* Select */
	form.find('#birth_day_Reg').each(function() {
		if (checkFormElement(form, $(this), 'select') == 'ErrFail'){ stopp = true;}
	});

	if(stopp == true){
		if (debug == true) alert('stopp');
		return false;
	} else {
		return true;
	}
	return false;

}

function checkFormElement(form, Ele, type) {
	var error = false;
	var msgcount = 0;
	var message = new Array();

	/* required */
	if(Ele.prop('required') || Ele.hasClass('required')){
		/* input */
		if (Ele.val() == '' ||  Ele.val() == undefined || Ele.val() == Ele.prop('placeholder')) {
			error = true;
			message[msgcount] = jsTranslate("required");
			if (debug == true) alert('required ' + Ele.prop('name'));
			msgcount++;
		}

		/* Checkbox */
		if (Ele.prop('type') == 'checkbox' && (Ele.prop('checked') == false || !Ele.is(':checked'))) {
			error = true;
			if (debug == true) alert('required but not checked ' + Ele.prop('name'));
			message[msgcount] = jsTranslate("required");
			msgcount++;
		}

		/* Radiobox */
		if (Ele.prop('type') == 'radio') {
			myname = Ele.prop('name');
			myname = myname.replace('[', '');
			myname = myname.replace(']', '');
			if ($('input.' +  myname + ':checked').length == 0){
				error = true;
				if (debug == true) alert('required but not checked ' + Ele.prop('name'));
				message[msgcount] = jsTranslate("required");
				msgcount++;
			}
		}
	}


	/* minlenght */
	if (Ele.attr('minlength') && Ele.attr('minlength') > Ele.val().length) {
		error = true;
		if (debug == true) alert('minlength' + Ele.prop('name'));
		message[msgcount] = jsTranslate("minlength", Ele.attr('minlength'));
		msgcount++;
		
	}

	/* maxlenght */
	if (Ele.prop('maxlength') && Ele.prop('maxlength') != '-1' && Ele.prop('maxlength') < Ele.val().length) {
		error = true;
		if (debug == true) alert('maxlength' + Ele.prop('name'));
		message[msgcount] = jsTranslate("maxlength", Ele.attr('maxlength'));
		msgcount++;
	}

	if(Ele.hasClass('equal')){
		var nextval = $('input[name=' + Ele.prop('name') + '_equal]').val();
		if (Ele.val() != nextval ) {
			if (debug == true) alert('Fields are not equal');
			error = true;
	 	}
	 	message[msgcount]  = jsTranslate("notmatch");
	 	msgcount++;
	}

	if(Ele.hasClass('required_agb')){
		if (Ele.prop('type') == 'checkbox' && (Ele.prop('checked') == false || !Ele.is(':checked'))) {
			error = true;
			if (debug == true) alert('required_agb but not checked ' + Ele.prop('name'));
			message[msgcount] = jsTranslate("required_agb");
			msgcount++;
		}
	}

	if(Ele.hasClass('email') || Ele.prop('type') == 'email'){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var address = Ele.val();

		if(reg.test(address) == false) {
			if (debug == true) alert('Invalid Email Address');
			error = true;
		 	message[msgcount] = jsTranslate("email_bad");
		 	msgcount++;
	 	}
	}

	if(Ele.hasClass('password') || Ele.prop('type') == 'password') {
		var reg = /^[a-zA-Z0-9\-_ !\"§$%&()=#+*.,:;@€\/]*$/;
		var pwd = Ele.val();
		if(reg.test(pwd) == false) {
			if (debug == true) alert('Invalid Password');
			message[msgcount] = jsTranslate("pwd_bad_string");
			msgcount++;
			error = true;
	 	}
	}

	if(Ele.hasClass('birthday')) {
		var day = Ele.val();
		var month =  $('#birth_month_Reg').val();
		var year = $('#birth_year_Reg').val();
		var age = 18;
		var mydate = new Date();
		var currdate = new Date();

		mydate.setFullYear(year, month-1, day);
		currdate.setFullYear(currdate.getFullYear() - age);

		if(currdate - mydate < 0) { // ist noch keine 18 Jahre alt
			if (debug == true) alert('To Young');
			message[msgcount] = jsTranslate("birthdate_not_18");
			msgcount++;
			error = true;
		}
	}

	/* if Fail */
	if(error == true){
		resetFormErrors(Ele);
		returnFormErrors(Ele,message,form);
		return 'ErrFail';
	} else {
		resetFormErrors(Ele);
		return true;
	}

}