{% extends "main.volt" %}
{% block bodyClass %}recovery no-nav{% endblock %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block template_scripts_code %}
var message = {{ message }};
if(message){
    $('form, .h2.title').hide();
    $('.login-link').show();
    $('.login-link').on('click', function(){window.location = '/sign-in';});
}
{% endblock %}
{% block mobileNav %}{% endblock %} {# Remove mobile navigation #}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="col-left">
            {% include "_elements/logo.volt" %}
        </div>
        <div class="col-right">
            <div class="box-basic small">
                <h1 class="h2 title res">{{ language.translate("Generate a new Password") }}</h1>
                {% if message %}
                    <div class="box success">
                        <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                        <div class="txt">{{ language.translate('Your password has been updated.')}}</div>
                    </div>
                {% endif %}
                {% if errors %}
                    <div class="box error">
                        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                        <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
                    </div>
                {% endif %}
                {{ form('/account/resetPassword') }}
                    <p>{{ language.translate("Insert your new password") }}</p>
                    <label for="new-password" class="label">{{ language.translate("New password") }} <span class="asterisk">*</span></label>
                    <input class="input" type="password" name="new-password" id="new-password" placeholder="{{ language.translate('New password') }}">

                    <label for="confirm-password" class="label">{{ language.translate("Confirm password") }} <span class="asterisk">*</span></label>
                    <input class="input" name="confirm-password" type="password" id="confirm-password" placeholder="{{ language.translate('Confirm password') }}">
                    <input type="hidden" value="{{ token }}" name="token" />
                    <label for="submitpass" class="btn big blue submit">
                        {{ language.translate('Update password') }} <input type="submit" class="hidden2" id="submitpass">
                    </label>
                {{ endform() }}
                <a style="display:none" class="btn big blue login-link" href="/sign-in">{{ language.translate('Continue and Log in ') }}</a>
            </div>
        </div>
    </div>
</main>
{% endblock %}

