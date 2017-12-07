
{#Old links#}

{#<li>#}
    {#<a class="link" href="/logout">{{ language.translate('LogOut') }}</a>#}
{#</li>#}
{#<li class="">#}
    {#<a class="link" href="/account/wallet">{{ language.translate('deposit') }}</a>#}
{#</li>#}


<li class="top-loggedin-menu">

    <div class="top-loggedin-menu--inner">


        <div class="left">
            <div class="txt">
                {{ language.translate('balance') }}: {{ user_balance }}
            </div>
            <a href="/account/wallet">
                {{ language.translate('addBalance') }}
            </a>
        </div>
        <div class="right">
            <div class="txt">
                {{ user_name }}
            </div>
            <a href="/account">
                {{ language.translate('myAccount') }}
            </a>
        </div>

    </div>
</li>