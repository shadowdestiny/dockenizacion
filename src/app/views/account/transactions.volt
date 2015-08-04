{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}my-games{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}

{% set activeNav='{"myClass": "account"}'|json_decode %}

<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "transactions"}'|json_decode %}
           {% include "account/nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("My Transactions") }}</h1>

    
        </div>
    </div>
</main>
{% endblock %}