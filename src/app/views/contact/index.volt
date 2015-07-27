{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}

{% block header %}{% include "_elements/header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic small">
            <h1 class="h2">{{ language.translate("Contact us") }}</h1>

            <p>{{ language.translate("What can we help you with?") }}</p>

            <form novalidate>
                <div class="box error">
                    <span class="ico-warning ico"></span>
                    <span class="txt">{{ language.translate("Error info lorem ipsum") }}</span>
                </div>

                <label for="option" class="label">
                    <span class="txt">{{ language.translate("Select a topic") }}</span>
                    <select id="option">
                        <option>{{ language.translate("Playing the game") }}</option>
                        <option>{{ language.translate("Password, Email and Log in") }}</option>
                        <option>{{ language.translate("Account settings") }}</option>
                        <option>{{ language.translate("Bank and Credit card") }}</option>
                        <option>{{ language.translate("Other kind of questions") }}</option>
                    </select>
                </label>


                <label for="name" class="label">
                    <span class="txt">{{ language.translate("Full Name") }}</span>
                    <input id="name" class="input" type="text">
                </label>

                <label for="email" class="label">
                    <span class="txt">{{ language.translate("Email") }}</span>
                    <input id="email" class="input" type="email">
                </label>

                <label for="email" class="label">
                    <span class="txt">{{ language.translate("Content") }}</span>
                    <textarea id="textarea" class="textarea" rows="5"></textarea>
                </label>

                <div class="cl">
                    <a href="javascript:void(0);" class="btn blue big submit right">{{ language.translate("Send message") }}</a>
                </div>
            </form>
        </div>
    </div>
</main>

{% endblock %}