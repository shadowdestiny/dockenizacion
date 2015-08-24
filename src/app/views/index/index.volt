{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/home.css">{% endblock %}
{% block bodyClass %}home{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

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
							<p>{{ language.translate('Draw on') }} Tuesday, June 16, 2015</p>
							<ul class="no-li inline numbers small">
                                {% for regular_number in euromillions_results["regular_numbers"] %}
								    <li>{{ regular_number }}</li>
                                {% endfor %}
                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
								    <li class="star">{{ lucky_number }}</li>
                                {% endfor %}
							</ul>
							<span class="lnk animate infi"><span class="txt">{{ language.translate('Results &amp; Prizes') }}</span> <i class="ico ico-arrow-right3"></i></span>
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
								<picture class="pic">
									<!--[if IE 9]><video style="display: none;"><![endif]-->
									<source media="(max-width: 768px)" srcset="/img/home/win-millions-sm.png">
									<!--[if IE 9]></video><![endif]-->
									<img srcset="/img/home/win-millions.png, /img/home/win-millions@2x.png 1.5x" alt="{{ language.translate('Win Millions') }}">
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

{#	Hidden temporarly

		<div class="informative">
			* highlight this section*
			<div class="">
				<div class="who-we-are cols">
					<div class="col6">
						<h3 class="title">Who we are</h3>
						<p>The Euromillions.com is the first European transnational lottery launched in 2004: Fast, Convenient and Secure. We are the first lottery-website with a full responsive design that offers you the same user experience on all your devices, (mobile, tablet, computer). Our draws are held every Tuesday and Friday night at 20:45 CET‬ in Paris, France.</p>
					</div>
					<div class="col6">
						img
					</div>
				</div>
				<div class="cols">
					<div class="col6">
						<picture class="pic">
							<!--[if IE 9]><video style="display: none;"><![endif]-->
							<source media="(max-width: 768px)" srcset="/img/home/quality-sm.jpg">
							<!--[if IE 9]></video><![endif]-->
							<img srcset="/img/home/quality.jpg, /img/home/quality@2x.jpg 1.5x" alt="{{ language.translate('Win Millions') }}">
						</picture>
					</div>
					<div class="col6">
						*highlight those 3 text with graphics *
						<h3 class="li-title">Fast</h3>
						<p>Regular players rarely exceed two minutes to validate their euromillions tickets on our website. At Euromillions.com, you just have to choose your favourite numbers, sit back, and relax. We do all the nerve-wracking, waiting, and checking the results for you. Shortly after the draw, you will receive an email notification detailing the latest results, and if you have won, the winnings will automatically be credited to your player account.
						</p>

						<h3 class="li-title">Convenient</h3>
						<p>You’ll find your played tickets, latest euromillions results, and customer support at the same place. At only €2.35 (£1.65) per play, we offer the best price available on the Internet. Your winnings are commission free and will remain so forever.
						It doesn’t matter where you live – we are a regulated operator and our services are available worldwide. If you win big, our team of professionals will be at your assistance to make sure you receive your winnings quickly and safely.  
						</p>

						<h3 class="li-title">Secure</h3>
						<p>Euromillions.com is a fully licensed lottery operator. We are customer-centric and constantly endeavour to improve our performance to meet and exceed the demands of our customers. Our partnerships with top financial institutions and certification companies ensure your optimum safety when playing on our website supporting 256-bit SSL encryption. 
						</p>
					</div>
				</div>
			</div>

			<div class="box-basic">
				<div class="cols playing-euro">
					<div class="col6">
						<h2 class="h1 yellow">Playing euromillions</h2>
						<ul class="no-li ul-home cl">
							<li>
								<h3 class="li-title">Play</h3>
								Players select five main numbers from 1 to 50 and two lucky stars numbered from 1 to 11. The results are published shortly after the draw on Euromillions.com, and players receive an email notification detailing the latest results and if they have won. 
								Winnings are commission free and are immediately credited to the player’s account on Euromillions.com.  
							</li>
							<li>
								<h3 class="li-title">Eligibility</h3>
								Any person who is 18 years or above can participate in the Euromillions. This differs for some countries such as the United Kingdom, where lottery players can participate starting from the age of 16. 
							</li>
							<li>
								<h3 class="li-title">Jackpot</h3>
								Euromillions jackpot prizes can reach up to €190 million with a guaranteed jackpot of at least €15 million (£10.5 million) per draw (two draws per week). In the absence of a first prize winner, the money is rolled over to the next draw which will grow in successive categories until either a Euromillions jackpot winner is produced, or the Euromillions Pool Cap (190 million euro) is reached. 
							</li>
							<li>
								<h3 class="li-title">Odds</h3>
								To win the Euromillions jackpot prize, players need to match 5 main numbers from 50, and 2 lucky stars from 11. The Euromillions odds of this actually happening are 1 in 116,531,800. In addition, the Euromillions lottery features 13 different tiers and the odds of a Euromillions win are 1 in 23. 
							</li>
							<li>
								<h3 class="li-title">International</h3> 
								As there are several participating countries, EuroMillions is said differently in each language. In Portuguese: Euro Milhões
								In Spanish: Euromillones. In German EuroMilionen. In France, Euromillions is written the same way as in English, but the last letter, "s" is not pronounced and the word is sometimes misspelled as l’Euromillion.
							</li>
						</ul>
					</div>
					<div class="col6">
						xxxx
					</div>
				</div>
			</div>
		</div>


#}
	</div>
</main>

{% endblock %}