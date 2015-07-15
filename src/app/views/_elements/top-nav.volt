<li class="inter">
	<a class="link" href="javascript:void(0);">{{ language.translate('English') }}</span></a>
	
	<div class="sub-inter arrow-box2">
		<h1 class="h3">{{ language.translate('Select Regional Settings') }}</h1>
		<div class="box-currency cl">
			<span class="txt">{{ language.translate('Currency') }}</span> 
			<select>
				<option value="0">&euro; &nbsp;Euro</option>
				<option value="1">currency 2</option>
				<option value="2">currency 3</option>
			</select>
		</div>
		<div class="box-lang cl">
			<span class="txt">{{ language.translate('Language') }}</span>
			<select>
                {% for lang in languages %}
				    <option value="{{ lang.id }}">{{ language.translate(lang.ccode) }}</option>
                {% endfor %}
			</select>
		</div>
		<a href="javascript:void(0);" class="btn red"><span class="text">{{ language.translate('save')|upper }}</span></a>
	</div>
</li>
<li class="hidden balance">
	<a class="link" href="javascript:void(0);">{{ language.translate('Balance') }}: 50 &euro;</a>
</li>
<li class="cart">
	<a class="link" href="javascript:void(0);"><span class="ico ico-cart"></span> {{ language.translate('Cart') }}</a>
</li>
