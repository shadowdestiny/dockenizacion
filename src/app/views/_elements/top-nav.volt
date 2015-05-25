<li class="inter">
	<a class="link" href="javascript:void(0);">Euro / English <span class="ico ico-pull-down mhide"></span></a>
	
	<div class="sub-inter arrow-box2">
		<h1 class="h3">{{ language.translate('Select Regional Settings') }} </h1>
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
<li class="help">
	<a class="link" href="javascript:void(0);">{{ language.translate('Help') }} <span class="ico ico-pull-down mhide"></span></a>
	<ul class="sub-help arrow-box">
		<li><a href="javascript:void(0);">{{ language.translate('How to Play') }}</a></li>
		<li><a href="javascript:void(0);">{{ language.translate('Frequently Asked Questions') }}</a></li>
		<li><a href="javascript:void(0);">{{ language.translate('Player Protection') }}</a></li>
		<li><a href="javascript:void(0);">{{ language.translate('Contact us') }}></a></li>
	</ul>
</li>
