{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
{% block bodyClass %}account{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}




{% block body %}
    <main id="content" class="account-page">
        <div class="wrapper">
            {% include "account/_breadcrumbs.volt" %}
            <div class="nav">
                {% set activeSubnav='{"myClass": "account"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>
            <div class="content content-main-account">
                <div class="my-account--section my-account" {% if which_form == 'index' %} style="display: block" {% else %} style="display: none" {% endif %}>

                    {#<h1 class="h1 title yellow">{{ language.translate("account_head") }}</h1>#}
                    <h2 class="">{{ language.translate("account_subhead") }}</h2>

                    {{ form('/account/index') }}
                    {% if msg %}
                        <div class="box success">
                            <svg class="ico v-checkmark">
                                <use xlink:href="/w/svg/icon.svg#v-checkmark"></use>
                            </svg>
                            <span class="txt">{{ language.translate(msg) }}</span>
                        </div>
                    {% endif %}
                    {% if  errors %}
                        <div class="box error">
                            <svg class="ico v-warning">
                                <use xlink:href="/w/svg/icon.svg#v-warning"></use>
                            </svg>
                            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                        </div>
                    {% endif %}
                    <div class="wrap">
                        <table class="">
                            {#<div class="">#}
                            {#{% include "account/_user-detail.volt" %}#}
                            {#</div>#}

                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label" for="name">{{ language.translate("account_name") }} <span
                                                    class="asterisk">*</span></label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('name', {'class':'input' }) }}
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label" for="surname">{{ language.translate("account_surname") }}
                                            <span
                                                    class="asterisk">*</span></label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('surname', {'class':'input' }) }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label"
                                               for="email">{{ language.translate("account_email") }}</label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('email', {'class':'input','disabled':'disabled'}) }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label"
                                               for="country">{{ language.translate("account_country") }}
                                        </label>
                                    </p>
                                </td>
                                <td class="td-input">

                                    <p class="selectbox">
                                        {{ myaccount.render('country', {'class':'select','disabled':'disabled'}) }}
                                    </p>

                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label"
                                               for="street">{{ language.translate("account_street") }}</label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('street', {'class':'input',"id":"street"}) }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label" for="po">{{ language.translate("account_zip") }}</label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('zip', {'class':'input',"id":"po"}) }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label" for="city">{{ language.translate("account_city") }}</label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('city', {'class':'input',"id":"city"}) }}
                                    </p>
                                </td>
                            </tr>


                            <tr>
                                <td class="td-label">
                                    <p>
                                        <label class="label"
                                               for="phone">{{ language.translate("account_phone") }}</label>
                                    </p>
                                </td>
                                <td class="td-input">
                                    <p>
                                        {{ myaccount.render('phone_number', {'class':'input',"id":"phone"}) }}
                                    </p>
                                </td>
                            </tr>


                        </table>

                        <div class="cols gap"
                             style="margin-bottom:0;"> {# temporary inline style to remove the gap with #}
                            <div class="col12">
                                <label class="btn big blue right submit" for="submit">
                                    {{ language.translate("account_update_btn") }}
                                    <input id="submit" type="submit" class="hidden2">
                                </label>
                            </div>
                        </div>
                    </div>
                    {{ endform() }}

                    {# DO NOT DELETE - Facebook revoke access
                    <hr class="yellow">
                    <div class="connect">
                        <div class="cl">
                            <h2 class="title h3">{{ language.app("Connect with Facebook") }}</h2>
                            <a class="btn gwy revoke" href="javascript:void(0)">{{ language.app("Revoke Access") }}</a>
                        </div>
                        <p>{{ language.app("Permissions: Read only") }}</p>
                    </div>
                    #}
                </div>


                <div class="my-account--section my-email">
                    <h2 class="">{{ language.translate("email_head") }}</h2>



                    <form action="/account/editEmail" name="form_notifications" id="form-email-settings" method="post" class="form-currency">
                        <div class="cl">
                            {#<div class="email-me">{{ language.translate("email_emailme") }}</div>#}
                            <table class="options">

                                {#TODO : Add real variables here#}
                                {#Clear html start#}
                                <tr>
                                    <td class="td-label">
                                        <p>
                                            <label for="old-password" class="label">
                                                Email me when <br>
                                                JACKPOT reach
                                            </label>
                                        </p>
                                    </td>
                                    <td class="td-input">
                                        <p>
                                            <input type="text" id="" name="" class="input" placeholder="€ Insert amount">
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="td-label">
                                        <p>
                                            <label for="old-password" class="label">
                                                Email me results <br>
                                                of the draw
                                            </label>
                                        </p>
                                    </td>
                                    <td class="td-input">
                                        <p>
                                            <input type="text" id="" name="" class="input" placeholder="When I played the ticket">
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="td-label">
                                        <p>
                                            <label for="old-password" class="label">
                                                Email me
                                            </label>
                                        </p>
                                    </td>
                                    <td class="td-input">
                                        <p>
                                            <input type="text" id="" name="" class="input" placeholder="With the best offers and promotions">
                                        </p>
                                    </td>
                                </tr>


                                {#Clear html end#}



                                {% if list_notifications is empty %}
                                    {# EMTD - We shouldn't do something about when notification is empty? Alessio #}
                                {% else %}
                                    {% for i,notification in list_notifications %}
                                        {% if notification.notification.notification_type != 2 and notification.notification.notification_type != 3 %}
                                            <tr>
                                                <td class="td-label">
                                                    <p>
                                                        <label for="check{{ i }}">
                                                            <input id="check{{ i }}" data-type="{{ notification.notification.notification_type }}" name="{{ notification.name }}" data-value="{{ notification.config_value }}" data-id="{{ notification.id }}" class="checkbox" type="checkbox" {% if notification.active == true %} checked="checked" {% endif %} data-role="none">
                                                            <span class="txt">{{ language.translate(notification.notification.description) }}</span>
                                                        </label>
                                                    </p>
                                                </td>
                                                <td class="td-input">
                                                    <p>
                                                        {% if notification.notification.notification_type == 4 %}
                                                            <select class="select" id="config_value" name="config_value_{{ notification.name }}">
                                                                <option value="0" {% if notification.config_value == 0 %}selected{% endif %}>{{ language.translate("email_played_dropdown") }}</option>
                                                                <option value="1" {% if notification.config_value == 1 %}selected{% endif %}>{{ language.translate("email_always_dropdown") }}</option>
                                                            </select>
                                                        {% endif %}

                                                        {% if notification.notification.notification_type == 1 %}
                                                            <span class="currency">&euro;</span>
                                                            <input name="config_value_{{ notification.name }}" id="amount-threshold" placeholder="{{ language.translate('withdraw_insertAmt') }}" type="text" value="{{ notification.config_value }}" class="input insert{% if error_form %}error{% endif %}"/>
                                                        {% endif %}
                                                    </p>
                                                </td>
                                            </tr>
                                        {% endif %}
                                    {% endfor %}
                                {% endif %}
                            </table>
                        </div>
                        <div class="cl">
                            <label class="btn submit blue right" for="new-card">
                                {{ language.translate("email_save_btn") }}
                                <input id="new-card" type="submit" class="hidden2">
                            </label>
                        </div>
                    </form>
                </div>


                <div class="my-account--section my-password">
                    <h2 class="">{{ language.translate("password_head") }}</h2>
                    <div class="box-change-psw" {% if which_form == 'password' %}style="display:block"
                            {% else %}
                                {#TODO: check this condition#}
                                {#style="display:none"#}
                            {% endif %}>
                        {% set myPsw='{"value": "change"}'|json_decode %}
                        {% include "_elements/generate-psw.volt" %}
                    </div>
                </div>


            </div>
        </div>
    </main>
{% endblock %}