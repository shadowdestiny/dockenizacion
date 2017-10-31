<li class="li-currency" id="li-currency--desktop">
    <a class="link myCur li-currency--main-link" href="javascript:void(0);">
        <span class="currency--symbol">{{ user_currency['symbol'] }}</span> &nbsp; {{ language.translate(user_currency_code ~ "_name") }}
        {#<svg class="ico v-triangle-down">#}
            {#<use xlink:href="/w/svg/icon.svg#v-triangle-down"></use>#}
        {#</svg>#}
    </a>
    <div class="div-currency">
        <ul class="no-li">
            {% for currency in currencies %}
                {% if currency.code != user_currency_code %}
                    <li class="currency--li">
                        <a href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">{{ language.translate(currency.code ~ "_code") }}
                            &nbsp; {{ language.translate(currency.code ~ "_name") }}
                        </a>
                    </li>
                {% else %}
                    <li class="currency--li--current">
                        <a href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">{{ language.translate(currency.code ~ "_code") }}
                            &nbsp; {{ language.translate(currency.code ~ "_name") }}
                        </a>
                    </li>
                {% endif %}
            {% endfor %}
            <li class="currency--show-all">
                <a href="/{{ language.translate("link_currency") }}">

                    {#TODO : Add real variables here#}
                    {#{{ language.translate('currencies') }}#}
                    show All currencies

                    {#<svg class="ico v-arrow-right3">#}
                        {#<use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use>#}
                    {#</svg>#}
                </a>
            </li>
        </ul>
    </div>
</li>

{% if user_balance_raw > 0 %}
    {% set class_balance="" %}
{% else %}
    {% set class_balance="hidden" %}
{% endif %}

<li class="balance {{ class_balance }}">
    <a class="link" href="/account/wallet/">{{ language.translate('balance') }}: {{ user_balance }}</a>
</li>

{# EMTD - CART link functionality incompleted and hidden for first release

<li class="li-cart">
	<a class="link" href="/cart"><svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg> {{ language.app('Cart') }}</a>
</li>
#}