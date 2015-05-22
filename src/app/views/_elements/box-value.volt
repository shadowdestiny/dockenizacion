{#<div class="box-value {{ extraClass.boxvalueClass }}">
	{% if currency_symbol_first %}
		<span class="currency first {{ extraClass.currencyClass }}">&euro;</span> 
	{% endif %}
	
	<span class="value {{ extraClass.valueClass }}">51.000.000</span>
	
	{% if not currency_symbol_first %}
		<span class="currency last {{ extraClass.currencyClass }}">&euro;</span> 
	{% endif %}
</div>#}