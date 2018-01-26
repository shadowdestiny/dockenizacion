<ul class="no-li">

    <li><a class="dashboard-menu--balance--link" {% if activeSubnav.myClass == "wallet" %}class="active"{% endif %} href="#">
            {{ language.translate("myAccount_balance", ['balance' :   user_balance ]) }}
        </a></li>


    <li><a {% if activeSubnav.myClass == "games" %}class="active"{% endif %} href="/profile/tickets/games">
            {{ language.translate("myAccount_tickets") }}
        </a></li>

    <li><a {% if activeSubnav.myClass == "transaction" %}class="active"{% endif %} href="/profile/transactions">
            {{ language.translate("myAccount_transactions") }}
        </a></li>

    <li><a {% if activeSubnav.myClass == "account" %}class="active"{% endif %} href="/account">
            {{ language.translate("myAccount_account") }}
        </a></li>
</ul>
