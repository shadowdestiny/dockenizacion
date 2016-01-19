<li class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a href="/play">
        <span class="link desktop">{{ language.translate("Win top prizes") }}</span>
        <br class="br">
        <span class="txt">{{ language.translate("Play Games") }}</span>
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="/numbers">
        <span class="link desktop">{{ language.translate("Winning") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Numbers") }}</span>
			<span class="mobile">{{ language.translate("Results") }}</span>
		</span>
    </a>
</li>
<li class="li-your-account{% if activeNav.myClass == 'account' %} active{% endif %}">
    {% if user_logged %}
        {% set link="javascript:void(0)" %}
    {% else %}
        {% set link="/sign-in" %}
    {% endif %}

    <a class="your-account" href="{{ link }}">
        <span class="link desktop username">{{ language.translate("Hello. ") }}{% if user_name %}{{ user_name }}{% else %}{{ language.translate("Log in") }}{% endif %}</span>
        <br class="br">
        <span class="txt"><svg class="ico v-user"><use xlink:href="/w/svg/icon.svg#v-user"></use></svg>{{ language.translate(" Your Account") }}</span>
    </a>
    {% if user_logged %}
        {# EDTD To remove SUBNAV when not connected as account #}
        <ul class="subnav hidden">
            <li><a href="/account">{{ language.translate("Account") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            <li><a href="/account/games">{{ language.translate("Games") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            <li><a href="/account/wallet">{{ language.translate("Wallet") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            <li><a href="/account/transaction">{{ language.translate("Transaction") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
{#
            <li><a href="/account/messages">{{ language.translate("Messages") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
#}
            <li><a href="/account/email">{{ language.translate("Email Settings") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            <li><a href="/account/password">{{ language.translate("Change Password") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            <li><a href="/userAccess/logout">{{ language.translate("Sign out") }} <svg class="ico v-exit"><use xlink:href="/w/svg/icon.svg#v-exit"></use></svg></a></li>
        </ul>
    {% endif %}
</li>
<li class="li-help{% if activeNav.myClass == 'help' %} active{% endif %}">
    <a href="/help">
        <span class="link desktop">{{ language.translate("How to play") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Help") }}</span>
			<span class="mobile">{{ language.translate("How to play") }}</span>
		</span>
    </a>
</li>
