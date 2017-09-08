{{ form('/password/reset') }}
    {% if msg %}
        <div class="box success">
            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
            <span class="txt">{{ msg }}</span>
        </div>
    {% endif %}
    {% if which_form == 'password' and errors %}
        <div class="box error">
            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
        </div>
    {% endif %}

    {% if myPsw.value == "change" %}
        <div class="cols change">
            <div class="col6">
                <label for="old-password" class="label">{{ language.translate("password_old") }} <span class="asterisk">*</span></label>
                {{ password_change.render('old-password', {'class':'input' }) }}
    {% endif %}

    <label for="new-password" class="label">{{ language.translate("password_new") }} <span class="asterisk">*</span></label>
    {{ password_change.render('new-password', {'class':'input' }) }}

    <label for="confirm-password" class="label">{{ language.translate("password_confirm") }} <span class="asterisk">*</span></label>
    {{ password_change.render('confirm-password', {'class':'input' }) }}

    {% if myPsw.value == "change" %}
            </div>
            <div class="col6">
                <div class="box info">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">
                        {{ language.translate("password_advice") }}
                    </span>
                </div>
            </div>
        </div>
    {% endif %}

    <div class="cl">
        {% if myPsw.value == "change" %}
            <label class="btn big blue left submit" for="submitpass">
                {{ language.translate("password_update_btn") }}
                <input id="submitpass" type="submit" class="hidden2">
            </label>
        {% else %}
            <a href="javascript:void(0);" class="btn blue submit right">{{ language.translate("Request new password") }}</a>
        {% endif %}
    </div>
{{ endform() }}
