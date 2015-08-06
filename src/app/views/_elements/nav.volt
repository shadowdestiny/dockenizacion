<li class="li-play">
    <a href="javascript:void(0);">
        <span class="link desktop">{{ language.translate("Win top prizes") }}</span>
        <br class="br">
        <span class="txt">{{ language.translate("Play Games") }}</span>
    </a>
</li>
<li class="li-numbers">
    <a href="javascript:void(0);">
        <span class="link desktop">{{ language.translate("Winning") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Numbers") }}</span>
			<span class="mobile">{{ language.translate("Results") }}</span>
		</span>
    </a>
</li>
<li class="li-your-account">
    {% if user_logged %}
        {% set link="javascript:void(0)" %}
    {% else %}
        {% set link="/sign-in" %}
    {% endif %}

    <a class="your-account" href="{{ link }}">
        <span class="link desktop">{{ language.translate("Hello, ") }}{% if user_name %}{{ user_name }}{% else %}{{ language.translate("Hello, Sign in") }}{% endif %}</span>
        <br class="br">
        <span class="txt"><span class="ico ico-user"></span>{{ language.translate(" Your Account") }}</span>
    </a>
    {% if user_logged %}
        {# EDTD To remove SUBNAV when not connected as account #}
        <ul class="subnav hidden">
            <li><a href="javascript:void(0);">{{ language.translate("My Account") }} <span class="ico ico-arrow-right"></span></a>
            </li>
            <li><a href="javascript:void(0);">{{ language.translate("My Games") }} <span
                            class="ico ico-arrow-right"></span></a></li>
            <li><a href="javascript:void(0);">{{ language.translate("My Wallet") }} <span
                            class="ico ico-arrow-right"></span></a></li>
            <li><a href="javascript:void(0);">{{ language.translate("Messages") }} <span
                            class="ico ico-arrow-right"></span></a></li>
            <li><a href="javascript:void(0);">{{ language.translate("Sign out") }} <span class="ico ico-exit"></span></a></li>
        </ul>
    {% endif %}

</li>
<li class="li-help">
    <a href="javascript:void(0);">
        <span class="link desktop">{{ language.translate("How to play") }}</span>
        <br class="br">
		<span class="txt">
			<span class="desktop">{{ language.translate("Help") }}</span>
			<span class="mobile">{{ language.translate("How to play") }}</span>
		</span>
    </a>
</li>
