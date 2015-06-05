<span class="box-value{% if extraClass.boxvalueClass %} {{ extraClass.boxvalueClass }}{% endif %}">
	{% if currency_symbol_first %}
		<span class="currency first{% if extraClass.currencyClass %} {{ extraClass.currencyClass }}{% endif %}">&euro;</span> 
	{% endif %}
	
	<span class="value{% if extraClass.valueClass %} {{ extraClass.valueClass }}{% endif %}">51.000.000</span>
	
	{% if not currency_symbol_first %}
		<span class="currency last{% if extraClass.currencyClass %} {{ extraClass.currencyClass }}{% endif %}">&euro;</span> 
	{% endif %}
</span>