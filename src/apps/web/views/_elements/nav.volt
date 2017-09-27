<li class="li-christmas{% if activeNav.myClass == 'christmas' %} active{% endif %}">
    <a href="/christmas-lottery/play">
        <span class="link desktop">{{ language.translate("Christmas Lottery") }}</span>
        <br class="br">
        {% if mobile == 1 %}
            <span class="txt">Play Christmas Lottery</span>
        {% else %}
            <span class="txt">{{ language.translate("Play") }}</span>
        {% endif %}
    </a>
</li>
<li class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a href="/{{ lottery }}/play">
        <span class="link desktop">{{ language.translate("play_sub") }}</span>
        <br class="br">
        {% if mobile == 1 %}
            <span class="txt">Play EuroMillions</span>
        {% else %}
            <span class="txt">{{ language.translate("play") }}</span>
        {% endif %}
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="/{{ lottery }}/results">
        <span class="link desktop">{{ language.translate("results") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("results_sub") }}</span>
			<span class="mobile">Euromillions results</span>
		</span>
    </a>
</li>
<li class="li-your-account{% if activeNav.myClass == 'account' %} active{% endif %}">
    {% if user_logged %}
        {% set link="/account" %}
    {% else %}
        {% set link="/sign-up" %}
    {% endif %}

    <a class="your-account" href="{{ link }}">
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
    <a href="/{{ lottery }}/help">
        <span class="link desktop">{{ language.translate("help_sub") }}</span>
        <br class="br">
        <span class="txt">
			<span class="desktop">{{ language.translate("help") }}</span>
			<span class="mobile">{{ language.translate("help_sub") }}</span>
		</span>
    </a>
</li>
