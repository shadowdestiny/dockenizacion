{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block bodyClass %}cart fail minimal{% endblock %}
{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>if(window!=top){top.location.href=location.href;}</script>{% endblock %}


{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <div class="cols">
                <div class="col9">
                    <h1 class="h1 title red">{{ language.translate("Payment Unsuccessful") }}</h1>
                    <h2 class="h2">{{ language.translate("Whoooooops! Something went terribly wrong...") }}</h2>
                    <p class="txt">
                        {{ language.translate("An error has occurred and your payment has failed.") }}
                        <br><a href="/play/christmas-lottery">{{ language.translate("Please, try again.") }}</a>
                    </p>
                    <p>
                        <strong>{{ language.translate("If difficulties persist, please contact our support team.") }}</strong>
                        <br>{{ language.translate("Write us directly at") }} <em><a href="mailto:support@euromillions.com">support@euromillions.com</a></em> {{ language.translate("and describe in detail your issue. Describe as well what browser and operative system you are using for the operation. Thank you.")}}
                    </p>
                </div>
                <div class="col3 img"></div>
            </div>
        </div>
    </div>
</main>
{% endblock %}
