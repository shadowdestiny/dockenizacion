$(document).ready(function(){

	id = $('.pagename').attr('id');

	$('.sidebar-toggle').click(function(){
		 $('#sidebar').toggleClass('hidden-xs');
	});
	$('.main').click(function(){
		 $('#sidebar').addClass('hidden-xs');
	});

	$('body').on('hidden.bs.modal', '#acpmodal', function () {
		$(this).removeData('bs.modal');
		 $('.modal-content').html('');
	});

	$('#reportrange').daterangepicker(
		{
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
			'Last 7 Days': [moment().subtract('days', 6), moment()],
			'Last 30 Days': [moment().subtract('days', 29), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
		  },
			format: 'YYYY-MM-DD',
			startDate: $('#reportrange #start').val(),
			endDate: $('#reportrange #start').val()
		},
		function(start, end, e) {
			//$('#displaytype').val($(e).data('type'));
			$('#reportrange span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
			$('#reportrange #start').val(start.format('YYYY-MM-DD'));
			$('#reportrange #end').val(end.format('YYYY-MM-DD'));
		}
	);

	if (id == 'translationlist' || id == 'countries' || id == 'currencies' ){
	 	$.fn.editable.defaults.mode = 'inline';
		$('.editable').editable({
			success: function (response, newValue) {
						if (response.slice(0, 1) == '{') {
							response = jQuery.parseJSON(response);
						} else {
							response = jQuery.parseJSON('{"error":"parse_error", "value":"not_logged_in"}');
						}
						if(response.data){
							data = response.data;
							$.each(data,function(key,value){
								$('td[data-id=' + response.id + '] td.' + key).html(value);
							});
						}
					if (response.error)
						return response.value; //msg will be shown in editable form
					}
			}
		);
	}

	$('#addlang').submit(function(){
		if($(this).find('select[name="language"]').val() != 0){ return true; } else { return false; }
	});
	$('input[type=file]').bootstrapFileInput();

	startBinds();


	$('#getdrawresults').click(function(){
		$(this).find('.glyphicon').remove();
		var defaultText = $(this).html();
		$(this).html('<i class="glyphicon glyphicon-refresh glyphicon-spin"></i> ' + defaultText);

		response = AjaxSubmit($(this).data('href'),$(this).data());
		if(response.success){
			$.each(response.data,function(key,value){
				$('input[name=' + key +'][type="number"]').val(value);
				$('input[name=' + key +'][type="radio"][value=' + value + ']').click();
			});
			$.each(response.winners,function(key1,value1){
				$.each(value1,function(key2,value2){
					$('input[name="winner[' + key1 +'][' + key2 +']"]').val(value2);
				});
			});
			$.each(response.prizes,function(key1,value1){
				$.each(value1,function(key2,value2){
					$('input[name="prize[' + key1 +'][' + key2 +']"]').val(value2);
				});
			});
		} else {
			alert('error');
		}
		$(this).html('<i class="glyphicon glyphicon-ok"></i> ' + defaultText);
	});

	$('#infobox').click(function(){
		$(this).toggleClass('open');
	});

});

function AjaxSubmit(url,params) {

	var obj = {};

	$.ajax({
		type: 'POST',
		url: url,
		async: false,
		data: params,
		datatype: "JSON",
		success: function(data) {
			if (data){
				try {
					response = jQuery.parseJSON(data);
				}
				catch(e) {
					response = jQuery.parseJSON( '{"error":"parse_error"}' );
				}
				if (response.success != null){
					returnvar = response;
				} else {
					returnvar = response;
				}
			} else { returnvar = jQuery.parseJSON( '{"error":"parse_error"}' );}
		}
	});
	return returnvar;
}

function startBinds(element) {//Initialisieren
	var position = '';
	if (element != undefined && element != ''){
		position = '#' + element + ' ';
	}


	// Filament datepicker
	$(position + ' .datepicker').datepicker({format:'yyyy-mm-dd'});
	$(position + ' #startdate,' + position + ' #enddate').daterangepicker({dateFormat: 'yy-mm-dd'});

	$(position + ' a.popup').click(function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		e.stopPropagation();
		var data = $(this).data();
		var url = '';
		if(!$(this).data('href')){
			$.extend(data, {href:$(this).attr('href')});
		}
		Popup(data);
		$('#acpmodal .modal-content').load();
		$('#acpmodal').hide();
	});

	$(position + ' .tip').tooltip({'delay': { show: 500, hide: 100 }});
}

function showPopup(c){
	$('#acpmodal .modal-content').html('').html(c);
	$('#acpmodal').modal({backdrop:'static',keyboard:false}).modal('show');
	startBinds('acpmodal');
}

function Popup(submitData){
	$.extend(submitData, {popup:'true'});
	href=submitData.href;
	delete submitData.href;
	var response = '';
	$.ajax({
		url: href,
		type: "GET",
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
				response = data;
			}
			$('#loader').remove();
			showPopup(response);
		}
	});
}
function getRequestReturn(response){
	console.log(response);
	if(response.sessions){
		session  = response.sessions;
		$('#infobox .user span,#infobox .user .details').html(' ');
		$.each(session,function(key,value){
			if (key != 'total'){
				$('#infobox .user .details').append('<strong>' + value + '</strong>x ' + key + ' ');
			} else {
				$('#infobox .user span').append(value + ' online');
			}
		});
	}
	if(response.emails){
		session  = response.emails;
		$('#infobox .emaillist span,#infobox .emaillist .details').html(' ');
		$.each(session,function(key,value){
			if (key != 'total'){
				$('#infobox .emaillist .details').append('<strong>' + value + '</strong>x ' + key + ' ');
			} else {
				$('#infobox .emaillist span').append(value + ' in cue');
			}
		});

	}
}
