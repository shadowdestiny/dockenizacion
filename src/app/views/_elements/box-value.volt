{#<div class="box-value {{ extraClass.boxvalueClass }}">
	{% if currency_symbol_first %}
		<span class="currency first {{ extraClass.currencyClass }}">&euro;</span> 
	{% endif %}
	
	<span class="value {{ extraClass.valueClass }}">51.000.000</span>
	
	{% if not currency_symbol_first %}
		<span class="currency last {{ extraClass.currencyClass }}">&euro;</span> 
	{% endif %}
</div>#}

<div class="box-value yellow">
	{% if currency_symbol_first %}
		<span class="currency first">&euro;</span> 
	{% endif %}
	
	<span class="value">51.000.000</span>
	
	{% if not currency_symbol_first %}
		<span class="currency last">&euro;</span> 
	{% endif %}
</div>