<form novalidate>

    <div class="box error">
        <span class="ico-warning ico"></span>
        <span class="txt">Error info lorem ipsum</span>
    </div>

    {% if myPsw.value == "change" %}
        <div class="cols change">
            <div class="col6">
                <label for="old-password" class="label">{{ language.translate("Old password") }}</label>
                <input id="old-password" class="input" type="email">
    {% endif %}

    <label for="new-password" class="label">{{ language.translate("New password") }}</label>
    <input id="new-password" class="input" type="email">

    <label for="confirm-password" class="label">{{ language.translate("Confirm password") }}</label>
    <input id="confirm-password" class="input" type="email">

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
            <a href="javascript:void(0);" class="btn blue submit">{{ language.translate("Update password") }}</a>
        {% else %}
            <a href="javascript:void(0);" class="btn blue submit right">{{ language.translate("Request new password") }}</a>
        {% endif %}
    </div>
</form>