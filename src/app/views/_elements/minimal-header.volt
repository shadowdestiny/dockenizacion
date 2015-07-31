{# EMTD To insert *burger* for  mobile version #}

{# Caching SVG #}
{% include "_elements/svg.volt" %}

<header data-role="header">
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
		</div>
	</div>
</header>