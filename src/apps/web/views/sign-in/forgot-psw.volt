{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block bodyClass %}forgot-psw minimal{% endblock %}

{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
    {% include "sign-in/_forgot.volt" %}
{% endblock %}