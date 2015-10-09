{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block template_scripts %}
<script>
$(function(){
    btnShowHide('.change-psw', '.box-change-psw', '.my-account')
});
</script>
{% endblock %}  
{% block bodyClass %}account{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}

<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "account"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <div class="my-account">

                <h1 class="h1 title yellow">{{ language.translate("My Account") }}</h1>
                <h2 class="h3 yellow">{{ language.translate("User detail") }}</h2>

                <form novalidate>
                    <div class="box error">
                        <span class="ico-warning ico"></span>
                        <span class="txt">{{ language.translate("Error info lorem ipsum") }}</span>
                    </div>
                    <div class="wrap">
                        <div class="cols">
                            <div class="col6">
                                {% include "account/_user-detail.volt" %}
                            </div>
                            <div class="col6">
                                <label class="label" for="street">{{ language.translate("Street address") }}</label>
                                <input id="street" class="input" type="text">

                                <label class="label" for="po">{{ language.translate("ZIP / Postal code") }}</label>
                                <input id="po" class="input" type="text">

                                <label class="label" for="city">{{ language.translate("City") }}</label>
                                <input id="city" class="input" type="text">

                                <label class="label" for="phone">{{ language.translate("Phone Number") }}</label>
                                <input id="phone" class="input" type="text">
                            </div>
                        </div>

                *click to see change psw area*
                        <div class="cols gap" style="margin-bottom:0;"> {# temporary inline style to remove the gap with #}
                            <div class="col12">
                                <a href="javascript:void(0)" class="change-psw btn gwy big">{{ language.translate("Change password") }}</a>
                                <label class="btn big blue right submit" for="submit">
                                    {{ language.translate("Update profile details") }}
                                    <input id="submit" type="submit" class="hidden2">
                                </label>
                            </div>
                        </div>

                    </div>
                </form>

                {# DO NOT DELETE - Facebook revoke access
                <hr class="yellow">
                <div class="connect">
                    <div class="cl">
                        <h2 class="title h3">{{ language.translate("Connect with Facebook") }}</h2>
                        <a class="btn gwy revoke" href="javascript:void(0)">{{ language.translate("Revoke Access") }}</a>
                    </div>
                    <p>{{ language.translate("Permissions: Read only") }}</p>
                </div>
                #}
            </div>
            <div class="box-change-psw hidden">
                 <h1 class="h1 title yellow">{{ language.translate("My Account") }}</h2>
                 <h2 class="h3 yellow">{{ language.translate("Change password") }}</h2>
                {% set myPsw='{"value": "change"}'|json_decode %}
                {% include "_elements/generate-psw.volt" %}
            </div>
        </div>
    </div>
</main>
{% endblock %}