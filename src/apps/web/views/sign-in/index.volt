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
        {#<div class="col-left">#}
            {#{% include "_elements/logo.volt" %}#}
        {#</div>#}
        {#<div class="col-right">#}
            <div class="signin-form log-in">
                <h1 class="h1 title">{{ language.translate("signin_head") }}</h1>

                {# DO NOT DELETE - Facebook connect
                <div class="connect">
                    <a href="#" class="btn blue big"><svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"></use></svg> {{ language.app("Log in with Facebook") }}</a>
                    <a href="#" class="btn red big"><svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"></use></svg></span> {{ language.app("Log in with Google") }}</a>
                </div>

                <div class="separator">
                    <hr class="hr">
                    <span class="bg-or"><span class="or">{{ language.app("or") }}</span></span>
                </div>#}

                {% set url_signin = '/sign-in' %}
                {% include "sign-in/_log-in.volt" %}
            </div>
            {#<div class="terms txt">#}
                {#{{ language.translate("signup_signInTC") }}#}
            {#</div>#}
        {#</div>#}
    </div>
</main>
{% endblock %}
