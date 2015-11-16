{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic small">
            <h1 class="h2">{{ language.translate("Contact us") }}</h1>

            <p>{{ language.translate("What can we help you with?") }}</p>

            {{  form('contact') }}
                {% if message %}
                    <div class="box {{ class }}">
                        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                        <span class="txt">{{ message }}</span>
                    </div>
                {% endif %}

                {{ guestContactForm.render('topic', {'class':'input'}) }}
                
                {% if user_logged %}
                    <p class="logged">{{ language.translate('Sending message as: ') }}{{ user_name }}</p>
                {% else %}
                    {{ guestContactForm.render('fullname', {'class':'input'}) }}
                    {{ guestContactForm.render('email', {'class':'input'}) }}
                {% endif %}
                {{ guestContactForm.render('message', {'class':'textarea'}) }}
                {{ guestContactForm.render('csrf', ['value': security.getSessionToken()]) }}

                <div class="cl">
                    <label for="submitBtn" class="btn blue big submit right">{{ language.translate("Send message") }}</label>
                    <input id="submitBtn" type="submit" class="hidden">
                </div>
            {{ endform() }}
        </div>
    </div>
</main>

{% endblock %}