<script>var myLogged = '<?php echo $user_logged; ?>'</script> {# This value is used in mobileFix.js #}
<div class="mobile">
	{% include "_elements/cookies.volt" %}
</div>
<header data-role="header" class="header">
	{% include "_elements/top-bar.volt" %}
	<div class="head">
		<div class="wrapper">
            {% include "_elements/logo.volt" %}
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