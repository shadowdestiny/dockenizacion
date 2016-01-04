{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart profile minimal{% endblock %}

{% block header %}
    {% include "_elements/minimal-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
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
                {# Temporary commented (Credit Card details)
                <hr class="hr yellow">
                {% set component='{"where": "cart"}'|json_decode %}
                {% include "account/_add-card.volt" %}
                #}
            </form>
        </div>
    </div>
</main>
{% endblock %}