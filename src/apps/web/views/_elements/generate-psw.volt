{{ form('account/password') }}
    {% if msg %}
        <div class="box success">
            <span class="ico- ico"></span> {# Ico- ? shouldn't be something else? #}
            <span class="txt">{{ msg }}</span>
        </div>
    {% endif %}
    {% if which_form == 'password' and errors %}
        <div class="box error">
            <span class="ico-warning ico"></span>
            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
        </div>
    {% endif %}

    {% if myPsw.value == "change" %}
        <div class="cols change">
            <div class="col6">
                <label for="old-password" class="label">{{ language.translate("Old password") }} <span class="asterisk">*</span></label>
                {{ password_change.render('old-password', {'class':'input' }) }}
                {% endif %}

                <label for="new-password" class="label">{{ language.translate("New password") }} <span class="asterisk">*</span></label>
                {{ password_change.render('new-password', {'class':'input' }) }}

                <label for="confirm-password" class="label">{{ language.translate("Confirm password") }} <span class="asterisk">*</span></label>
                {{ password_change.render('confirm-password', {'class':'input' }) }}

                {% if myPsw.value == "change" %}
            </div>
            <div class="col6">
                <div class="box info">
                    <span class="ico ico-info"></span>
                    <span class="txt">
                        {{ language.translate("Use a long password made up of numbers, letters and symbols. The longer your password is, the harder it is to guess. So make your password long to help keep your information safe.") }}
                    </span>
                </div>
            </div>
        </div>
    {% endif %}

    <div class="cl">
        {% if myPsw.value == "change" %}
            <label class="btn big blue left submit" for="submitpass">
                {{ language.translate("Update password") }}
                <input id="submitpass" type="submit" class="hidden2">
            </label>
        {% else %}
            <a href="javascript:void(0);" class="btn blue submit right">{{ language.translate("Request new password") }}</a>
        {% endif %}
    </div>
{{ endform() }}