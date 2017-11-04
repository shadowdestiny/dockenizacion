{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}cookies{% endblock %}

{% block header %}
    {% set activeNav='{"myClass":""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
    <main id="content" class="legal-page">
        <div class="wrapper">
            <div class="nav">
                {% set activeSubnav='{"myClass":"cookies"}'|json_decode %}
                {% include "legal/_nav.volt" %}
            </div>
            <div class="content">
                <h1 class="h1 title yellow">{{ language.translate("cookies_head") }}</h1>
                <p>
                    <strong>{{ language.translate("cookies_paragraph1") }}</strong>
                </p>
                <h2 class="h3 title yellow">{{ language.translate("whathow_subhead") }}</h2>
                <p>
                    {{ language.translate("whathow_p1") }}
                </p>
                <p>{{ language.translate("whathow_p2") }}</p>
                <ul class="list">
                    <li>{{ language.translate("whathow_ul1") }}</li>
                    <li>{{ language.translate("whathow_ul2") }}</li>
                    <li>{{ language.translate("whathow_ul3") }}</li>
                </ul>

                <h2 class="h3 title yellow">{{ language.translate("thirdParty_subhead") }}</h2>
                <p>{{ language.translate("thirdParty_p1") }}</p>


                <h2 class="h3 title yellow">{{ language.translate("moreInfo_subhead") }}</h2>
                <ul class="list">
                    <li>{{ language.translate("moreInfo_link1") }}</li>
                    <li>{{ language.translate("moreInfo_link2") }}</li>
                    <li>{{ language.translate("moreInfo_link3") }}</li>
                </ul>

                <h2 class="h3 title yellow">{{ language.translate("disable_subhead") }}</h2>
                <p>{{ language.translate("disable_p1") }}</p>
                <ul class="list">
                    <li>{{ language.translate("disable_link1") }}</li>
                    <li>{{ language.translate("disable_link2") }}</li>
                    <li>{{ language.translate("disable_link3") }}</li>
                    <li>{{ language.translate("disable_link4") }}</li>
                    <li>{{ language.translate("disable_link5") }}</li>
                    <li>{{ language.translate("disable_link6") }}</li>
                    <li>{{ language.translate("disable_link7") }}</li>
                    <li>{{ language.translate("disable_link8") }}</li>
                    <li>{{ language.translate("disable_link9") }}</li>
                </ul>
            </div>
        </div>


        <div class="wrapper">
            <div class="thank-you-block">
                <div class="thank-you-block--top">
                    <h1>Thank you</h1>

                    <h2>
                        thanks for your order and good luck!
                    </h2>
                    <p>
                        You just completed your payment
                    </p>
                    <div class="count">
                        Countdown to next draw is 7 hours and 11 minutes
                    </div>
                    <div class="btn-row">
                        <a href="#" class="btn-theme--big">Play more</a>
                    </div>
                </div>


                <div class="thank-you-block--jackpot">
                    <p>
                        your lines played for this friday’s draw 28 may 2017
                    </p>
                    <h2>
                        jackpot €70 milliones
                    </h2>
                </div>

                <div class="thank-you-block--rows">
                    <div class="thank-you-block--row">
                        <p>
                            <b>LINE A</b> Tuesday, 23.01.2017
                        </p>

                        <ul class="no-li inline numbers small">

                            <li>9</li>
                            <li>12</li>
                            <li>29</li>
                            <li>39</li>
                            <li>45</li>
                            <li class="star">5</li>
                            <li class="star">12</li>

                        </ul>


                    </div>
                    <div class="thank-you-block--row"></div>
                </div>

                <div class="thank-you-block--bottom">
                    <p>
                        We have sent you an email with details of the line you played.
                        You can also see the lines you have played on our tickets page
                    </p>
                    <h3>
                        In case of winning
                    </h3>
                    <p>
                        We will contact you at joseluis@panamedia.net be sure to add our
                        email support@euromillions.com to your adress book to avoid spam
                        filters.
                    </p>
                </div>
            </div>
        </div>
    </main>
{% endblock %}

