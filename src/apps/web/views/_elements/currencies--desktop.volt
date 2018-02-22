<li class="li-currency" id="li-currency--desktop">
    <a rel="nofollow" class="link myCur li-currency--main-link" href="javascript:void(0);">
        <span class="currency--symbol">{% if user_currency['symbol'] != user_currency_code %}{{ user_currency['symbol'] }}{% endif %}</span>
        &nbsp {{ user_currency_code }}
        {#<svg class="ico v-triangle-down">#}
        {#<use xlink:href="/w/svg/icon.svg#v-triangle-down"></use>#}
        {#</svg>#}
    </a>
    <div class="div-currency">
        <div class="div-currency--shade"></div>
        <div class="div-currency--content">
            <ul class="no-li">
                {% for currency in currencies %}
                    {% if currency.code != user_currency_code %}
                        <li class="currency--li">
                            <a rel="nofollow" href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">
                            <span class="currency--li--code">
                                {{ language.translate(currency.code ~ "_code") }}
                            </span>
                            <span class="currency--li--name">
                                {{ language.translate(currency.code ~ "_name") }}
                            </span>
                            </a>
                        </li>
                    {% else %}
                        <li class="currency--li--current">
                            <a rel="nofollow" href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">
                            <span class="currency--li--code">
                                {{ language.translate(currency.code ~ "_code") }}
                            </span>
                            <span class="currency--li--name">
                                {{ language.translate(currency.code ~ "_name") }}
                            </span>
                            </a>
                        </li>
                    {% endif %}
                {% endfor %}
                <li class="currency--li--show-all">
                    <a rel="nofollow" href="/{{ language.translate("link_currency") }}">

                        {{ language.translate('currencies') }}
                        {#show All currencies#}

                        {#<svg class="ico v-arrow-right3">#}
                        {#<use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use>#}
                        {#</svg>#}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</li>

{# EMTD - CART link functionality incompleted and hidden for first release

<li class="li-cart">
	<a class="link" href="/cart"><svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg> {{ language.app('Cart') }}</a>
</li>
#}