{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}

{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart profile minimal sign-in{% endblock %}

{% block header %}
    {% include "_elements/minimal-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}

{% set signIn='{"myClass": "cart"}'|json_decode %}

<main id="content">
    <div class="wrapper">

        <div class="what-user">
            <div class="cols">
                <div class="col4">
                    <div class="box-basic log-in">
                        <div class="info">
                            <div class="txt1">{{ language.translate("Returning user?") }}</div>
                            <div class="txt2 yellow">
                                <div class="line-sep"></div>
                                <div class="line-txt">{{ language.translate("Log in") }}</div>
                            </div>
                        </div>
                        {% set url_signin = 'cart/login' %}
                        {% include "sign-in/_log-in.volt" %}
                    </div>
                </div>
                <div class="col8">
                    <div class="box-basic sign-in">
                        <div class="info">
                            <div class="txt1">{{ language.translate("New to Euromillion?") }}</div>
                            <div class="txt2 yellow">
                                <div class="line-sep"></div>
                                <div class="line-txt">{{ language.translate("Sign Up") }}</div>
                            </div>
                            {% set url_signup = 'cart/profile' %}
                            {% include "sign-in/_sign-up.volt" %}
{#
                            <p>
                                {{ language.translate("It is necessary to register to be able to finalize the play and to claim the prize in case of winning.") }}
                            </p>
                            <div class="cl">
                                <input id="register" type="submit" class="hidden2" />
                                <label for="register" class="submit btn big blue">{{ language.translate("Create account &amp; Play") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
                            </div>
#}
                        </div>

                    </div>

                </div>
            </div>

            <div class="terms txt">
                {{ language.translate("By signing in you agree to our") }} <a href="/legal/index">{{ language.translate("Terms &amp; Conditions") }}</a>
                <br>{{ language.translate("and that you are 18+ years old.") }}
            </div>

        </div>

{#
        <div class="box-basic medium content">
            <h1 class="h1 title yellow">{{ language.translate("Your Profile") }}</h1>
            <form action="/cart/profile" method="post">
                <div class="fields cl">
                    {% if errors %}
                    <div class="box error">
                        {% for error in errors %}
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                            <span class="txt">{{ error }}</span>
                        {% endfor %}
                    </div>
                    {% endif %}
                    <p>{{ language.translate("We need your information in order to proceed to payment.") }}</p>
                    {% set activePsw='{"myClass": "no"}'|json_decode %}
                    {% include "account/_user-detail.volt" %}
                </div>
                <div class="cl">
                    <label class="btn blue big right submit">
                        {{ language.translate("Save and proceed to Checkout") }} 
                        <input type="submit" class="hidden2">
                    </label>
                </div>

                <!-- Temporary commented (Credit Card details)
                <hr class="hr yellow">
                {% set component='{"where": "cart"}'|json_decode %}
                {% include "account/_add-card.volt" %}
                -->
            </form>
        </div>
#}
    </div>
</main>
{% endblock %}