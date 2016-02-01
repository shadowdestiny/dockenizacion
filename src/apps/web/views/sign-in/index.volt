{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block bodyClass %}sign-in minimal{% endblock %}

{% block template_scripts %}
<script>
function swap(myVar){
    $(myVar).click(function(event){
        event.preventDefault();
        $(".log-in, .sign-up").toggle();
    });
}

$(function(){
    swap(".log-in .box-extra a, .sign-up .box-extra a");
});
</script>

{% if which_form == 'up' %}
<script>
        $(".sign-up").show()
        $(".log-in").hide();
</Script>
{% endif %}
{% endblock %}

{% block body %}

{% set signIn='{"myClass": "sign-in"}'|json_decode %}

<main id="content" style="padding-top:40px;">
    <div class="wrapper cl">
        <div class="col-left">
            <a href="/" title="{{ language.translate('Go to homepage')}}"><img class="v-logo vector" alt="Euromillions" src="/w/svg/logo.svg"></a>
        </div>
        <div class="col-right">
            <div class="box-basic log-in">
                <h1 class="h1 title">
                    <span class="line"></span>
                    <span class="line-txt">{{ language.translate("Log in") }}</span>
                </h1>

                {# DO NOT DELETE - Facebook connect
                <div class="connect">
                    <a href="#" class="btn blue big"><svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"></use></svg> {{ language.translate("Log in with Facebook") }}</a>
                    <a href="#" class="btn red big"><svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"></use></svg></span> {{ language.translate("Log in with Google") }}</a>
                </div>

                <div class="separator">
                    <hr class="hr">
                    <span class="bg-or"><span class="or">{{ language.translate("or") }}</span></span>
                </div>
                #}
                {% set url_signin = '/sign-in' %}
                {% include "sign-in/_log-in.volt" %}
            </div>

            <div class="box-basic sign-up hidden">
                <h1 class="h1 title">
                    <span class="line"></span>
                    <span class="line-txt">{{ language.translate("Sign up") }}</span>
                </h1>
                {% set url_signup = '/sign-up' %}
                {% include "sign-in/_sign-up.volt" %}
            </div>

            <div class="terms txt">
                {{ language.translate("By signing in you agree to our") }} <a href="/legal/index">{{ language.translate("Terms &amp; Conditions") }}</a>
                <br>{{ language.translate("and that you are 18+ years old.") }}
            </div>

        </div>

    </div>
</main>
{% endblock %}

