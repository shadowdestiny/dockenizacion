{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/w/css/vendor/tipr.css">
<link rel="stylesheet" href="/w/css/play.css">
{% endblock %}
{% block template_scripts %}
<script>{% include "play/index.js" %}</script>
<script src="/w/js/vendor/tipr.min.js"></script>
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
{# How to play video - hidden we have no video
					<a href="javascript:void(0);" class="circle center">
						<svg class="ico v-multimedia"><use xlink:href="/w/icon.svg#v-multimedia"></use></svg>
						<span class="txt">{{ language.translate("How to play<br>Lotto") }}</span>
					</a>
#}
				</div>
			</div>
		</header>
		<div class="special">
			<svg class="ico v-info"><use xlink:href="/w/icon.svg#v-info"></use></svg>
			<span class="txt">{{ language.translate("A single bet in a line consist in 5 numbers + 2 star numbers. Do you want to make a bet with multiple numbers in a line?") }}
			<a href="/faq#n10">{{ language.translate("Yes, please.") }}</a></span>
		</div>
		<div class="gameplay">
			<div class="box-lines cl">
				{% for index in 0..5 %}
					<div id="num_{{ index }}" class="myCol num{{ index }}">
						{% set showIndex = index + 1 %}
						{% include "_elements/line.volt" %}
					</div>
				{% endfor %}
			</div>
			<div class="cl">
				<ul class="no-li cl box-action">
					<li class="box-more" data-tip="{{ language.translate('It is not possible to add more lines until you fill in the previous ones') }}"><a class="btn gwg add-more" href="javascript:void(0);">{{ language.translate("Add more lines") }} <svg class="ico v-plus"><use xlink:href="/w/icon.svg#v-plus"></use></svg></a></li>
					<li><a class="btn bwb random-all" href="javascript:void(0);">{{ language.translate("Randomize all lines") }} <svg class="v-shuffle"><use xlink:href="/w/icon.svg#v-shuffle"></use></svg></a></li>
					<li class="fix-margin"><a class="btn rwr clear-all" href="javascript:void(0);">{{ language.translate("Clear all lines") }} <svg class="ico v-cross"><use xlink:href="/w/icon.svg#v-cross"></use></svg></i></a></li>
				</ul>
			</div>
			<div class="box-bottom">
				<div class="wrap">
					<div class="cl">
						<div class="right">
							<a href="javascript:void(0);" class="btn big gwp advanced">Advanced Play <svg class="ico v-clover"><use xlink:href="/w/icon.svg#v-clover"></use></svg></a>

							<a href="javascript:void(0);" class="btn add-cart"><span class="value">0.00 &euro;</span><span class="gap"><span class="separator"></span></span>{{ language.translate("Add to Cart") }}</a>
						</div>
					</div>
					<div class="advanced-play">
						<hr class="hr yellow">
						<a href="javascript:void(0);" class="close"><svg class="ico v-cancel-circle"><use xlink:href="/w/icon.svg#v-cancel-circle"></use></svg></a>
						<div class="cols">
							<div class="col2">
								<label class="label">{{ language.translate("Draw") }} 
									<svg data-tip="{{ language.translate('Which draw do you want to play?') }}" class="ico v-question-mark tipr-small"><use xlink:href="/w/icon.svg#v-question-mark"></use></svg>
								</label>
								<div class="styled-select">
									<div class="select-txt"></div>
									<select  autocomplete="off"  class="draw_days mySelect">
										<option value="2,5">{{ language.translate("Tuesday & Friday") }}</option>
										<option value="2" {% if next_draw == 2 %} selected {% endif %}>{{ language.translate("Tuesday") }}</option>
										<option value="5" {% if next_draw == 5 %} selected {% endif %}>{{ language.translate("Friday") }}</option>
									</select>
								</div>
							</div>
							<div class="col2">
								<label class="label">{{ language.translate("First Draw") }} 
									<svg data-tip="{{ language.translate('From which draw do you wish to play?') }}" class="ico v-question-mark tipr-small"><use xlink:href="/w/icon.svg#v-question-mark"></use></svg>
								</label>
								<div class="styled-select">
									<div class="select-txt"></div>
									<select autocomplete="off" class="start_draw mySelect">
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
							</div>
							<div class="col2">
								<label class="label">{{ language.translate("Duration") }} 
									<svg data-tip="{{ language.translate('For how long do you wish to play?') }}" class="ico v-question-mark tipr-small"><use xlink:href="/w/icon.svg#v-question-mark"></use></svg>
								</label>
								<div class="styled-select">
									<div class="select-txt"></div>
									<select autocomplete="off" class="frequency mySelect">
										<option value="1">{{ language.translate("1 week (Draws: 1)") }}</option>
										<option value="2">{{ language.translate("2 week (Draws: 2)") }}</option>
										<option value="4">{{ language.translate("4 week (Draws: 4)") }}</option>
										<option value="8">{{ language.translate("8 week (Draws: 8)") }}</option>
										<option value="52">{{ language.translate("52 week (Draws: 52)") }}</option>
		{#
										<option value="always">{{ language.translate("Always (Every draw)") }}</option>
		#}
									</select>
								</div>
							</div>
							<div class="col6 wrap-threshold">
								<label class="label" for="threshold">{{ language.translate("Jackpot Threshold") }} 
									<svg data-tip="{{ language.translate('Set the condition when you want to play or to be informed automatically. Thresholds are calculated only in Euro.') }}" class="ico v-question-mark tipr-normal"><use xlink:href="/w/icon.svg#v-question-mark"></use></svg>
								</label>
								{% include "_elements/jackpot-threshold.volt" %}
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>
{% endblock %}