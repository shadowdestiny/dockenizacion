<ul class="no-li">
    <li><a {% if activeSubnav.myClass == "terms" %}class="active"{% endif %} href="/{{ language.translate("link_legal_index") }}">{{ language.translate("legal_terms") }}
            {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
        </a></li>
    <li><a {% if activeSubnav.myClass == "privacy" %}class="active"{% endif %} href="/{{ language.translate("link_legal_privacy") }}">{{ language.translate("legal_privacy") }}
            {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
        </a></li>
    <li><a {% if activeSubnav.myClass == "cookies" %}class="active"{% endif %} href="/{{ language.translate("link_legal_cookies") }}">{{ language.translate("legal_cookies") }}
            {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
        </a></li>

<li><a {% if activeSubnav.myClass == "about" %}class="active"{% endif %} href="/{{ language.translate("link_legal_about") }}">{{ language.translate("legal_about") }}
        {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
    </a></li>
</ul>



