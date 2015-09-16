{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/shop.css">{% endblock %}
{% block template_scripts %}
    {% include "sign-in/_sign-in-js.volt" %}
{% endblock %}
{% block bodyClass %}shop minimal{% endblock %}

{% block header %}{% include "_elements/sign-in-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
*sign in shop*
    {% include "sign-in/_sign-in-box.volt" %}
{% endblock %}