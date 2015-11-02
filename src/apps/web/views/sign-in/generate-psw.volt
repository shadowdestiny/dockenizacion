{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="w/css/sign-in.css">{% endblock %}
{% block bodyClass %}generate-psw minimal{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic small">
            <h1 class="h2">{{ language.translate("Create new password") }}</h1>

            <div class="my-left">
                <h1 class="h3">{{ language.translate("Choose a better password") }}</h1>
                <p>{{ language.translate("Passwords provide the first line of defense against unauthorized access to your computer. The stronger your password, the more protected your computer will be.") }}</p>
            </div>

            <div class="my-right">
                <h1 class="h3">{{ language.translate("How to choose a strong password") }}</h1>
                <ul class="ul">
                    <li>{{ language.translate("Is at least eight characters long.") }}</li>
                    <li>{{ language.translate("Does not contain your user name, real name, or company name.") }}</li>
                    <li>{{ language.translate("Does not contain a complete word.") }}</li>
                    <li>{{ language.translate("Contains characters from, uppercase and lowercase letters letters, numbers and symbols.") }}</li> 
                </ul>
            </div>
            {% set myPsw='{"value": ""}'|json_decode %}
            {% include "_elements/generate-psw.volt" %}
        </div>
    </div>
</main>
{% endblock %}