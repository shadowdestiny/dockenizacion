{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <link Rel="Canonical" href="{{ language.translate('canonical_christmas_results') }}" />
{% endblock %}
{% block bodyClass %}terms{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
    {% if ticket_number != null %}
        {% include "_elements/christmass-lottery-results-prize.volt" %}
    {% else %}
        {% include "_elements/christmass-lottery-results-prize.volt" %}
    {% endif %}
{% endblock %}
