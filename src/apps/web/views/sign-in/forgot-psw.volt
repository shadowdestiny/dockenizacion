{% extends "main.volt" %}
{% block bodyClass %}forgot-psw no-nav{% endblock %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block mobileNav %}{% endblock %} {# Remove mobile navigation #}

{% block body %}
    <main id="content">
        <div class="wrapper">


            {#<div class="col-left">#}
            {#<div class="signin-form--logo">#}
            {#{% include "_elements/logo.volt" %}#}
            {#</div>#}
            {#</div>#}


            {#<div class="col-right">#}
            <div class="signin-form">
                <h1 class="h2 title">{{ language.translate("forgotpw_head") }}</h1>
                <p>{{ language.translate("forgotpw_text1") }}</p>
                <p>{{ language.translate("forgotpw_text2") }}</p>
                <br>
                <br>
                {{ form('/user-access/forgotPassword') }}
                {% if message %}
                    <div class="box success">
                        <svg class="ico v-checkmark">
                            <use xlink:href="/w/svg/icon.svg#v-checkmark"></use>
                        </svg>
                        <span class="txt">{{ message }}</span>
                    </div>
                {% endif %}

                {% if errors %}
                    <div class="box error">
                        <svg class="ico v-warning">
                            <use xlink:href="/w/svg/icon.svg#v-warning"></use>
                        </svg>
                        <div class="txt">
                            <ul class="no-li">{% for error in errors %}
                                    <li>{{ error }}</li>{% endfor %}</ul>
                        </div>
                    </div>
                {% endif %}

                {#<label for="email" class="label">{{ language.translate("forgotpw_formemail") }}</label>#}
                <div class="input--password">
                    {{ forgot_password_form.render('email', {'class':'input'}) }}
                </div>
                <br>
                {#<p><strong>{{ language.translate("forgotpw_formcaptcha") }}</strong></p>#}
                <div class="captcha">{{ captcha }}</div>
                {# *** Code to use in case no google captcha is utilised ***}
                                <br><a href="javascript:void(0);">{{ language.app("reload the image") }}</a>
                                <br>
                                <input id="captcha" class="input" placeholder="{{ language.app("Enter the code") }}">

                #}
                <div class="cl">
                    <input id="go" type="submit" class="hidden2"/>
                    <label for="go" class="submit  btn-theme--big">
                        {{ language.translate("forgotpw_btn") }}
                        {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
                    </label>
                </div>
                {{ endform() }}
            </div>
            {#</div>#}
        </div>
    </main>

{% endblock %}