{% extends "main.volt" %}

{% block bodyClass %}overview{% endblock %}

{% block meta %}<title>Overview - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="content">
                    <h1 class="h1 purple">Overview</h1>
                    /* Insert here stuff overview from other pages */

                </div>
            </div>
        </div>
    </div>
</div>

{% endblock %}