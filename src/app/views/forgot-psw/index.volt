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

            <form novalidate>

                <div class="box error">
                    <span class="ico-warning ico"></span>
                    <span class="txt">Error info lorem ipsum</span>
                </div>

                <label for="email" class="label">
                    <span class="txt">{{ language.translate("Email address") }}</span>
                    <input id="email" class="input email" type="email">
                </label>

                <p>
                    <strong>{{ language.translate("Insert captcha") }}</strong>
                    <br><img style="width:250px;" src="/img/fake-captcha.gif" alt="">
                </p>

{# *** Code to use in case no google captcha is utilised ***}
                <br><a href="javascript:void(0);">{{ language.translate("reload the image") }}</a>
                <br>
                <input id="captcha" class="input" placeholder="{{ language.translate("Enter the code") }}">

#}
                <div class="cl">
                    <a href="javascript:void(0);" class="btn blue big submit right">{{ language.translate("Save Password") }}</a>
                </div>
            </form>
		</div>
	</div>
</main>

{% endblock %}