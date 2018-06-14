{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/w/css/_elements/threshold.scss">
{% endblock %}
{% block bodyClass %}account{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}

{% block template_scripts_code %}

    $('#submitdeleteaccount').on('click',function(e){
        var targeted_popup_class = jQuery(this).attr('data-popup-open');
        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
        e.preventDefault();
    });

    $('#acceptdelete').on('click', function() {
        window.location.href = "/account/deleteaccount";
    });

    $('#canceldelete').on('click', function(e) {
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
        e.preventDefault();
    });

{% endblock %}



{% block body %}
    <main id="content" class="account-page">
        <div class="wrapper">
            {% include "account/_breadcrumbs.volt" %}
            <div class="nav">
                {% set activeSubnav='{"myClass": "account"}'|json_decode %}
                <div class="dashboard-menu--desktop">
                    {% include "account/_nav.volt" %}
                </div>

                <div class="dashboard-menu--mobile--back">
                    <a href="/account/wallet">
                        {{ language.translate("myAccount_account") }}
                    </a>
                </div>
            </div>
            <div class="content content-main-account">
                <div class="my-account--section my-account" style="display: block" >

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
                    {#<div class="wrap">#}
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
                    {#</div>#}
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


                {% include "account/email.volt" %}


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

                <div class="my-account--section personal-information" style="padding-bottom:100px">
                    <h2 class="">{{ language.translate("downloadinfo_head") }}</h2>
                    <div class="box-change-psw" style="display:block">
                        <div>
                        <p>
                            <label class="label" style="font-size:14px">{{ language.translate("downloadinfo_subhead") }}</label>
                        </p>
                        <p>
                             {{ language.translate("downloadinfo_text") }}
                        </p>
                        {{ form('/account/downloadinformation') }}
                        <label class="btn big blue left submit" for="submitinformation">
                            {{ language.translate("downloadinfo_btn") }}
                            <input id="submitinformation" type="submit" class="hidden2">
                        </label>
                        {{ endform() }}
                        </div>
                        <div style="margin-top:100px">
                            <p>
                               <label class="label" style="font-size:14px;">{{ language.translate("deleteaccount_subhead") }}</label>
                            </p>
                            <p>
                                 {{ language.translate("deleteaccount_text") }}
                            </p>
                            <label class="btn big blue left submit" for="submitdeleteaccount">
                                {{ language.translate("deleteaccount_btn") }}
                                <input data-popup-open="popup-1" id="submitdeleteaccount" type="button" class="hidden2">
                            </label>
                        </div>
                    </div>
                    <div class="popup" data-popup="popup-1">
                        <div class="popup-inner">
                            <h2></h2>
                                <p>{{ language.translate("deleteaccount_popup") }}</p>
                                 <label class="btn big blue left submit" for="acceptdelete">
                                                                {{ language.translate("deleteaccount_accept") }}
                                                                <input data-popup-open="popup-1" id="acceptdelete" type="button" class="hidden2">
                                                            </label>
                                 <label class="btn big blue left submit" for="canceldelete" style="margin-left:10px">
                                                                {{ language.translate("deleteaccount_cancel") }}
                                                                <input data-popup-close="popup-1" id="canceldelete" type="button" class="hidden2">
                                                            </label>
                        </div>
                     </div>
                </div>
            </div>

        </div>
    </main>
{% endblock %}