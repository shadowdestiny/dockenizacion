{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/home.css">{% endblock %}

{% block body %}

<main id="content">
	<div class="wrapper">
		<div class="cols">
			<div class="col6 box-left">
				<div class="box-estimated">
					<div class="content">
						<h1 class="h2">Estimated Euromillions Jackpot</h1>
						<div class="box-value yellow">
							<span class="currency">&euro;</span> <span class="value">51.000.000</span>
						</div>
						<a href="javascript:void(0);" class="btn white try">Try your luck <i class="ico ico-arrow-right3"></i></a>
					</div>
					<img class="vector" src="/img/sprite.svg#emblem">
				</div>
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
				<div class="box-result">
						<h1 class="h2">Euromillions Results</h1>
						<p>Lorem ipsum winning numbers</p>
						* insert numbers *
						<a href="javascript:void(0);" class="go-numbers">Try your luck <i class="ico"></i></a>
				</div> 
			</div>
			<div class="col6 box-right">

				<div class="box-play">
					<p class="h2">For only 2,35 &euro;</p>

					<a href="javascript:void(0);" class="btn big purple">PLAY NOW <i class="ico ico-arrow-right"></i></a>

					<ul class="no-li">
						<li><span class="txt">Best price</span></li>
						<li><span class="txt">Risk free</span></li>
					</ul>
				</div>

				<div class="box-prize">
					<h1 class="h2">1st Prize</h1>
					<div class="box-value">
						<span class="currency">&euro;</span> <span class="value">51.000.000</span>
					</div>

					<a href="javascript:void(0);" class="btn blue">I feel lucky <i class="ico ico-arrow-right"></i></a>

					<a href="javascript:void(0);" class="btn red">Pick your numbers <i class="ico ico-arrow-right"></i></a>

					<div class="notes cl">
						<div class="right">
							Luck is not as random as you think
						</div>
						<i class="ico ico-hourglass"></i> *time*

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

{# SVG embed
<embed type="image/svg+xml" src="mySVG.svg" />
#}
	</div>
</main>

{% endblock %}