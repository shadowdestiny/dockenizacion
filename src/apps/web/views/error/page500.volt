{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/error.css">{% endblock %}
{% block bodyClass %}error pageGeneric{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic">
            <h1 class="h1 title">{{ language.translate("Internal Server Error - ERROR 500") }}</h1>
            <p class="p">{{ language.translate("The server encountered an internal error or misconfiguration and was unable to complete your request.") }}</p>

            <p class="res">
                {{ language.translate('If the problem persists, please <a href="mailto:support@euromillions.com">report your problem to  support@euromillions.com</a></a> and mention this error message, when the error occurred, and possibly anything you might have done that may have caused the error.<br><br> <strong>Thank you</strong>')}}
            </p>
        </div>
    </div>
</main>
{% endblock %}