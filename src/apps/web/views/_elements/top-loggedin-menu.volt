
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
            <div class="txt account-menu-desktop--link">
                {{ user_name }}
            </div>
            <ul class="account-menu-desktop--menu">
                <li><a href="/account">
                        <span>My Account</span>
                    </a></li>
                <li><a href="/account/wallet">
                        <span>Balance</span>
                    </a></li>
                <li><a href="/profile/tickets/games">
                        <span>My Tickets</span>
                    </a></li>
                <li>
                    <a href="/profile/transactions">
                        <span>My Transactions</span>
                    </a></li>
                <li><a href="/logout">
                        <span>Logout</span>
                    </a></li>
            </ul>
            <a href="/account">
                {{ language.translate('myAccount') }}
            </a>

        </div>
    </div>
</li>