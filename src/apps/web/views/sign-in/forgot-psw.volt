{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block bodyClass %}forgot-psw minimal{% endblock %}


{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="col-left">
            {% include "_elements/logo.volt" %}
        </div>
        <div class="col-right">
            <div class="box-basic small">
                <h1 class="h2 title">{{ language.translate("Password recovery") }}</h1>

                <p>{{ language.translate("Enter the email address associated with your Euromillions.com account, then click the button.") }}</p>

                <p>{{ language.translate("We'll email your a link to a page where you can easily create a new password.") }}</p>

                {{ form('/userAccess/forgotPassword') }}
                    {%if message %}
                        <div class="box success">
                            <svg class="ico v-success"><use xlink:href="/w/svg/icon.svg#v-success"></use></svg>
                            <span class="txt">{{ message }}</span>
                        </div>
                    {% endif %}

                    {% if errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                        </div>
                    {%  endif %}

                    <label for="email" class="label">{{ language.translate("Email address") }}</label>
                    {{ forgot_password_form.render('email', {'class':'input'}) }}

                    <p><strong>{{ language.translate("Insert captcha") }}</strong></p>
                    <div class="captcha">{{ captcha }}</div>
    {# *** Code to use in case no google captcha is utilised ***}
                    <br><a href="javascript:void(0);">{{ language.translate("reload the image") }}</a>
                    <br>
                    <input id="captcha" class="input" placeholder="{{ language.translate("Enter the code") }}">

    #}
                    <div class="cl">
                        <input id="go" type="submit" class="hidden2" />
                        <label for="go" class="submit btn big blue">{{ language.translate("Retrieve Password") }}<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
                    </div>
                {{ endform() }}
            </div>
        </div>
    </div>
</main>

{% endblock %}