<li class="inter">
	<a class="link" href="javascript:void(0);">{{ currency_symbol }}</span></a>
	
	<div class="sub-inter arrow-box2">
		<h1 class="h3">{{ language.translate('Select Regional Settings') }}</h1>
		<div class="box-currency cl">
			<span class="txt">{{ language.translate('Currency') }}</span> 
			<select id="currency_selection">
                {% for code, name in currencies %}
    				<option value="{{ code }}">{{ name }}</option>
                {% endfor %}
			</select>
		</div>
		{#
		<div class="box-lang cl">
			<span class="txt">{{ language.translate('Language') }}</span>
			<select>
                {% for lang in languages %}
				    <option value="{{ lang.id }}">{{ language.translate(lang.ccode) }}</option>
                {% endfor %}
			</select>
		</div>
		#}
		<a href="#" onclick="globalFunctions.setCurrency($('#currency_selection').val())" class="btn red"><span class="text">{{ language.translate('save')|upper }}</span></a>
	</div>

</li>
<li class="hidden balance">
	<a class="link" href="javascript:void(0);">{{ language.translate('Balance') }}: 50 &euro;</a>
</li>
<li class="cart">
	<a class="link" href="javascript:void(0);"><span class="ico ico-cart"></span> {{ language.translate('Cart') }}</a>
</li>
