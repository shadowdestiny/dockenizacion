{% extends "main.volt" %}
{% block template_css %}
	<link rel="stylesheet" href="/w/css/numbers.css">
	<!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
{% endblock %}
{% block bodyClass %}
numbers
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "numbers"}'|json_decode %}
	{% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
<script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
$(function(){
	var html_formatted_offset = [];
	$('.countdown .dots').eq(2).hide();
	$('.countdown .seconds').hide();
	var element = $('.countdown');
	var html_formatted = element.html();
	$('.countdown .dots').eq(2).show();
	$('.countdown .seconds').show();
	$('.countdown .day').remove();
	$('.countdown .dots').eq(0).remove();
	html_formatted_offset[0] = $('.countdown').html();
	$('.countdown .hour').remove();
	$('.countdown .dots').eq(0).remove();
	html_formatted_offset[1] = $('.countdown').html();
	$('.countdown .minute').remove();
	$('.countdown .dots').eq(0).remove();
	html_formatted_offset[2] = $('.countdown').html();
	var finish_action = function(){
		$('.box-next-draw .btn.red').remove();
	}
	var date = '{{ date_draw }}'; {# To test "2015/11/17 10:49:00"  #}
	var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
	count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
});
{% endblock %}
{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="box-basic">
			<h1 class="h1 title">{{ language.translate("Winning Numbers") }}</h1>
			<div class="wrap">
				<div class="cols">
					<div class="col8">
						<div class="box-results">
							<div class="content cl">
								<h2 class="h2"><span class="purple">{{ language.translate("Last Draw") }}</span> {{ last_draw_date }}</h2>

								<ul class="no-li inline numbers">
									{% for index,regular_number in last_result["regular_numbers"] %}
										{% if index == '0'%}
											<li>
											<div class="crown">
												<svg class="vector"><use xlink:href="/w/svg/number.svg#crown"></use></svg>
											</div>
												<span class="num">{{ regular_number }}</span></li>
										{% else %}
											<li><span class="num">{{ regular_number }}</span></li>
										{% endif %}
									{% endfor %}
									{% for lucky_number in last_result["lucky_numbers"] %}
										<li class="star"><span class="num">{{ lucky_number }}</span><span class="txt">{{ language.translate("Star ball") }}</span></li>
									{% endfor %}
								</ul>
								<span class="grey left">{{ language.translate("Draw") }} {{ id_draw }}</span>
							</div>
						</div>
					</div>
					<div class="col4">
						<div class="box-estimated">
							<div class="laurel first">
								<svg class="vector"><use xlink:href="/w/svg/icon.svg#laurel"></use></svg>
							</div>
							<div class="laurel last">
								<svg class="vector"><use xlink:href="/w/svg/icon.svg#laurel"></use></svg>
							</div>
							<div class="bg">
								<a href="/play" class="content">
									<h1 class="h3">{{ language.translate("Estimated jackpot") }}</h1>

									{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                                    {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}

                                    <div class="box-next-draw cl">
                                    	<div class="countdown">
		                                    <span class="next-draw"><span class="txt-one">{{ language.translate("Next") }}</span><br class="br">{{ language.translate("Draw") }}</span>
		                                    <div class="day unit">
		                                    	<span class="val">%-d</span>
		                                    	<span class="txt">day%!d</span>
		                                    </div>
		                                    <div class="dots">:</div>
		                                    <div class="hour unit">
		                                    	<span class="val">%-H</span>
		                                    	<span class="txt">hr</span>
			                                </div>
		                                    <div class="dots">:</div>
		                                    <div class="minute unit">
		                                    	<span class="val">%-M</span>
		                                    	<span class="txt">min</span>
		                                    </div>
											<div class="dots">:</div>
											<div class="seconds unit">
												<span class="val">%-S</span>
												<span class="txt">sec</span>
											</div>
		                                </div>
                                    	<span class="btn red big right">{{ language.translate("PLAY NOW") }}</span>
	                                </div>
								</a>
							</div>
						</div>

					</div>
				</div>
				<div class="cols">
					<div class="col8">
						<div class="box-current-winners">
							<h1 class="h2 purple">{{ language.translate("Prize pool") }}</h1>
							<table id="current-winners" class="table ui-responsive" data-role="table" data-mode="reflow">
								<thead>
									<tr>
										<th class="td-ball">{{ language.translate("Ball") }}</th>
										<th class="td-star-ball">{{ language.translate("Star Ball") }}</th>
										<th class="td-winners">{{ language.translate("Winners") }}</th>
										<th class="td-prize">{{ language.translate("Prize") }}</th>
									</tr>
								</thead>
								<tbody>
									{% for name,categories in break_downs %}
										<tr>
											{% if break_downs[name] is defined %}
												<td class="td-ball">
													{%  for corrected_numbers in 1..break_downs[name]['numbers_corrected'] %}
														<span class="ball"></span>
													{% endfor %}
												</td>
												<td class="td-star-ball">
													{% if break_downs[name]['stars_corrected'] > 0 %}
														{%  for corrected_stars in 1..break_downs[name]['stars_corrected'] %}
															<span class="star-ball"></span>
														{% endfor %}
													{% endif %}
												</td>
												<td class="td-winners">{{ break_downs[name]['winners'] }}</td>
												<td class="td-prize">{{ symbol }} {{ break_downs[name]['lottery_prize'] | number_format(2, '.', ',') }}</td>
											{% endif %}
										</tr>
									{% endfor %}
								</tbody>
							</table>
						</div>
					</div>
					<div class="col4">
						<div class="box-history">
						</div>
					</div>
				</div>
				<div class="cols bottom">
					<div class="col8">

						<div class="box-basic">
							<div class="pad">
								<h1 class="h2 purple">{{ language.translate("Past Winning Numbers") }}</h1>
							</div>
							<table id="history-numbers" class="ui-responsive table2" data-role="table" data-mode="reflow">
								<thead>
								<tr>
									<th class="td-date">{{ language.translate("Date") }}</th>
									<th class="td-ball-numbers">{{ language.translate("Ball") }} <span class="ball"></span></th>
									<th class="td-star-numbers">
										{{ language.translate("Star") }}
										<span class="star-ball"></span>
									</th>
								</tr>
								</thead>
								<tbody>
								{% for i,draw in list_draws %}
										<tr style="cursor: pointer" onclick="document.location='/{{ lottery }}/results/draw-history-page/{{ draw.drawDateParam }}'">
												<td class="td-date">{{ draw.drawDate }}</td>
												<td class="td-ball-numbers">{{ draw.regularNumbers }}</td>
												<td class="td-star-numbers">{{ draw.luckyNumbers }}</td>
										</tr>
								{%  endfor %}
								</tbody>
							</table>
							<div class="box-action">
								<a href="/euromillions/results/draw-history-page" class="btn  green big wide ui-link">Show more past results</a>
							</div>
						</div>
					</div>
					<div class="col4">{# nothing here #}</div>
				</div>
			</div>
		</div>
	</div>
</main>
{% endblock %}