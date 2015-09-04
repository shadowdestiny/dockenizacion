{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/css/sign-in.css">{% endblock %}
{% block bodyClass %}forgot-psw minimal{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
        <div class="box-basic small">
            <h1 class="h2">{{ language.translate("Password recovery") }}</h1>

            <p>{{ language.translate("Enter the email address associated with your Euromillions.com account, then click the button.") }}</p>
            <p>{{ language.translate("We'll email your a link to a page where you can easily create a new password.") }}</p>

            {{ form('userAccess/forgotPassword') }}


                {%if message %}
                    <div class="box error">
                        <span class="ico- ico"></span>
                        <span class="txt">{{ message }}</span>
                    </div>
                {% endif %}

                {% if errors %}
                    <div class="box error">
                        <span class="ico-warning ico"></span>
                        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                    </div>
                {%  endif %}

                <label for="email" class="label">{{ language.translate("Email address") }}</label>
                {{ forgot_password_form.render('email', {'class':'input'}) }}


                <p>
                    <strong>{{ language.translate("Insert captcha") }}</strong>
                    <br>
                    {{ captcha }}
                </p>

{# *** Code to use in case no google captcha is utilised ***}
                <br><a href="javascript:void(0);">{{ language.translate("reload the image") }}</a>
                <br>
                <input id="captcha" class="input" placeholder="{{ language.translate("Enter the code") }}">

#}
                <div class="cl">
                            <div class="cl">
                                <input id="go" type="submit" class="hidden2" />
                                <label for="go" class="submit btn big blue">{{ language.translate("Save Password") }}<span class="ico ico-arrow-right"></span></label>
                            </div>
                </div>
            {{ endform() }}
		</div>
	</div>
</main>

{% endblock %}