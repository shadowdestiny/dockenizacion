{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
{% block bodyClass %}password{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}

<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "password"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("Change Password") }}</h1>
            <div class="box-change-psw" {% if which_form == 'password' %} style="display: block" {%  else %} style="display: none" {% endif %}>
                 <h1 class="h1 title yellow">{{ language.translate("My Account") }}</h2>
                 <h2 class="h3 yellow">{{ language.translate("Change password") }}</h2>
                {% set myPsw='{"value": "change"}'|json_decode %}
                {% include "_elements/generate-psw.volt" %}
            </div>
        </div>
    </div>
</main>
{% endblock %}