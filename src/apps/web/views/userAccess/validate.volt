{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/sign-in.css">{% endblock %}
{% block bodyClass %}validate{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
    {{ message }}
{% endblock %}
