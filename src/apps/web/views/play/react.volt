{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/w/css/vendor/tipr.css">
<link rel="stylesheet" href="/w/css/play.css">
{% endblock %}
{% block template_scripts %}
{#<script>{% include "play/index.js" %}</script>#}
{#<script src="/w/js/vendor/tipr.min.js"></script>#}
<script src="/w/js/vendor/react.js"></script>
<script src="/w/js/vendor/react-dom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>
<script type="text/babel" src="/w/js/react/index.js"></script>
{% endblock %}
{% block bodyClass %}play{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "play"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="gameplay">
			<div id="lines" class="box-lines cl">
				{% for index in 0..5 %}
					<div id="num_{{ index }}" class="myCol num{{ index }}">
						{#&#123;&#35;{% set showIndex = index + 1 %}&#35;&#125;#}
						{#&#123;&#35;{% include "_elements/line_react.volt" %}&#35;&#125;#}
					</div>
				{% endfor %}
			</div>
		</div>

	</div>
</main>
{% endblock %}