<li class="li-currency">
	<a class="link" href="javascript:void(0);">{{ user_currency['symbol'] }} {{ user_currency['name'] }}</a>
	<div class="div-currency">
		<ul class="no-li">
            {% for currency in currencies %}
				<li><a href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">EUR &nbsp; {{ currency.name }}</a></li>
            {% endfor %}
            <li><a href="/currency">Show more</a></li>
        </ul>
    </div>
</li>

{% if user_balance_raw > 0 %}
	{% set class_balance="" %}
{% else %}
	{% set class_balance="hidden" %}
{% endif %}

<li class="{{class_balance }} balance">
	<a class="link" href="javascript:void(0);">{{ language.translate('Balance') }}: {{ user_balance }}</a>
</li>
<li class="cart">
	<a class="link" href="/cart"><svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg> {{ language.translate('Cart') }}</a>
</li>
