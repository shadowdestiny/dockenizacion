<li class="li-play {% if activeNav.myClass == 'play' %}active{% endif %}">
    <a href="{{ url("play") }}">
        <span class="link desktop">{{ language.translate("Win top prizes") }}</span>
        <br class="br">
        <span class="txt">{{ language.translate("Play Games") }}</span>
    </a>
</li>
<li class="li-numbers {% if activeNav.myClass == 'numbers' %}active{% endif %}">
    <a href="{{ url("numbers") }}">
        <span class="link desktop">{{ language.translate("Winning") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Numbers") }}</span>
			<span class="mobile">{{ language.translate("Results") }}</span>
		</span>
    </a>
</li>
<li class="li-your-account {% if activeNav.myClass == 'account' %}active{% endif %}">
    {% if user_logged %}
        {% set link="javascript:void(0)" %}
    {% else %}
        {% set link="/sign-in" %}
    {% endif %}

    <a class="your-account" href="{{ link }}">
        <span class="link desktop">{{ language.translate("Hello, ") }}{% if user_name %}{{ user_name }}{% else %}{{ language.translate("Sign in") }}{% endif %}</span>
        <br class="br">
        <span class="txt"><span class="ico ico-user"></span>{{ language.translate(" Your Account") }}</span>
    </a>
    {% if user_logged %}
        {# EDTD To remove SUBNAV when not connected as account #}
        <ul class="subnav hidden">
            <li><a href="{{ url("account")}}">{{ language.translate("My Account") }} <span class="ico ico-arrow-right"></span></a></li>
            <li><a href="{{ url("account/games") }}">{{ language.translate("My Games") }} <span class="ico ico-arrow-right"></span></a></li>
            <li><a href="{{ url("account/wallet") }}">{{ language.translate("My Wallet") }} <span class="ico ico-arrow-right"></span></a></li>
            <li><a href="{{ url("account/messages")}}">{{ language.translate("Messages") }} <span class="ico ico-arrow-right"></span></a></li>
            <li><a href="{{ url("account/email")}}">{{ language.translate("Email Settings") }} <span class="ico ico-arrow-right"></span></a></li>
            <li><a href="{{ url("userAccess/logout") }}">{{ language.translate("Sign out") }} <span class="ico ico-exit"></span></a></li>
        </ul>
    {% endif %}

</li>
<li class="li-help {% if activeNav.myClass == 'help' %}active{% endif %}">
    <a href="{{ url("help") }}">
        <span class="link desktop">{{ language.translate("How to play") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Help") }}</span>
			<span class="mobile">{{ language.translate("How to play") }}</span>
		</span>
    </a>
</li>
