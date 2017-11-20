{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block template_css %}
    <link Rel=”Canonical” href=”{{ language.translate('canonical_contact') }}” />
<style>
.box-basic p{margin-bottom:5px;}
.box-basic .input, .box-basic .textarea, .box-basic .select{width:100%; margin-bottom:1em;}
.box-basic .textarea{font-size:90%; height:180px;}
</style>
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic small">
            <h1 class="h2">{{ language.translate("contact_head") }}</h1>
            {% if message %}
                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg>
                    <span class="txt">{{ message }}</span>
                </div>
            {% endif %}

            {% if errors %}
                <div class="box error">
                    <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg>
                    <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
                </div>
            {% endif %}
            <p>{{ language.translate("question") }}</p>

            {{  form('/contact') }}

                {{ guestContactForm.render('topic', {'class':'input'}) }}
                
                {% if user_logged %}
                    <p class="logged">{{ language.translate("sendas") }} {{ user_name }}</p>
                {% else %}
                    {{ guestContactForm.render('fullname', {'class':'input'~form_errors['fullname']}) }}
                    {{ guestContactForm.render('email', {'class':'input'~form_errors['email']}) }}
                {% endif %}
                {{ guestContactForm.render('message', {'class':'textarea'~form_errors['message']}) }}
                {{ guestContactForm.render('csrf', ['value': security.getSessionToken()]) }}
                <p><strong>{{ language.translate("Insert captcha") }}</strong></p>
                <p><div class="captcha">{{ captcha }}</div>
                    {# *** Code to use in case no google captcha is utilised ***}
                                    <br><a href="javascript:void(0);">{{ language.app("reload the image") }}</a>
                                    <br>
                                    <input id="captcha" class="input" placeholder="{{ language.app("Enter the code") }}">
                    #}
                </p>
                <div class="cl">
                    <label for="submitBtn" class="btn blue big submit right">{{ language.translate("contact_btn") }}</label>
                    <input id="submitBtn" type="submit" class="hidden">
                </div>
            {{ endform() }}
        </div>

	<p align="center">{{ language.translate("contactUs") }} <br/> {{ language.translate("supportOpen") }} </p>

    </div>
</main>
{% endblock %}
