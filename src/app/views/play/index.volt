{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/play.css">{% endblock %}
{% block template_scripts %}
<script>{% include "play/index.js" %}</script>
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
		<header class="bg-top">
			<div class="cols">
				<div class="col8">
					<h1 class="h3"><span class="br">{{ language.translate("Next Draw") }}:</span> Friday 29 May 19:20</h1>
					<span class="h1">
						Jackpot
						{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
						{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
					</span>
				</div>
				<div class="col4">
					<a href="javascript:void(0);" class="circle center">
						<span class="ico ico-multimedia"></span>
						<span class="txt">{{ language.translate("How to play<br>Lotto") }}</span>
					</a>
				</div>
			</div>
		</header>
		<div class="gameplay">
			<div class="wrap">
				<div class="cols box-lines">
					<div class="col2 num1">
						{# EMTD -  Every single line, need to have a different Line Number and unique ID generated #}
						{% include "_elements/line.volt" %}
					</div>
					<div class="col2 num2">
						{% include "_elements/line.volt" %}
					</div>
					<div class="col2 num3">
						{% include "_elements/line.volt" %}
					</div>
					<div class="col2 num4">
						{% include "_elements/line.volt" %}
					</div>
					<div class="col2 num5">
						{% include "_elements/line.volt" %}
					</div>
					<div class="col2 num6">
						{% include "_elements/line.volt" %}
					</div>
				</div>
			</div>
			<div class="cl">
				<ul class="no-li cl box-action">
					{# EMTD - Feature to do in second phase 
					<li><a class="btn gwg add-more" href="javascript:void(0);">{{ language.translate("Add more lines") }} <i class="ico ico-plus"></i></a></li> #}
					<li><a class="btn bwb random-all" href="javascript:void(0);">{{ language.translate("Randomize all lines") }} <i class="ico ico-shuffle"></i></a></li>
					<li class="fix-margin"><a class="btn rwr clear-all" href="javascript:void(0);">{{ language.translate("Clear all lines") }} <i class="ico ico-cross"></i></a></li>
				</ul>
			</div>
			<div class="box-bottom">
				<div class="wrap">
					<div class="cols">
						<div class="col2">
							<label>{{ language.translate("Which draws?") }}</label>
							<select class="draw_days">
								<option value="2,5">{{ language.translate("Tuesday & Friday") }}</option>
								<option value="2" {% if next_draw == 2 %} selected {% endif %}>{{ language.translate("Tuesday") }}</option>
								<option value="5" {% if next_draw == 5 %} selected {% endif %}>{{ language.translate("Friday") }}</option>
							</select>
						</div>
						<div class="col2">
							<label>{{ language.translate("Starting from?") }}</label>
							<select class="start_draw">
									{% for k,dates in play_dates %}
										{% for j,date in dates %}
											{% if k == 0 %}
												<option data-date="{{ date }}" value="{{ j }}">{{ date }} {{ jackpot_value/1000000 }}M</option>
											{% else %}
												<option data-date="{{ date }}" value="{{ j }}">{{ date }}</option>
											{% endif %}
										{% endfor %}
									{% endfor %}
							</select>
						</div>
						<div class="col2">
							<label>{{ language.translate("For how many weeks?") }}</label>
							<select class="frequency">
								<option value="1">{{ language.translate("1 week (Draws: 1)") }}</option>
								<option value="2">{{ language.translate("2 week (Draws: 2)") }}</option>
								<option value="4">{{ language.translate("4 week (Draws: 4)") }}</option>
								<option value="8">{{ language.translate("8 week (Draws: 8)") }}</option>
								<option value="52">{{ language.translate("52 week (Draws: 52)") }}</option>
							</select>
						</div>
						<div class="col6">
							<a href="javascript:void(0);" class="btn right add-cart"><span class="value">0.00 &euro;</span><span class="gap"><span class="separator"></span></span>{{ language.translate("Add to cart") }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>

{% endblock %}