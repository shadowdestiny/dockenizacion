{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/error.css">{% endblock %}
{% block bodyClass %}error page404{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}
{% block body %}
    <main id="content">
        <div class="bg">
            <div class="wrapper">
                <h1 class="h1 message yellow">{{ language.translate("The part of the site your are viewing is being updated.") }}</h1>
                <h2 class="h0 title">{{ language.translate("While we update you can still play EuroMillions with us.") }}</h2>
                <div class="cols bottom">
                    <a href="/{{ lottery }}/play" class="btn red big wide ui-link">PLAY NOW</a>
                </div>
                <p class="p">
                    {{ language.translate('We offer the cheapest price for Euromillions tickets available anywhere online.') }}
                </p>
            </div>
        </div>
        <div class="looking">
            <div class="wrapper">
                <h2 class="h1 title2">{{ language.translate("Or maybe you were looking for") }}</h2>
                <ul class="no-li cl h3">
                    <li><a href="/{{ lottery }}/play">{{ language.translate("Playing the Lottery") }}</a></li>
                    <li><a href="/{{ lottery }}/numbers">{{ language.translate("Draw History") }}</a></li>
                    <li><a href="/{{ lottery }}/faq">{{ language.translate("Asking for help") }}</a></li>
                    <li><a href="/{{ lottery }}/help">{{ language.translate("How to Play Lotto") }}</a></li>
                    <li><a href="/{{ lottery }}/index#about-us">{{ language.translate("About Euromillions") }}</a></li>
                    <li><a href="/contact">{{ language.translate("Contact us") }}</a></li>
                </ul>
            </div>
        </div>
    </main>
{% endblock %}