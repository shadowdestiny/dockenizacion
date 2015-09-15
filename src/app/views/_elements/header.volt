{# EMTD To insert *burger* for  mobile version #}

{# Caching SVG #}

{% include "_elements/svg.volt" %}

<header data-role="header">
	<nav class="top-nav desktop">
		<div class="wrapper">
			<ul class="ul-top-nav">
				{% include "_elements/top-nav.volt" %}
			</ul>
		</div>
	</nav>
	<div class="head">
		<div class="wrapper">
			<a href="javascript:void(0);" class="logo" title="Go to Homepage">
				<picture class="pic">
					<!--[if IE 9]><video style="display: none;"><![endif]-->
					<source media="(max-width: 768px)" srcset="/img/logo/euromillions-sm.png">
					<!--[if IE 9]></video><![endif]-->
					<img src="/img/logo/euromillions.png" alt="{{ language.translate('EuroMillions.com') }}">
				</picture>
			</a>
			<nav class="main-nav desktop">
				<ul>
					{% include "_elements/nav.volt" %}
				</ul>
			</nav>
		</div>
	</div>
	<nav class="nav mobile">
		<button class="menu-ham"><span class="bar"></span></button> 
		<ul>
			{% include "_elements/nav.volt" %}
			{% include "_elements/top-nav.volt" %}
		</ul>
	</nav>
</header>