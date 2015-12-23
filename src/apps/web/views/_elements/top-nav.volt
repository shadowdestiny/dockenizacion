<li class="li-currency">
	<a class="link" href="javascript:void(0);">{{ user_currency['symbol'] }} &nbsp; {{ user_currency['name'] }} <svg class="ico v-triangle-down"><use xlink:href="/w/svg/icon.svg#v-triangle-down"></use></svg></a>
	<div class="div-currency">
		<ul class="no-li">
            {% for currency in currencies %}
				{% if currency.name != user_currency['name'] %}
					<li><a href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">{{ currency.code }} &nbsp; {{ currency.name }}</a></li>
				{% endif %}
            {% endfor %}
            <li><a href="/currency">{{ language.translate('Show all currencies') }} <svg class="ico v-arrow-right3"><use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use></svg></a></li>
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
<li class="li-cart">
	<a class="link" href="/cart"><svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg> {{ language.translate('Cart') }}</a>
</li>
<li class="li-sign">
    <a class="link" href="/cart">{{ language.translate('Sign up') }}</a>
</li>