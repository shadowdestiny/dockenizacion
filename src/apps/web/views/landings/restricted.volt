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
                <h1 class="h1 message yellow">{{ language.translate("The page you requested cannot be viewed") }}</h1>
                <h2 class="h0 title">{{ language.translate("It appears you are accessing our website from a restricted location.") }}</h2>
                <div class="cols bottom">
                    <a href="/{{ lottery }}/play" class="btn red big wide ui-link">PLAY NOW</a>
                </div>
                <p class="p">
                    {{ language.translate('Local regulations prohibit us from allowing you to view or purchase tickets on our site.') }}
                </p>
            </div>
        </div>
        <div class="looking">
            <div class="wrapper">
                <h2 class="h1 title2">{{ language.translate("If you believe you have been transferred to this page in error. Please contact") }}</h2>
                <ul class="no-li cl h3">
                    <li>support@euromillions.com</li>
                </ul>
            </div>
        </div>
    </main>
{% endblock %}