<div class="top-nav--mobile-account">

    {% if user_logged is not empty %}
        <a href="/account/wallet" class="top-nav--mobile-account--icon"></a>
        <span>{{user_name}}</span>
    {% else %}
        <a rel="nofollow" href="/{{ language.translate("link_signin") }}" class="top-nav--mobile-account--icon"></a>
    {% endif %}

    <div class="top-nav--mobile-account--menu">
        <div class="top-nav--mobile-account--menu--inner">

            <div class="top-nav--mobile-account--menu--top">
                <div class="home-block">
                    <a class="home-block--ico ico-block" href="/{{ language.translate("link_home") }}" class="" title="Go to Homepage"></a>
                    <a class="home-block--link" href="/{{ language.translate("link_home") }}" class="" title="Go to Homepage">{{ language.translate("home_breadcrumb") }}</a>
                </div>
                {#<div class="cart-block">#}
                    {#<div class="cart-block--ico ico-block"></div>#}
                    {#<div class="cart-block--total">#}
                        {#€24.00#}
                    {#</div>#}
                    {#<a href="#" class="cart-block--link">#}
                        {#Shopping Chart#}
                    {#</a>#}
                {#</div>#}
            </div>
            <div class="top-nav--mobile-account--menu--close">
            </div>
            <ul class="top-nav--mobile-account--menu--list">
                <li class="li--lottery">
                    <strong>
                    {{ language.translate("play_dropdown") }}<br />
                    </strong>
                    <a href="/{{ language.translate("link_euromillions_play") }}">{{ language.translate("playeuromillions") }}</a>
                    <a href="/{{ language.translate("link_powerball_play") }}">{{ language.translate("playpowerball") }}</a>
                    <a href="/{{ language.translate("link_megamillions_play") }}">{{ language.translate("playmegamillions") }}</a>
                    <a href="/{{ language.translate('link_eurojackpot_play') }}">{{ language.translate("playeurojackpot") }}</a>
                    <a href="/{{ language.translate('link_christmas_play') }}">{{ language.translate("playchris_sub") }}</a>
                    <a href="/{{ language.translate('link_euromillions_help') }}">{{ language.translate("howto_em_sub") }}</a>
                    <a href="/{{ language.translate('link_powerball_howto') }}">{{ language.translate("howto_pow_sub") }}</a>
                </li>
                <li>
                    <strong>
                    {{ language.translate("results_dropdown") }}<br />
                    </strong>
                    <a href="/{{ language.translate('link_euromillions_results') }}">{{ language.translate("results_em_sub") }}</a>
                    <a href="/{{ language.translate('link_powerball_results') }}">{{ language.translate("results_pow_sub") }}</a>
                    <a href="/{{ language.translate('link_megam_results') }}">{{ language.translate("results_megam_sub") }}</a>
                    <a href="/{{ language.translate('link_eurojackpot_results') }}">{{ language.translate("results_eurojackpot_sub") }}</a>
                    <a href="/{{ language.translate('link_christmas_results') }}">{{ language.translate("results_chris_sub") }}</a>
                </li>
                <li>
                    <a href="/{{ language.translate('link_blogindex') }}">{{ language.translate("blogindex") }}</a>
                </li>
                {% if user_logged is not empty %}
                <li class="li--euromillion">
                    <strong>
                        {{ language.translate("myAccount_account") }}
                    </strong>
                    <a href="/account/wallet">{{ language.translate("myAccount_balance") }}</a>
                    <a href="/profile/tickets/games">{{ language.translate("myAccount_tickets") }}</a>
                    <a href="/profile/transactions">{{ language.translate("myAccount_transactions") }}</a>
                    <a href="/account">{{ language.translate("myAccount") }}</a>
                    <a class="link" href="/logout">{{ language.translate("LogOut") }}</a>
                </li>
                {% else %}
                    <li>
                        <a rel="nofollow" href="/{{ language.translate("link_signin") }}">{{ language.translate('signIn') }}</a>
                        <a rel="nofollow" href="/{{ language.translate("link_signup") }}">{{ language.translate('signUp') }}</a>
                    </li>
                {% endif %}
                <li class="li--lang">
                    <a href="#">{{ language.translate(user_language) }}</a>

                    <div class="li--lang--languages">
                        <ul class="no-li">
                            {#<li class="language--li--current">#}
                                {#<a class="link myLang li-language--main-link" href="javascript:void(0);"></a>#}
                            {#</li>#}
                            {% for active_language in active_languages %}
                                {% if active_language != user_language %}
                                    <li>
                                        <a href="javascript:globalFunctions.setLanguage('{{ active_language }},{% if pageController is defined %}{{ pageController }}{% endif %}');">{{ language.translate(active_language) }}</a>
                                    </li>
                                {% endif %}
                            {% endfor %}
                        </ul>
                    </div>


                </li>
                <li class="li--cur">
                    <a href="#">{{ user_currency['symbol'] }} {{ user_currency_code }}</a>
                    <div class="li--cur--currencies">
                        <ul class="no-li">
                            {% for currency in currencies %}
                                {% if currency.code != user_currency_code %}
                                    <li>
                                        <a rel="nofollow" href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">
                                            {{ language.translate(currency.code ~ "_code") }} {{ language.translate(currency.code ~ "_name") }}
                                        </a>
                                    </li>
                                {% endif %}
                            {% endfor %}
                            <li>
                                <a rel="nofollow" href="/{{ language.translate("link_currency") }}">{{ language.translate("currencies") }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
    </div>

</div>