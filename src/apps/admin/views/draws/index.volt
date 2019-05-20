{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}
    <script>{% include "draws/draws.js" %}</script>
{% endblock %}

{% block meta %}<title>Jackpot - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/react/adminDraws.js"></script>
{% endblock %}
{% block footer %}
    {% include "_elements/footer.volt" %}
{% endblock %}

{% block body %}
 <div class="wrapper">
    <div class="container-fluid">
        <div id="admin-draws"></div>
    </div>
</div>
{% endblock %}