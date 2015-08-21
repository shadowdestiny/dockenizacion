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

            <form novalidate>
                <div class="box error">
                    <span class="ico-warning ico"></span>
                    <span class="txt">{{ language.translate("Error info lorem ipsum") }}</span>
                </div>

                <label for="option" class="label">
                    {{ language.translate("Select a topic") }}
                </label>
                <select class="select" id="option">
                    <option>{{ language.translate("Playing the game") }}</option>
                    <option>{{ language.translate("Password, Email and Log in") }}</option>
                    <option>{{ language.translate("Account settings") }}</option>
                    <option>{{ language.translate("Bank and Credit card") }}</option>
                    <option>{{ language.translate("Other kind of questions") }}</option>
                </select>



                <label for="name" class="label"> 
                    {{ language.translate("Full Name") }}
                </label>
                <input id="name" class="input" type="text">
               

                <label for="email" class="label">
                    {{ language.translate("Email") }}
                </label>
                <input id="email" class="input" type="email">

                <label for="email" class="label">
                    {{ language.translate("Content") }}
                </label>
                <textarea id="textarea" class="textarea" rows="5"></textarea>

                <div class="cl">
                    <label for="submitBtn" class="btn blue big submit right">{{ language.translate("Send message") }}</label>
                    <input id="submitBtn" type="submit" class="hidden">
                </div>
            </form>
        </div>
    </div>
</main>

{% endblock %}