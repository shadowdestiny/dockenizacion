{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/legal.css">
    <link Rel="Canonical" href="{{ language.translate('canonical_legal_privacy') }}" />
{% endblock %}
{% block bodyClass %}privacy{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content" class="legal-page">
    <div class="wrapper">
        <div class="nav">
           {% set activeSubnav='{"myClass":"privacy"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="content">
            <h1 class="h1 title">{{ language.translate("privacy_head") }}</h1>
            <h2 class="h3">{{ language.translate("p1_subhead") }}</h2>
            <p>{{ language.translate("p1_text") }}</p>

            <h2 class="h3">{{ language.translate("p2_subhead") }}</h2>
            <p>{{ language.translate("p2_text") }}</p>

            <h2 class="h3">{{ language.translate("p3_subhead") }}</h2>
            <p>{{ language.translate("p3_text") }}</p>
            
            <h2 class="h3">{{ language.translate("p4_subhead") }}</h2>
            <p>{{ language.translate("p4_text") }}</p>

            <h2 class="h3">{{ language.translate("p5_subhead") }}</h2>
            <p>{{ language.translate("p5_text") }}</p>

            <h2 class="h3">{{ language.translate("p6_subhead") }}</h2>
            <p>{{ language.translate("p6_text") }}</p>
            
            <h2 class="h3">{{ language.translate("p7_subhead") }}</h2>
            <p>{{ language.translate("p7_text") }}</p>

            <h2 class="h3">{{ language.translate("p8_subhead") }}</h2>
            <p>{{ language.translate("p8_text") }}</p>

            <h2 class="h3">{{ language.translate("p9_subhead") }}</h2>
            <p>{{ language.translate("p9_text") }}</p>

            <h2 class="h3">{{ language.translate("p10_subhead") }}</h2>
            <p>{{ language.translate("p10_text") }}</p>

            <h2 class="h3">{{ language.translate("p11_subhead") }}</h2>
            <p>{{ language.translate("p11_text") }}</p>

            <h2 class="h3">{{ language.translate("p12_subhead") }}</h2>
            <p>{{ language.translate("p12_text") }}</p>

            <h2 class="h3">{{ language.translate("p13_subhead") }}</h2>
            <p>{{ language.translate("p13_text") }}</p>

            <hr class="yellow">
            <br><p>{{ language.translate("privacy_foot") }}</p>
        </div>
    </div>
</main>
{% endblock %}