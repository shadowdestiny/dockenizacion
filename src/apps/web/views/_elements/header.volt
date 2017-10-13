<script>var myLogged = '<?php echo $user_logged; ?>'</script> {# This value is used in mobileFix.js #}
<div class="mobile">
	{% include "_elements/cookies.volt" %}
</div>
<header data-role="header" class="header">
	<div class="top-bar--desktop">
		{% include "_elements/top-bar--desktop.volt" %}
	</div>
	<div class="top-bar--mobile">
		{% include "_elements/top-bar--mobile.volt" %}
	</div>

	<div class="head">
		<div class="wrapper">
            {#{% include "_elements/logo.volt" %}#}
			<nav class="main-nav desktop">
				<ul>
					{% include "_elements/nav--desktop.volt" %}
				</ul>
			</nav>
		</div>
	</div>
	<nav class="nav mobile">
		<button class="menu-ham"><span class="bar"></span></button>
		<ul>
			{#{% include "_elements/top-nav--mobile.volt" %}#}
			{% include "_elements/nav--mobile.volt" %}
		</ul>
	</nav>
</header>