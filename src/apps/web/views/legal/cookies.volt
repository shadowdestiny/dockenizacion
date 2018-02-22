{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/legal.css">
    <link Rel="Canonical" href="{{ language.translate('canonical_legal_cookies') }}" />
{% endblock %}
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

        {#TODO: we shoul to use this block in correct place#}
        {#<div class="wrapper">#}
            {#{% include "_elements/thank-you.volt" %}#}
        {#</div>#}

        {#TODO: we shoul to use this block in correct place#}
        {#<div class="wrapper">#}
            {#{% include "_elements/review_and_pay.volt" %}#}
        {#</div>#}

        {#TODO: we shoul to use this block in correct place#}
        {#<div class="wrapper">#}
            {#{% include "_elements/cart.volt" %}#}
        {#</div>#}



    </main>
{% endblock %}

