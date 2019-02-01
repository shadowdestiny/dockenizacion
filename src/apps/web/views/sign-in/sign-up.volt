{% extends "main.volt" %}
{% block bodyClass %}sign-in minimal{% endblock %}

{% block template_css %}
    {#<link rel="stylesheet" href="/w/css/sign-in.css">#}
    <link Rel="Canonical" href="{{ language.translate(canonical) }}" />
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/w/css/_elements/threshold.scss">
{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{#{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}#}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts_after %}<script src="/w/js/react/tooltip.js"></script>{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}
{% block body %}
{% set signIn='{"myClass": "sign-in"}'|json_decode %}

<main id="content" style="padding-top:20px;">
    <div class="wrapper cl">
            <div class="signin-form sign-up">
                <h1 class="h1 title">{{ language.translate("signup_head") }}</h1>
                {% set url_signup = '/sign-up' %}
                {% include "sign-in/_sign-up.volt" %}
            </div>
    </div>
</main>
{% endblock %}
