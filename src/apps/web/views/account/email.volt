
            <div class="my-account--section my-email">
                <h2 class="">{{ language.translate("email_head") }}</h2>

                {% if message %}
                    <div class="box success">
                        <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                        <div class="txt">{{ language.translate(message) }}</div>
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
                        <div class="email-me">{{ language.translate("email_emailme") }}</div>
                        <ul class="no-li options">
                            {% if list_notifications is empty %}
                                {# EMTD - We shouldn't do something about when notification is empty? Alessio #}
                            {% else %}
                                {% for i,notification in list_notifications %}
                                    {% if notification.notification.notification_type != 2 and notification.notification.notification_type != 3 %}
                                        <li>
                                            <label for="check{{ i }}">
                                                <input id="check{{ i }}" data-type="{{ notification.notification.notification_type }}" name="{{ notification.name }}" data-value="{{ notification.config_value }}" data-id="{{ notification.id }}" class="checkbox" type="checkbox" {% if notification.active == true %} checked="checked" {% endif %} data-role="none">
                                                <span class="txt">{{ language.translate(notification.notification.description) }}</span>
                                            </label>
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
                                        </li>
                                    {% endif %}
                                {% endfor %}
                            {% endif %}
                        </ul>
                    </div>
                    <div class="cl">
                        <label class="btn submit blue right" for="new-card">
                            {{ language.translate("email_save_btn") }}
                            <input id="new-card" type="submit" class="hidden2">
                        </label>
                    </div>
                </form>
            </div>
