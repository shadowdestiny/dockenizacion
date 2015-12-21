{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block bodyClass %}sign-in minimal{% endblock %}

{#
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}
#}

{% block body %}
    {% include "sign-in/_sign-in-box.volt" %}
{% endblock %}

