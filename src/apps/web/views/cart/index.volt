{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/css/sign-in.css">
<link rel="stylesheet" href="/css/cart.css">
{% endblock %}
{% block template_scripts %}
    {% include "sign-in/_sign-in-js.volt" %}
{% endblock %}
{% block bodyClass %}cart minimal{% endblock %}

{% block header %}
    {% set activeSteps='{"myClass": "step1"}'|json_decode %}
    {% include "_elements/sign-in-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
    {% include "sign-in/_sign-in-box.volt" %}
{% endblock %}