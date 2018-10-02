{#<section class="section--card--details">#}

{% if component.where == 'cart' %}
    {#<hr class="hr yellow">#}
{% endif %}
    {% if component.where == 'account' %}
        {% if msg %}
            <div class="box success">
                <svg class="ico v-checkmark">
                    <use xlink:href="/w/svg/icon.svg#v-checkmark"/>
                </svg>
                <span class="txt">{{ msg }}</span>
            </div>
            {% if ga_code is defined %}
                <script src="/w/js/vendor/ganalytics.min.js"></script>
                <script>
                    ga('set', 'page', '/account/addFunds-success');
                    ga('send', 'pageview');
                </script>
            {% endif %}
        {% endif %}
        {% if errors %}
            <div class="box error">
                <svg class="ico v-warning">
                    <use xlink:href="/w/svg/icon.svg#v-warning"/>
                </svg>
                <div class="txt">
                    <ul class="no-li">{% for error in errors %}
                            <li>{{ error }}</li>{% endfor %}</ul>
                </div>
            </div>
            {% if ga_code is defined %}
                <script src="/w/js/vendor/ganalytics.min.js"></script>
                <script>
                    ga('set', 'page', '/account/addFunds-fail');
                    ga('send', 'pageview');
                </script>
            {% endif %}
        {% endif %}
    {% endif %}
<div class="">
    {% if component.where == 'account' %}
    <div class="">
        <div class="">
            {% endif %}
            {% if component.where == 'cart' %}
            {% if msg %}
                <div class="box success">
                    <svg class="ico v-checkmark">
                        <use xlink:href="/w/svg/icon.svg#v-checkmark"/>
                    </svg>
                    <span class="txt">{{ msg }}</span>
                </div>
            {% endif %}
            {% if errors %}
                <div class="box error">
                    <svg class="ico v-warning">
                        <use xlink:href="/w/svg/icon.svg#v-warning"/>
                    </svg>
                    <div class="txt">
                        <ul class="no-li">{% for error in errors %}
                                <li>{{ error }}</li>{% endfor %}</ul>
                    </div>
                </div>
            {% endif %}
        </div>

        {% endif %}

        {% if component.where == 'account' %}
    </div>
    <div class="add-funds-block second" style="margin-top: -65px;">
        <h2 class="h3 yellow margin">{{ language.translate("deposit_subhead") }}</h2>
        <div class="div-balance"><strong class="purple">{{ language.translate("deposit_balance") }}:</strong> <span
                    class="value">{{ user_balance }}</span></div>
        <span class="currency">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'style':'left:45px','class':'insert input'~form_errors['funds-value']}) }}
        <div class="notes cl">
            <svg class="ico v-info">
                <use xlink:href="/w/svg/icon.svg#v-info"></use>
            </svg>
            <span class="txt"
                  data-txt='{{ language.translate("deposit_minimum") }} {{ symbol }} {{ site_config.feeLimit }}'>{{ language.translate("Minimum Deposit is ") }} {{ site_config.feeLimit }}</span>
            <span class="txt">{{ language.translate("deposit_ccyWarning") }}</span>
            <span class="txt">{{ language.translate("deposit_statement") }}</span>
        </div>
        <br>
        <div class="div-balance"><strong class="purple charge">{{ language.translate("deposit_total") }}</strong> <span
                    class="value charge"></span><span class="value convert"></span></div>
        <div class="box-wallet overview">

            <label class="label submit btn gray" style="cursor:pointer">
                {{ language.translate("deposit_deposit_btn") }}
            </label>
        </div>
    </div>
	<div id="loading" class="add-funds-block" style="display:none;">
        	<strong>Cargando...</strong>
    </div>
    <div id="money-matrix" class="add-funds-block" style="display:none;">
    	{% include "cart/moneymatrix_iframe.volt" %}
    </div>
</div>
{% endif %}
<input id="id_payment" name="id_payment" value="{#{{ payment_method.id_payment }}#}" type="hidden"/>

</div>


    {#</div>#}


{#</section>#}