<div class="top-nav--mobile-account">

    {% if user_logged is not empty %}
        <a href="/account/wallet" class="top-nav--mobile-account--icon"></a>
        <span>{{user_name}}</span>
    {% else %}
        <a href="/sign-in" class="top-nav--mobile-account--icon"></a>
    {% endif %}

    <div class="top-nav--mobile-account--menu">
        <div class="top-nav--mobile-account--menu--inner">

            <div class="top-nav--mobile-account--menu--top">
                <div class="home-block">
                    <a class="home-block--ico ico-block" href="/" class="" title="Go to Homepage"></a>
                    <a class="home-block--link" href="/" class="" title="Go to Homepage">Home</a>
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
                    <h3>Lotteries</h3>
                    <a href="/{{ language.translate("link_euromillions_play") }}">euromillions</a>
                    <a href="/{{ language.translate('link_christmas_play') }}">Spanish christmass Lottery</a>
                    <a href="/{{ language.translate('link_euromillions_results') }}">euromillions result</a>
                </li>
                <li class="li--euromillion">
                    <h3>My EuroMillions</h3>
                    <a href="/account/wallet">Balance</a>
                    <a href="/profile/tickets/games">tickets</a>
                    <a href="/profile/transactions">transactions</a>
                    <a href="/account">my account</a>
                    <a class="link" href="/logout">{{ language.translate('LogOut') }}</a>
                </li>
                <li class="li--help">
                    <a href="/{{ language.translate("link_euromillions_help") }}">How to play</a>
                </li>
                <li class="li--lang">
                    <a href="#">Languages</a>

                    <div class="li--lang--languages">
                        <ul class="no-li">
                            <li class="language--li--current">
                                <a class="link myLang li-language--main-link" href="javascript:void(0);">{{ language.translate(user_language) }}</a>
                            </li>
                            {% for active_language in active_languages %}
                                {% if active_language != user_language %}
                                    <li>
                                        <a href="javascript:globalFunctions.setLanguage('{{ active_language }}');">{{ language.translate(active_language) }}</a>
                                    </li>
                                {% endif %}
                            {% endfor %}
                        </ul>
                    </div>


                </li>
                <li class="li--cur">
                    <a href="/{{ language.translate("link_currency") }}">Currencies</a>
                </li>
            </ul>

        </div>
    </div>

</div>