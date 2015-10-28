<ul class="no-li">
    <li><a {% if activeSubnav.myClass == "account" %}class="active"{% endif %} href="/account">{{ language.translate("Account") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "games" %}class="active"{% endif %} href="/account/games">{{ language.translate("Games") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "wallet" %}class="active"{% endif %} href="/account/wallet">{{ language.translate("Wallet") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "messages" %}class="active"{% endif %}href="/account/messages">{{ language.translate("Messages") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "email" %}class="active"{% endif %}href="account/email">{{ language.translate("Email Settings") }} <i class="ico ico-arrow-right"></i></a></li>
</ul>