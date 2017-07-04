{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/play.css">{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/christmasPlay.js"></script>
{% endblock %}

{% block bodyClass %}play{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "christmas"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
	<main id="content">
		<div class="wrapper">
			<div class="gameplay border-top-yellow">
                ORDER
			</div>
		</div>
	</main>
{% endblock %}
