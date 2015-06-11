{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/home.css">{% endblock %}

{% block body %}

<main id="content">
	<div class="wrapper">
		<div class="cols">
			<div class="col6 box-left">
				<a href="javascript:void(0);" class="box-estimated no-lnk">
					<div class="content">
						<h1 class="h2">{{ language.translate("Estimated Euromillions Jackpot") }}</h1>
						{% set extraClass='{"boxvalueClass": "yellow","currencyClass":"","valueClass":""}'|json_decode %}
						{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
						<span class="btn white try animate infi">{{ language.translate("Try your luck") }} <i class="ico ico-arrow-right3"></i></span>
					</div>
					<svg class="vector"><use xlink:href="#emblem"></use></svg>
				</a>
				<div class="box-how">
					<div class="bg-yellow">
						<h1 class="h3">{{ language.translate("How to play") }}</h1>
					</div>
					<ul class="no-li cl">
						<li>
							<svg class="vector"><use xlink:href="#lottery-ticket"></use></svg>

							<div class="box-txt">
								<h2 class="h3"><span class="grey">1.</span> {{ language.translate("play")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Choose <em>PLAY NOW</em> or <em>QuickPlay</em>") }}</p>
							</div>
						</li>
						<li>
							<svg class="vector"><use xlink:href="#monitor-number"></use></svg>
						
							<div class="box-txt">
								<h2 class="h3"><span class="grey">2.</span> {{ language.translate("pick")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Pick 5+2 Lucky Stars or <em>QuickPick</em>") }}</p>
							</div>
						</li>
						<li>
							<svg class="vector"><use xlink:href="#winner-cup"></use></svg>

							<div class="box-txt">
								<h2 class="h3"><span class="grey">3.</span> {{ language.translate("win")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Check results and cash your winnings") }}</p>
							</div>
						</li>
					</ul>

					<div class="center">
						<a href="javascript:void(0);" class="btn big blue">{{ language.translate("Start to Play") }} <i class="ico ico-arrow-right"></i></a>
					</div>
				</div>
				<a href="javascript:void(0);" class="box-result no-lnk">
					<div class="cols">
						<div class="col8 content">
							<h1 class="h2">{{ language.translate('Euromillions Results') }}</h1>
							<p>Lorem ipsum winning numbers</p>
							<ul class="no-li inline numbers small">
                                {% for regular_number in euromillions_results["regular_numbers"] %}
								    <li>{{ regular_number }}</li>
                                {% endfor %}
                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
								    <li class="star">{{ lucky_number }}</li>
                                {% endfor %}
							</ul>
							<span class="lnk animate infi"><span class="txt">{{ language.translate('Latest winning numbers') }}</span> <i class="ico ico-arrow-right3"></i></span>
						</div>
						<div class="col4 woman">&nbsp;</div>
					</div>
				</a> 
			</div>
			<div class="col6 box-right">

				<div class="box-play">
					<a href="javascript:void(0);" class="outbound">
						<div class="content">

							<p class="h2">{{ language.translate('for only %priceValue% &euro;',['priceValue':2,35]) }}</p>
							<div class="win-millions">
								<picture class="pic" alt="{{ language.translate('Win Millions') }}">
									<!--[if IE 9]><video style="display: none;"><![endif]-->
									<source media="(max-width: 768px)" srcset="/img/home/win-millions-sm.png">
									<!--[if IE 9]></video><![endif]-->
									<img srcset="/img/home/win-millions.png, /img/home/win-millions@2x.png 1.5x">
								</picture>
							</div>
							<div class="box-ball">
								<div class="btn big purple">
									{{ language.translate('PLAY NOW')|upper }} <i class="ico ico-arrow-right"></i>
									<span class="ball"></span>
								</div>
							</div>

							<ul class="no-li awards">
								<li class="best-price"><span class="txt">{{ language.translate('Best price') }}</span></li>
								<li class="risk-free"><span class="txt">{{ language.translate('Risk free') }}</span></li>
							</ul>
						</div>
					</a>
				</div>

				<div class="box-prize">
					<div class="content">
						<div class="bg2">
							<div class="bg">
								<div class="content">
									<div class="cols">
										<div class="col6">
											<h1 class="h2">{{ language.translate('1st Prize') }}</h1>
                                            {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                                            {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
											<div class="box-btn">
												<a href="javascript:void(0);" class="btn blue expand">{{ language.translate('I feel lucky') }} <i class="ico ico-arrow-right"></i></a>

												<a href="javascript:void(0);" class="btn red expand">{{ language.translate('Pick your numbers') }} <i class="ico ico-arrow-right"></i></a>
											</div>
										</div>
										<div class="col6 center box-vector">
											<svg class="vector"><use xlink:href="#lotto-game"></use></svg>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="notes cl">
							<div class="left">
								<i class="ico ico-hourglass"></i> <span class="time">{{ days_till_next_draw }} {{ language.translate('DAYS') }} {{ hours_till_next_draw }}:{{ minutes_till_next_draw }}</span>
								{# EMTD be careful to add singolar/plural: DAY / DAYS and if it is a matter of hours put content text as "04:09:34" format#}
							</div>
							<div class="right">
								{{ language.translate('Luck is not as random as you think') }}
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</main>

{% endblock %}