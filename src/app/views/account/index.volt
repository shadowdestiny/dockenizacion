{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}account{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "account"}'|json_decode %}
           {% include "account/nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("My Account") }}</h1>
            <h2 class="h3 yellow">{{ language.translate("User detail") }}</h2>
            <form novalidate>
                <div class="box error">
                    <span class="ico-warning ico"></span>
                    <span class="txt">{{ language.translate("Error info lorem ipsum") }}</span>
                </div>
                <div class="wrap">
                    <div class="cols">
                        <div class="col6">
                            <label class="label" for="name">{{ language.translate("Name") }} <span class="asterisk">*</span></label>
                            <input id="name" class="input" type="text">

                            <label class="label" for="surname">{{ language.translate("Surname") }} <span class="asterisk">*</span></label>
                            <input id="surname" class="input" type="text">

                            <label class="label" for="email">{{ language.translate("Email") }} <span class="asterisk">*</span></label>
                            <input id="email" class="input" type="email">

                            <label class="label" for="country">{{ language.translate("Country of residence") }} <span class="asterisk">*</span></label>
                            <select id="country" class="select">
                                <option>{{ language.translate("Select a country") }}</option>
                                <option>lorem ipsum 1</option>
                                <option>lorem ipsum 2</option>
                                <option>lorem ipsum 3</option>
                                <option>lorem ipsum 4</option>
                            </select>
                        </div>
                        <div class="col6">
                            <label class="label" for="street">{{ language.translate("Street address") }}</label>
                            <input id="street" class="input" type="text">

                            <label class="label" for="po">{{ language.translate("ZIP / Postal code") }}</label>
                            <input id="po" class="input" type="text">

                            <label class="label" for="city">{{ language.translate("City") }}</label>
                            <input id="city" class="input" type="text">

                            <label class="label" for="phone">{{ language.translate("Phone Number") }}</label>
                            <input id="phone" class="input" type="text">
                        </div>
                    </div>
                    <div class="cols gap">
                        <div class="col12">
                            <a href="javascript:void(0)" class="change-psw btn gwy big">{{ language.translate("Change password") }}</a>
                            <label class="btn big blue right submit" for="submit">
                                {{ language.translate("Update profile details") }}
                                <input id="submit" type="submit" class="hidden2"> 
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="yellow">
            <div class="connect">
                <div class="cl">
                    <h2 class="title h3">{{ language.translate("Connect with Facebook") }}</h2>
                    <a class="btn gwy revoke" href="javascript:void(0)">{{ language.translate("Revoke Access") }}</a>
                </div>
                <p>{{ language.translate("Permissions: Read only") }}</p>
            </div>

        </div>
    </div>
</main>
{% endblock %}