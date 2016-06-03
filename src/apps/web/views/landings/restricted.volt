
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
    </main>
{% endblock %}