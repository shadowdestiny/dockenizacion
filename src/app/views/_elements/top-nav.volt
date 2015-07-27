<li class="li-currency">
	<a class="link" href="javascript:void(0);">{{ user_currency['symbol'] }} {{ user_currency['name'] }}</a>

	<div class="div-currency">
		<ul class="no-li">
            {% for currency in currencies %}
				<li><a href="javascript:globalFunctions.setCurrency('{{ currency['code'] }}');">{{ currency['symbol'] }} {{ currency['name'] }}</a></li>
            {% endfor %}
        </ul>
	</div>

</li>
<li class="hidden balance">
	<a class="link" href="javascript:void(0);">{{ language.translate('Balance') }}: 50 &euro;</a>
</li>
<li class="cart">
	<a class="link" href="javascript:void(0);"><span class="ico ico-cart"></span> {{ language.translate('Cart') }}</a>
</li>
