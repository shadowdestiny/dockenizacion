{% if user_logged is empty %}
    <li class="li-sign">
        <a class="btn-theme btn-secondary" href="/{{ language.translate("signUp") }}">{{ language.translate('signUp') }}</a>
    </li>
    <li class="li-sign">
        {#TODO : paste real text here#}
        <a class="btn-theme btn-primary" href="/{{ language.translate("link_signin") }}">{{ language.translate('signIn') }}</a>
        {#<a class="btn-theme btn-primary" href="/{{ language.translate("link_signin") }}">Login</a>#}
    </li>
{% else %}
    <li>
        <a class="link" href="/logout">{{ language.translate('LogOut') }}</a>
    </li>
    <li class="">
        <a class="link" href="/account/wallet">{{ language.translate('deposit') }}</a>
    </li>

    {% include "_elements/top-loggedin-menu.volt" %}

{% endif %}
