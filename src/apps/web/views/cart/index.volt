{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/w/css/sign-in.css">
<link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}
{% block template_scripts %}
    {% include "sign-in/_sign-in-js.volt" %}
{% endblock %}
{% block bodyClass %}cart minimal{% endblock %}

{% block header %}
    {% include "_elements/minimal-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
    {% include "sign-in/_sign-in-box.volt" %}
{% endblock %}