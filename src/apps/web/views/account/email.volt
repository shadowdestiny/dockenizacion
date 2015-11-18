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
    $('.box-basic input').on('click', function () {
        $id = $(this).data('id');
        $checked = $(this).is(':checked');
        $value = $(this).next().val();
        $type = $(this).data('type');
        params = 'id=' + $id + '&active=' + $checked + '&value=' + $value + '&type=' + $type;
        $.ajax({
            url: '/account/editEmail/',
            data: params,
            type: 'POST',
            dataType: 'json',
            success: function (json) {
                if(json.result == 'OK') {
                    $('.box').removeClass('hidden');
                }
            }
        })
    });
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
        <div class="box success hidden">
            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>

        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("Email Settings") }}</h1>
            <div class="cl">
                <div class="email-me">
                    Email me
                </div>
                <ul class="no-li options">
                    {% if list_notifications is empty %}
                    {% else %}
                    {% for notification in list_notifications %}
                        <li>
                            <label for="check2">
                                <input id="check2" data-type="{{ notification.type }}" data-value="{{ notification.config_value }}" data-id="{{ notification.id }}" class="checkbox" type="checkbox" {% if notification.active == true %} checked="checked" {% endif %} data-role="none">
                                {{ notification.notification.description }}
                                {% if notification.type == 4 %}
                                    <select id="config_value" name="config_value">
                                        <option value="0" {% if notification.config_value == 0 %} selected {% endif %}>A</option>
                                        <option value="1" {% if notification.config_value == 0 %} selected {% endif %}>B</option>
                                    </select>
                                {% endif %}
                            </label>
                        </li>
                    {% endfor %}
                    {% endif %}
                </ul>
            </div>
        </div>
    </div>
</main>
{% endblock %}