<ul class="no-li">
    <li><a {% if activeSubnav.myClass == "account" %}class="active"{% endif %} href="/account">{{ language.translate("Account") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "games" %}class="active"{% endif %} href="/account/games">{{ language.translate("Games") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "wallet" %}class="active"{% endif %} href="/account/wallet">{{ language.translate("Wallet") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></i></a></li>
    <li><a {% if activeSubnav.myClass == "transaction" %}class="active"{% endif %} href="/account/transaction">{{ language.translate("Transaction") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>

{# Messages features are not included in the first release, so we hide it.
   <li><a {% if activeSubnav.myClass == "messages" %}class="active"{% endif %}href="/account/messages">{{ language.translate("Messages") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
#}

    <li><a {% if activeSubnav.myClass == "email" %}class="active"{% endif %}href="/account/email">{{ language.translate("Email Settings") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
    <li><a {% if activeSubnav.myClass == "password" %}class="active"{% endif %}href="/account/password">{{ language.translate("Change password") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
</ul>