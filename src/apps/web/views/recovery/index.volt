{% extends "main.volt" %}
{% block template_css %}{% endblock %}
{% block bodyClass %}recovery minimal{% endblock %}

{% block template_css %}
<style>
.title{padding-bottom:10px;}
.box-basic .label, .box-basic .input{width:100%;}
.btn.big{margin-top:10px; width:100%; text-align:center;}
#content{padding-top:40px;}

@media only screen and (max-width:768px){
    #content{padding-top:0;}
}

</style>
{% endblock %}

{% block body %}
{{ form('/account/resetPassword') }}
<main id="content">
    <div class="wrapper cl">
        <div class="box-basic small content">
            <h1 class="h1 title res">{{ language.translate("Recovery Password") }}</h1>
            <p>{{ language.translate("Insert your new password") }}</p>
            {% if message %}
                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                    <div class="txt">Your password was reset correctly. Please go to <a href="/sign-in"> log in</a>. Good luck!</div>
                </div>
            {% endif %}
            {% if errors %}
                <div class="box error">
                    <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                    <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                </div>
            {% endif %}
            <label for="new-password" class="label">{{ language.translate("New password") }} <span class="asterisk">*</span></label>
            <input class="input" type="password" name="new-password" id="new-password" placeholder="{{ language.translate('New password') }}">

            <label for="confirm-password" class="label">{{ language.translate("Confirm password") }} <span class="asterisk">*</span></label>
            <input class="input" name="confirm-password" type="password" id="confirm-password" placeholder="{{ language.translate('Confirm password') }}">
            <input type="hidden" value="{{ token }}" name="token" />
            <label for="submitpass" class="btn big blue submit">
                {{ language.translate('Update password') }} <input type="submit" class="hidden2" id="submitpass">
            </label>
        </div>
    </div>
</main>
{{ endform() }}

{% endblock %}

