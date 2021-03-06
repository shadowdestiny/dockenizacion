<li itemscope itemtype="http://schema.org/Event" class="li-christmas{% if activeNav.myClass == 'christmas' %} active{% endif %}">
    <a itemprop="url" href="/{{ language.translate('link_christmas_play') }}" class="ui-link">
        <span class="link desktop">{{ language.translate("playchris_sub") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("Play") }}</span>
			<span class="mobile">{{ language.translate("mobile_playchris") }}</span>
		</span>
        {#{% if mobile == 1 %}#}
            {#<span class="txt">{{ language.translate("mobile_playchris") }}</span>#}
        {#{% else %}#}
            {#<span class="txt">{{ language.translate("Play") }}</span>#}
        {#{% endif %}#}
    </a>
    <meta itemprop="name" content="{{ language.translate('markup_playchris_name') }}">
    <meta itemprop="startDate" content="2018-12-22">
    <span itemprop="location" itemscope itemtype="http://schema.org/Place">
        <meta itemprop="name" content="{{ language.translate('markup_playchris_place') }}">
        <meta itemprop="address" content="Teatro Real">
    </span>
</li>
<li itemscope itemtype="http://schema.org/Event" class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a itemprop="url" href="/{{ language.translate("link_euromillions_play") }}" class="ui-link">
        <span class="link desktop">{{ language.translate("play_sub") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("play") }}</span>
			<span class="mobile">{{ language.translate("mobile_playem") }}</span>
		</span>
        {#{% if mobile == 1 %}#}
            {#<span class="txt">{{ language.translate("mobile_playem") }}</span>#}
        {#{% else %}#}
            {#<span class="txt">{{ language.translate("play") }}</span>#}
        {#{% endif %}#}
    </a>
    <meta itemprop="name" content="{{ language.translate('markup_playem_name') }}">
    <meta itemprop="startDate" content="{{ nextDrawDateEuromillions }}">
    <span itemprop="location" itemscope itemtype="http://schema.org/Place">
        <meta itemprop="name" content="{{ language.translate('markup_playem_place') }}">
        <meta itemprop="address" content="{{ language.translate('markup_playem_address') }}">
    </span>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="/{{ language.translate('link_euromillions_results') }}">
        <span class="link desktop">{{ language.translate("results") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("results_sub") }}</span>
			<span class="mobile">{{ language.translate("mobile_resultsem") }}</span>
		</span>
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'christmasNumbers' %} active{% endif %}">
    <a href="/{{ language.translate('link_christmas_results') }}">
        <span class="link desktop">{{ language.translate("results_christmas") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("results_sub") }}</span>
			<span class="mobile">{{ language.translate("mobile_resultschristmas") }}</span>
		</span>
    </a>
</li>
<li class="li-your-account{% if activeNav.myClass == 'account' %} active{% endif %}">
    {% if user_logged %}
        {% set link="/account" %}
    {% else %}
        {% set link="/"~language.translate('link_signup') %}
    {% endif %}

    <a class="your-account" href="{{ link }}" rel="nofollow">
        <span class="link desktop username">{% if user_name %}{{ language.translate("myAccount_sub") }}{{ user_name }}{% else %}{{ language.translate("signUp_sub") }}{% endif %}</span>
        <br class="br">
        <span class="txt"><svg class="ico v-user"><use
                        xlink:href="/w/svg/icon.svg#v-user"></use></svg>{% if user_name %}{{ language.translate("Your Account") }}{% else %}{{ language.translate("signUp") }}{% endif %} </span>
    </a>
    {% if user_logged %}
        {# EDTD To remove SUBNAV when not connected as account #}
        <ul class="subnav hidden">
            <li><a href="/account">{{ language.translate("myAccount_account") }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            <li><a href="/profile/tickets/games">{{ language.translate("myAccount_tickets") }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            <?php $var = array('balance' => $user_balance);?>
            <li><a href="/account/wallet">{{ language.translate("myAccount_balance", ['balance' :   user_balance ]) }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            <li><a href="/profile/transactions">{{ language.translate("myAccount_transactions") }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            {#
                        <li><a href="/account/messages">{{ language.app("Messages") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></a></li>
            #}
            <li><a href="/account/email">{{ language.translate("myAccount_email") }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            <li><a href="/account/password">{{ language.translate("myAccount_password") }}
                    <svg class="ico v-arrow-right">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right"></use>
                    </svg>
                </a></li>
            <li><a href="/logout">{{ language.translate("myAccount_signOut") }}
                    <svg class="ico v-exit">
                        <use xlink:href="/w/svg/icon.svg#v-exit"></use>
                    </svg>
                </a></li>
        </ul>
    {% endif %}
</li>
<li class="li-help{% if activeNav.myClass == 'help' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_help") }}">
        <span class="link desktop">{{ language.translate("help_sub") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("help") }}</span>
			<span class="mobile">{{ language.translate("help_sub") }}</span>
		</span>
    </a>
</li>
