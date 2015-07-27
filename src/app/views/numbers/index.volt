{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/numbers.css">{% endblock %}
{% block bodyClass %}numbers{% endblock %}
{% block template_scripts %}
<script>
$(function(){
	$(".li-numbers").addClass("active");
});
</script>
{% endblock %}

{% block header %}{% include "_elements/header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="box-basic">
			<h1 class="h1 title">Winning Numbers</h1>
			<div class="wrap">
				<div class="cols">
					<div class="col8">
						<div class="box-results">
							<div class="content cl">
								<h2 class="h2"><span class="purple">Last Draw</span> Friday, 03 Jul 2015</h3>

								<ul class="no-li inline numbers">
								    <li>
								    	<div class="crown">
											<svg class="vector"><use xlink:href="#crown"></use></svg>
								    	</div>
								    	<span class="num">5</span></li>
								    <li><span class="num">7</span></li>
								    <li><span class="num">11</span></li>
								    <li><span class="num">28</span></li>
								    <li><span class="num">40</span></li>
								    <li class="star"><span class="num">7</span><span class="txt">Star ball</span></li>
								    <li class="star"><span class="num">10</span><span class="txt">Star ball</span></li>
								</ul>

	{#
								<ul class="no-li inline numbers">
	                                {% for regular_number in euromillions_results["regular_numbers"] %}
									    <li>{{ regular_number }}</li>
	                                {% endfor %}
	                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
									    <li class="star">{{ lucky_number }}</li>
	                                {% endfor %}
								</ul>
	#}
								<span class="grey left">Draw 117</span> 
							</div>
						</div>
					</div>
					<div class="col4">
						<div class="box-estimated">
							<div class="laurel first">
								<svg class="vector"><use xlink:href="#laurel"></use></svg>
							</div>
							<div class="laurel last">
								<svg class="vector"><use xlink:href="#laurel"></use></svg>
							</div>
							<div class="bg">
								<a href="javascript:void(0);" class="content">
									<h1 class="h3">Estimated jackpot</h1>

									{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                                    {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}

                                    <div class="box-next-draw cl">
                                    	<div class="countdown">
		                                    <span class="next-draw"><span class="txt-one">Next</span><br class="br">Draw</span>
		                                    <div class="day unit">
		                                    	<span class="val">1</span>
		                                    	<span class="txt">day</span>
		                                    </div>
		                                    <div class="dots">:</div>
		                                    <div class="hour unit">
		                                    	<span class="val">15</span>
		                                    	<span class="txt">hours</span>
			                                </div>
		                                    <div class="dots">:</div>
		                                    <div class="minute unit">
		                                    	<span class="val">35</span>
		                                    	<span class="txt">minutes</span>
		                                    </div>
		                                </div>
                                    	<span href="javascript:void(0);" class="btn red big right">PLAY NOW</span>
	                                </div>
								</a>
							</div>
						</div>

					</div>
				</div>
				<div class="cols">
					<div class="col8">
						<div class="box-current-winners">
							<h1 class="h2 purple">Prize pool</h1>
							<table id="current-winners" class="table ui-responsive" data-role="table" data-mode="reflow">
								<thead>
									<tr>
										<th class="td-ball">Ball</th>
										<th class="td-star-ball">Star Ball</th>
										<th class="td-winners">Winners</th>
										<th class="td-prize">Prize pool</th>
										<th class="td-payout">Payout</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="td-ball">
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
										</td>
										<td class="td-star-ball">
											<span class="star-ball"></span>
											<span class="star-ball"></span>
										</td>
										<td class="td-winners">0</td>
										<td class="td-prize">&euro; 24.000.000,00</td>
										<td class="td-payout">-</td>
									</tr>
									<tr>
										<td class="td-ball">
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
											<span class="ball"></span>
										</td>
										<td class="td-star-ball">
											<span class="star-ball"></span>
										</td>
										<td class="td-winners">2</td>
										<td class="td-prize">&euro; 24.000.000,00</td>
										<td class="td-payout">&euro; 1.254.451,20</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col4">
						<div class="box-history">
							<div class="box-basic">
								<div class="pad">
									<h1 class="h2 purple">Past Winning Numbers</h1>
								</div>
								<table id="history-numbers" class="ui-responsive table2" data-role="table" data-mode="reflow">
									<thead>
										<tr>
											<th class="td-date">Date</th>
											<th class="td-ball-numbers">Ball <span class="ball"></span></th>
											<th class="td-star-numbers">
												Star
												<span class="star-ball"></span>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="td-date">07 JUL 2015</td>
											<td class="td-ball-numbers">01 02 03 04 05</td>
											<td class="td-star-numbers">01 02</td>
										</tr>
										<tr>
											<td class="td-date">07 JUL 2015</td>
											<td class="td-ball-numbers">01 02 03 04 05</td>
											<td class="td-star-numbers">01 02</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="cols bottom">
					<div class="col8">
						<select class="select right">
							<option>07 August 2015 - Draw 116</option>
							<option>04 August 2015 - Draw 115</option>
							<option>01 August 2015 - Draw 114</option>
						</select>
						<span class="txt right">Previous results</span>
					</div>
					<div class="col4">{# nothing here #}</div>
				</div>
			</div>
		</div>
	</div>
</main>
{% endblock %}