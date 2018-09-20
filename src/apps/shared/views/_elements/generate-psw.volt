{{ form('/account/reset') }}
    {% if msg_pwd %}
        <div class="box success">
            <svg class="ico v-checkmark">
                <use xlink:href="/w/svg/icon.svg#v-checkmark"></use>
            </svg>
            <span class="txt">{{ msg_pwd }}</span>
        </div>
    {% endif %}
    {% if which_form == 'password' and errors_pwd %}
        <div class="box error">
            <svg class="ico v-warning">
                <use xlink:href="/w/svg/icon.svg#v-warning"></use>
            </svg>
            <span class="txt">{% for error in errors_pwd %}{{ error }}<br>{% endfor %}</span>
        </div>
    {% endif %}



<table>

    {% if myPsw.value == "change" %}
        <tr>
            <td class="td-label">
                <p>
                    <label for="old-password" class="label">{{ language.translate("password_old") }} <span class="asterisk">*</span></label>
                </p>
            </td>
            <td class="td-input">
                <p>
                    {{ password_change.render('old-password', {'class':'input' }) }}
                </p>
            </td>
        </tr>
    {% endif %}
    <tr>
        <td class="td-label">
            <p>
                <label for="new-password" class="label">{{ language.translate("password_new") }} <span class="asterisk">*</span></label>
            </p>
        </td>
        <td class="td-input">
            <p>
                {{ password_change.render('new-password', {'class':'input' }) }}
            </p>
        </td>
    </tr>

    <tr>
        <td class="td-label">
            <p>
                <label for="confirm-password" class="label">{{ language.translate("password_confirm") }} <span
                            class="asterisk">*</span></label>
            </p>
        </td>
        <td class="td-input">
            <p>
                {{ password_change.render('confirm-password', {'class':'input' }) }}
            </p>
        </td>
    </tr>
</table>


{#{% if myPsw.value == "change" %}#}
            {#</div>#}
            {#<div class="col6">#}
                {#<div class="box info">#}
                    {#<svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>#}
                    {#<span class="txt">#}
                        {#{{ language.translate("password_advice") }}#}
                    {#</span>#}
                {#</div>#}
            {#</div>#}
        {#</div>#}
    {#{% endif %}#}

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
