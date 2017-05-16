{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/play.css">{% endblock %}
{% block template_scripts %}
<script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
	var ajaxFunctions = {
		playCart : function (params){
			$.ajax({
				url:'/ajax/play-temporarily/temporarilyCart/',
				data:params,
				type:'POST',
				dataType:"json",
				success:function(json){
					if(json.result = 'OK'){
						location.href = json.url;
					}
				},
				error:function (xhr, status, errorThrown){
					{# //EMTD manage errors #}
				},
			});
		}
	};
	{% set dates_draw = play_dates|json_encode %}
	var draw_dates = <?php echo $dates_draw ?>;
	var next_draw_format = '<?php echo $next_draw_format ?>';
	var price_bet = {{ single_bet_price }};
	var next_draw = <?php echo $next_draw; ?>;
	var openTicket = <?php echo $openTicket; ?>;
	var currency_symbol = '<?php echo $currency_symbol ?>';
	var automatic_random = '<?php echo $automatic_random; ?>';
	var discount_lines_title = '{{ language.translate('tittle_multiple') }}';
	var addLinesBtn = '{{ language.translate('addLines_btn') }}';
	var randomizeAllLines = '{{ language.translate('randomizeAll_btn') }}';
	var clearAllLines = '{{ language.translate('clearAll_btn') }}';
	var buyForDraw = '{{ language.translate('buyForDraw') }}';


	{#añadir aqui el translate#}
	var discount_lines = '<?php echo $discount_lines; ?>';
	var draws_number = '<?php echo $draws_number; ?>';
	var discount = '<?php echo $discount; ?>';


	if(openTicket){
		showModalTicketClose();
	}

	function showModalTicketClose(){
		$("#closeticket").easyModal({
			top:100,
			autoOpen:true,
			overlayOpacity:0.7,
			overlayColor:"#000",
			transitionIn:'animated fadeIn',
			transitionOut:'animated fadeOut',
			overlayClose: false,
			closeOnEscape: false
		});
	}

	function showModalTicketCloseByLimitBet(){
		$("#closeticketbylimitbet").easyModal({
			top:100,
			autoOpen:true,
			overlayOpacity:0.7,
			overlayColor:"#000",
			transitionIn:'animated fadeIn',
			transitionOut:'animated fadeOut',
			overlayClose: false,
			closeOnEscape: false
		});
	}

{% endblock %}
{% block template_scripts_after %}
<script src="/w/js/react/play.js"></script>
{% endblock %}

{% block bodyClass %}play{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "play"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<header class="bg-top cl">
			<h1 class="h3 draw">{{ language.translate("shortInstruction") }}</h1>
			<span class="h1 jackpot">
				Jackpot
				{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
				{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
			</span>
		</header>
		<div class="gameplay" id="gameplay"></div>
		<div class="media"></div>
	</div>
	<div id="closeticket" class="modal" style="width: 1000px;height: 500px;">
		<div style="text-align: center;color:white">
			It is too late to buy EuroMillions tickets for the draw held in Paris tonight at 20:45 CET.
			In a few moments you will be able to purchase EuroMillions tickets for the next draw that will take place on Tuesday.

			<br><br>Thank you for your pacience.<br>

			The EuroMillions.com Support Team
		</div>
	</div>
	<div id="closeticketbylimitbet" class="modal" style="width: 1000px;height: 500px;">
		<div style="text-align: center;color:white">
			It is too late to buy EuroMillions tickets for the draw held in Paris tonight at 20:45 CET.
			You can be able to purchase EuroMillions tickets for the next draw accessing again to <a href="/">Euromillions.com</a> .

			<br><br>Thank you for your pacience.<br>

			The EuroMillions.com Support Team
		</div>
	</div>
</main>
{% endblock %}
