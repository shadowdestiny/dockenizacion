<span class="box-value{% if extraClass.boxvalueClass %} {{ extraClass.boxvalueClass }}{% endif %}">
	{% if currency_symbol_first %}
		<span class="currency first{% if extraClass.currencyClass %} {{ extraClass.currencyClass }}{% endif %}"></span>
	{% endif %}
	
	<span class="maxChar value{% if extraClass.valueClass %} {{ extraClass.valueClass }}{% endif %}">
		{% if jackpot_value != '1500000000' %}{{ jackpot_value }}{% endif %}
	</span>
	
	{% if not currency_symbol_first %}
		<span class="currency last{% if extraClass.currencyClass %} {{ extraClass.currencyClass }}{% endif %}">{{ user_currency['symbol'] }}</span>
	{% endif %}
</span>