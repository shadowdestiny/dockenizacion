<ul class="no-li">
    <li><a {% if activeSubnav.myClass == "account" %}class="active"{% endif %} href="/account">{{ language.translate("myAccount_account") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "games" %}class="active"{% endif %} href="/profile/tickets/games">{{ language.translate("myAccount_tickets") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "wallet" %}class="active"{% endif %} href="/account/wallet">{{ language.translate("myAccount_balance", ['balance' :   user_balance ]) }}<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "transaction" %}class="active"{% endif %} href="/profile/transactions">{{ language.translate("myAccount_transactions") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>

{# Messages features are not included in the first release, so we hide it.
   <li><a {% if activeSubnav.myClass == "messages" %}class="active"{% endif %}href="/account/messages">{{ language.app("Messages") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
#}

    <li><a {% if activeSubnav.myClass == "email" %}class="active"{% endif %} href="/account/email">{{ language.translate("myAccount_email") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "password" %}class="active"{% endif %} href="/account/password">{{ language.translate("myAccount_password") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
</ul>
