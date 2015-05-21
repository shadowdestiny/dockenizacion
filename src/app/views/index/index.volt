{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/home.css">{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="cols">
			<div class="col6 box-left">
				<a href="javascript:void(0);" class="box-estimated no-lnk">
					<div class="content">
						<h1 class="h2">Estimated Euromillions Jackpot</h1>
						<div class="box-value yellow">
							<span class="currency">&euro;</span> <span class="value">51.000.000</span>
						</div>
						<span class="btn white try animate infi">Try your luck <i class="ico ico-arrow-right3"></i></span>
					</div>
					<img class="vector" src="/img/sprite.svg#emblem">
				</a>
				<div class="box-how">
					<div class="bg-yellow">
						<h1 class="h3">How to play</h1>
					</div>
					<ul class="no-li cl">
						<li>
							<img class="vector vec1" src="/img/sprite.svg#lottery-ticket">
							<div class="box-txt">
								<h2 class="h3"><span class="grey">1.</span> PLAY</h2>
								<p>Choose <em>PLAY NOW</em> or <em>QuickPlay</em></p>
							</div>
						</li>
						<li>
							<img class="vector vec2" src="/img/sprite.svg#monitor-number">
							<div class="box-txt">
								<h2 class="h3"><span class="grey">2.</span> PICK</h2>
								<p>Pick 5+2 Lucky Stars or <em>QuickPick</em></p>
							</div>
						</li>
						<li>
							<img class="vector vec3" src="/img/sprite.svg#winner-cup">
							<div class="box-txt">
								<h2 class="h3"><span class="grey">3.</span> WIN</h2>
								<p>Check results and cash your winnings</p>
							</div>
						</li>
					</ul>

					<div class="center">
						<a href="javascript:void(0);" class="btn big blue">Start to Play Now <i class="ico ico-arrow-right"></i></a>
					</div>
				</div>
				<a href="javascript:void(0);" class="box-result no-lnk">
					<div class="cols">
						<div class="col8 content">
							<h1 class="h2">Euromillions Results</h1>
							<p>Lorem ipsum winning numbers</p>
							<ul class="no-li inline numbers small">
								<li>9</li>
								<li>25</li>
								<li>27</li>
								<li>38</li>
								<li>43</li>
								<li class="star">66</li>
								<li class="star">68</li>
							</ul>
							<span class="lnk animate infi"><span class="txt">Latest winning numbers</span> <i class="ico ico-arrow-right3"></i></span>
						</div>
						<div class="col4 woman">&nbsp;</div>
					</div>
				</a> 
			</div>
			<div class="col6 box-right">

				<div class="box-play">
					<a href="javascript:void(0);" class="outbound">
						<div class="content">
							<p class="h2">for only 2,35 &euro;</p>

							<div class="box-ball">
								<div class="btn big purple">
									PLAY NOW <i class="ico ico-arrow-right"></i>
									<span class="ball"></span>
								</div>
							</div>

							<ul class="no-li awards">
								<li class="best-price"><span class="txt">Best price</span></li>
								<li class="risk-free"><span class="txt">Risk free</span></li>
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
											<h1 class="h2">1st Prize</h1>
											<div class="box-value">
												<span class="currency yellow">&euro;</span> <span class="value yellow">51.000.000</span>
												<span class="only">only 2,35 &euro;</span>
											</div>

											<div class="box-btn">
												<a href="javascript:void(0);" class="btn blue expand">I feel lucky <i class="ico ico-arrow-right"></i></a>

												<a href="javascript:void(0);" class="btn red expand">Pick your numbers <i class="ico ico-arrow-right"></i></a>
											</div>
										</div>
										<div class="col6 center">
											<img class="vector" src="/img/sprite.svg#lotto-game">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="notes cl">
							<div class="right">
								Luck is not as random as you think
							</div>
							<i class="ico ico-hourglass"></i> <span class="time">3 DAYS 09:50</span> {# EMTD be careful to add singolar/plural: DAY / DAYS and if it is a matter of hours put content text as "04:09:34" format}

						</div>
					</div>
				</div>

			</div>
		</div>

{# Example Variables
		<ul>
			{% for text in texts %}
			<li>{{ text }}</li>
			{% endfor %}
		</ul>
#}

	</div>
</main>

{% endblock %}