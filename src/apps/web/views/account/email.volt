{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
{% block bodyClass %}email{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block template_scripts %}
<script>
$(function() {
//    $('.box-basic input').on('click', function () {
//        $id = $(this).data('id');
//        $checked = $(this).is(':checked');
//        $value = $(this).next().val();
//        $type = $(this).data('type');
//        params = 'id=' + $id + '&active=' + $checked + '&value=' + $value + '&type=' + $type;
//        $.ajax({
//            url: '/account/editEmail/',
//            data: params,
//            type: 'POST',
//            dataType: 'json',
//            success: function (json) {
//                if(json.result == 'OK') {
//                    $('.box').removeClass('hidden');
//                }
//            }
//        })
//    });
});
</script>
{% endblock %}
{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "email"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            {% if message %}
                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                    {{ message }}
                </div>
            {% endif %}
            {% if error %}
                <div class="box danger">
                    <svg class="ico v-cross"><use xlink:href="/w/svg/icon.svg#v-cross"></use></svg>
                    {{ error }}
                </div>
            {% endif %}

            <h1 class="h1 title yellow">{{ language.translate("Email Settings") }}</h1>
            <form action="/account/editEmail" name="form_notifications" method="post" >

                <div class="cl">
                    <div class="email-me">
                        Email me
                    </div>
                    <ul class="no-li options">
                        {% if list_notifications is empty %}
                         {# EMTD - We shouldn't do something about when notification is empty? Alessio #}
                        {% else %}
                            {% for notification in list_notifications %}
                                <li>
                                    <label for="check2">
                                        <input id="check2" data-type="{{ notification.notification.notification_type }}" name="{{ notification.name }}" data-value="{{ notification.config_value }}" data-id="{{ notification.id }}" class="checkbox" type="checkbox" {% if notification.active == true %} checked="checked" {% endif %} data-role="none">
                                        {{ notification.notification.description }}
                                    </label>
                                    {% if notification.notification.notification_type == 4 %}
                                        <select id="config_value" name="config_value_{{ notification.name }}">
                                            <option value="0" {% if notification.config_value == 0 %}selected{% endif %}>{{ language.translate("When I played a ticket") }}</option>
                                            <option value="1" {% if notification.config_value == 1 %}selected{% endif %}>{{ language.translate("Always") }}</option>
                                        </select>
                                    {% endif %}

                                    {% if notification.notification.notification_type == 1 %}
                                        <input name="config_value_{{ notification.name }}" value="{{ notification.config_value }}"/>
                                    {% endif %}
                                </li>
                            {% endfor %}
                        {% endif %}
                    </ul>
                </div>
                <div class="cl">
                    <label class="btn submit blue right" for="new-card">
                        {{ language.translate("Save Email Settings") }}
                        <input id="new-card" type="submit" class="hidden">
                    </label>
                </div>
            </form>
        </div>
    </div>
</main>
{% endblock %}