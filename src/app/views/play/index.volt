{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/play.css">{% endblock %}
{% block bodyClass %}play{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<header class="bg-top">
			<div class="cols">
				<div class="col8">
					<h1 class="h3"><span class="br">Next Draw:</span> Friday 29 May 19:20</h2>
					<span class="h1">
						Jackpot
						{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
						{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
					</span>
				</div>
				<div class="col4">
					<a href="javascript:void(0);" class="circle center">
						<span class="ico ico-multimedia"></span>
						<span class="txt">How to play<br>Lotto</span>
					</a>
				</div>
			</div>
		</header>
		<div class="gameplay">
			<div class="wrap">
				<div class="cols box-lines">
					<div class="col2 num1">
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
					<li><a class="btn gwg" href="javascript:void(0);">Add more lines <i class="ico ico-plus"></i></a></li>
					<li><a class="btn bwb" href="javascript:void(0);">Randomize all lines <i class="ico ico-shuffle"></i></a></li>
					<li><a class="btn rwr" href="javascript:void(0);">Clear all lines <i class="ico ico-cross"></i></a></li>
				</ul>
			</div>
			<div class="box-bottom">
				<div class="wrap">
					<div class="cols">
						<div class="col2">
							<label>Which draws?</label>
							<select>
								<option>Friday</option>
							</select>
						</div>
						<div class="col2">
							<label>Starting from?</label>
							<select>
								<option>29.May.2015</option>
							</select>
						</div>
						<div class="col2">
							<label>For how many weeks?</label>
							<select>
								<option>1 week (Draws:1</option>
							</select>
						</div>
						<div class="col6">
							<a href="javascript:void(0);" class="active btn right add-cart">0.00 &euro;<span class="gap"><span class="separator"></span></span>Add to cart</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>

{% endblock %}