<li class="li-currency">
    {#<a class="link myCur" href="javascript:void(0);">{{ user_currency['symbol'] }} &nbsp; {{ language.translate(user_currency_code ~ "_name") }}</a>#}
    <a rel="nofollow" class="link myCur" href="javascript:void(0);">{{ language.translate('currencies') }}</a>
    <div class="div-currency">
        <ul class="no-li">
            {% for currency in currencies %}
                {% if currency.code != user_currency_code %}
                    <li><a rel="nofollow" href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">{{ language.translate(currency.code ~ "_code") }}
                            &nbsp; {{ language.translate(currency.code ~ "_name") }}</a></li>
                {% endif %}
            {% endfor %}
            <li><a rel="nofollow" href="/{{ language.translate("link_currency") }}">{{ language.translate('currencies') }}
                    <svg class="ico v-arrow-right3">
                        <use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use>
                    </svg>
                </a></li>
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