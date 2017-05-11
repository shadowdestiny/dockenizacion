{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/w/css/_elements/threshold.scss">
{% endblock %}
{% block bodyClass %}email{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
        $('#form-email-settings').on('submit',function(){
            var value = $('#amount-threshold').val().replace(/^0+/, '');
            $('#amount-threshold').val(value);
            return true;
        });
        $('#amount-threshold').on('keypress',function(e) {
            var evt = e || window.event;
            var code = evt.keyCode || evt.which;
            var chr = String.fromCharCode(code);
            var pattern = /^[0-9]/;
            if( code == 8 || code == 83 || code == 37 || code == 38 || code == 39 || code == 40) {
            } else {
                if(!pattern.test(chr)) {
                    evt.preventDefault();
                }
            }
        });
        $('#amount-threshold').on('focus', function(e) {
           if($(this).hasClass('error')) {
               $(this).removeClass('error');
           }
        });
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "email"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("Email Settings") }}</h1>

            {% if message %}
                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                    <div class="txt">{{ message }}</div>
                </div>
            {% endif %}
            {% if error_form %}
                <div class="box error">
                    <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                    <span class="txt">{% for error in error_form %}{{ error }}<br>{% endfor %}</span>
                </div>
            {% endif %}

            <form action="/account/editEmail" name="form_notifications" id="form-email-settings" method="post" class="form-currency">
                <div class="cl">
                    <div class="email-me">{{ language.translate("Email me") }}</div>
                    <ul class="no-li options">
                        {% if list_notifications is empty %}
                         {# EMTD - We shouldn't do something about when notification is empty? Alessio #}
                        {% else %}
                            {% for i,notification in list_notifications %}
                                {% if notification.notification.notification_type != 2 and notification.notification.notification_type != 3 %}
                                <li>
                                    <label for="check{{ i }}">
                                        <input id="check{{ i }}" data-type="{{ notification.notification.notification_type }}" name="{{ notification.name }}" data-value="{{ notification.config_value }}" data-id="{{ notification.id }}" class="checkbox" type="checkbox" {% if notification.active == true %} checked="checked" {% endif %} data-role="none">
                                        <span class="txt">{{ notification.notification.description }}</span>
                                    </label>
                                    {% if notification.notification.notification_type == 4 %}
                                        <select class="select" id="config_value" name="config_value_{{ notification.name }}">
                                            <option value="0" {% if notification.config_value == 0 %}selected{% endif %}>{{ language.translate("When I played a ticket") }}</option>
                                            <option value="1" {% if notification.config_value == 1 %}selected{% endif %}>{{ language.translate("Always") }}</option>
                                        </select>
                                    {% endif %}

                                    {% if notification.notification.notification_type == 1 %}
                                        <span class="currency">&euro;</span>
                                        <input name="config_value_{{ notification.name }}" id="amount-threshold" placeholder="{{ language.translate('withdraw_insertAmt') }}" type="text" value="{{ notification.config_value }}" class="input insert{% if error_form %}error{% endif %}"/>
                                    {% endif %}
                                </li>
                                {% endif %}
                            {% endfor %}
                        {% endif %}
                    </ul>
                </div>
                <div class="cl">
                    <label class="btn submit blue right" for="new-card">
                        {{ language.translate("Save Email Settings") }}
                        <input id="new-card" type="submit" class="hidden2">
                    </label>
                </div>
            </form>
        </div>
    </div>
</main>
{% endblock %}