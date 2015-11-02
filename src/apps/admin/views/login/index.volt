{% extends "main.volt" %}
{% block template_css %}
{#<link rel="stylesheet" href="/w/css/home.css">
#}
{% endblock %}
{% block bodyClass %}login{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
Login
{% endblock %}