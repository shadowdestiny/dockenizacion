{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
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
                            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}
                    {% if  errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
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
        </div>
    </div>
</main>
{% endblock %}