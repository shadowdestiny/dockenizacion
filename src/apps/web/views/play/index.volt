{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/w/css/play.css">
{% endblock %}
{% block template_scripts %}
{#<script>{% include "play/index.js" %}</script>#}
<script>
	var ajaxFunctions = {
		playCart : function (params) {
			$.ajax({
				url: '/ajax/play-temporarily/temporarilyCart/',
				data: params,
				type: 'POST',
				dataType: "json",
				success: function(json) {
					if(json.result = 'OK') {
						location.href = json.url;
					}
				},
				error: function (xhr, status, errorThrown) {
					//EMTD manage errrors
				},
			});
		}
	};
</script>
{% set dates_draw = play_dates|json_encode %}
<script>
var draw_dates = <?php echo $dates_draw ?>;
var price_bet = {{ single_bet_price }};
var currency_symbol = '<?php echo $currency_symbol ?>';
</script>
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
			<h1 class="h3 draw"><span class="br">{{ language.translate("Next Draw") }}:</span> Friday 29 May 19:20</h1>
			<span class="h1 jackpot">
				Jackpot
				{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
				{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
			</span>
		</header>
		<div class="gameplay" id="gameplay"></div>
		<div class="media"></div>
	</div>
</main>
{% endblock %}