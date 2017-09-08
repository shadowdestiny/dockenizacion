<li class="li-language">
	<a class="link myLang" href="javascript:void(0);">{{ language.translate(user_language) }} <svg class="ico v-triangle-down"><use xlink:href="/w/svg/icon.svg#v-triangle-down"></use></svg></a>
	<div class="div-language">
		<ul class="no-li">
            {% for active_language in active_languages %}
                {% if active_language != user_language %}
					<li>
						<a href="javascript:globalFunctions.setLanguage('{{ active_language }}');">{{ language.translate(active_language) }}</a>
					</li>
                {% endif %}
            {% endfor %}
        </ul>
    </div>
</li>
<li class="li-currency">
	<a class="link myCur" href="javascript:void(0);">{{ user_currency['symbol'] }} &nbsp; {{ user_currency['name'] }} <svg class="ico v-triangle-down"><use xlink:href="/w/svg/icon.svg#v-triangle-down"></use></svg></a>
	<div class="div-currency">
		<ul class="no-li">
            {% for currency in currencies %}
                {% if currency.code != user_currency_code %}
					<li><a href="javascript:globalFunctions.setCurrency('{{ currency.code }}');">{{ currency.code }} &nbsp; {{ currency.name }}</a></li>
                {% endif %}
            {% endfor %}
            <li><a href="/currency">{{ language.translate('currencies') }} <svg class="ico v-arrow-right3"><use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use></svg></a></li>
        </ul>
    </div>
</li>

{% if user_balance_raw > 0 %}
	{% set class_balance="" %}
{% else %}
	{% set class_balance="hidden" %}
{% endif %}

<li class="balance {{class_balance }}">
	<a class="link" href="/account/wallet/">{{ language.translate('balance') }}: {{ user_balance }}</a>
</li>
{# EMTD - CART link functionality incompleted and hidden for first release

<li class="li-cart">
	<a class="link" href="/cart"><svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg> {{ language.app('Cart') }}</a>
</li>
#}

{% if user_logged is empty %}
<li class="li-sign">
    <a class="link" href="/sign-in">{{ language.translate('signIn') }}</a>
</li>
{% else %}
<li>
	<a class="link" href="/logout">{{ language.translate('LogOut') }}</a>
</li>
<li class="li-sign">
    <a class="link" href="/account/wallet">{{ language.translate('deposit') }}</a>
</li>

{% endif %}
