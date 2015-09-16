<ul class="no-li">
    <li><a {% if activeSubnav.myClass == "account" %}class="active"{% endif %} href="javascript:void(0)">{{ language.translate("My Account") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "games" %}class="active"{% endif %} href="javascript:void(0)">{{ language.translate("My Games") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "wallet" %}class="active"{% endif %} href="javascript:void(0)">{{ language.translate("My Wallet") }} <i class="ico ico-arrow-right"></i></a></li>
    <li><a {% if activeSubnav.myClass == "messages" %}class="active"{% endif %}href="javascript:void(0)">{{ language.translate("Messages") }} <i class="ico ico-arrow-right"></i></a></li>
</ul>