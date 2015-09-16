{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/cart.css">
{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart minimal{% endblock %}

{% block header %}
    {% set activeSteps='{"myClass": "step2"}'|json_decode %}
    {% include "_elements/sign-in-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <h1 class="h1 title yellow">{{ language.translate("Your Profile") }}</h1>
            <form novalidate>
                <div class="fields cl">
                    <div class="box error">
                        <span class="ico-warning ico"></span>
                        <span class="txt">Error info lorem ipsum</span>
                    </div>
                    {% set activePsw='{"myClass": "no"}'|json_decode %}
                    {% include "account/_user-detail.volt" %}
                </div>
                <hr class="hr yellow">
                {% include "account/_add-card.volt" %}
            </form>
        </div>
    </div>
</main>
{% endblock %}