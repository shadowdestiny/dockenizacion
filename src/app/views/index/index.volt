{% extends "main.volt" %}
{#
{% block template_css %}
	<link rel="stylesheet" href="css/home.css">
{% endblock %}
#}
{% block body %}

<main id="content">
	<div class="wrapper">
		<div class="cols">
			<div class="col6">&nbsp;</div>
			<div class="col6">&nbsp;</div>
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