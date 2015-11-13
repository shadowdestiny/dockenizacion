{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
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
            <div class="my-account" {% if which_form == 'index' %} style="display: block" {%  else %} style="display: none" {% endif %}>

                <h1 class="h1 title yellow">{{ language.translate("My Account") }}</h1>
                <h2 class="h3 yellow">{{ language.translate("User detail") }}</h2>

                {{ form('account/index') }}
                    {% if msg %}
                        <div class="box success">
                            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#checkmark"></use></svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}
                    {% if  errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#warning"></use></svg>
                            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                        </div>
                    {% endif %}
                    <div class="wrap">
                        <div class="cols">
                            <div class="col6">
                                {% include "account/_user-detail.volt" %}
                            </div>
                            <div class="col6">
                                <label class="label" for="street">{{ language.translate("Street address") }}</label>
                                {{ myaccount.render('street', {'class':'input'}) }}

                                <label class="label" for="po">{{ language.translate("ZIP / Postal code") }}</label>
                                {{ myaccount.render('zip', {'class':'input'}) }}

                                <label class="label" for="city">{{ language.translate("City") }}</label>
                                {{ myaccount.render('city', {'class':'input'}) }}

                                <label class="label" for="phone">{{ language.translate("Phone Number") }}</label>
                                {{ myaccount.render('phone_number', {'class':'input'}) }}
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
                {{ endform() }}

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
            <div class="box-change-psw hidden" {% if which_form == 'password' %} style="display: block" {%  else %} style="display: none" {% endif %}>
                 <h1 class="h1 title yellow">{{ language.translate("My Account") }}</h2>
                 <h2 class="h3 yellow">{{ language.translate("Change password") }}</h2>
                {% set myPsw='{"value": "change"}'|json_decode %}
                {% include "_elements/generate-psw.volt" %}
            </div>
        </div>
    </div>
</main>
{% endblock %}