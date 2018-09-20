
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
                {{ user_balance }}
            </div>
            <a href="/account/wallet">
                {{ language.translate("deposit") }}
            </a>
        </div>
        <div class="right">
            <div class="txt account-menu-desktop--link">
                {{ user_name }}
            </div>
            <a href="/account">
                {{ language.translate('myAccount') }}
            </a>

            <ul class="account-menu-desktop--menu">
                <li><a href="/account">
                        <span>{{ language.translate("myAccount") }}</span>
                    </a></li>
                <li><a href="/account/wallet">
                        <span>{{ language.translate("myAccount_balance") }}</span>
                    </a></li>
                <li><a href="/profile/tickets/games">
                        <span>{{ language.translate("myAccount_tickets") }}</span>
                    </a></li>
                <li>
                    <a href="/profile/transactions">
                        <span>{{ language.translate("myAccount_transactions") }}</span>
                    </a></li>
                <li><a href="/logout">
                        <span>{{ language.translate("LogOut") }}</span>
                    </a></li>
            </ul>

        </div>
    </div>
</li>