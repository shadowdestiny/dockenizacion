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
    {% if mobile != 1 %}
            <div class="add-funds-block second" style="margin-top: -65px; margin-left: 30px">
                <h2 class="h3 yellow margin" style="font-family: Roboto,sans-serif;margin-bottom: 10px;">{{ language.translate("deposit_subhead")|upper }}</h2>
                <div class="div-balance"><strong class="purple" style="color:#f0c11c;">{{ language.translate("deposit_balance") }}</strong> <span
                            class="value" style="color:#f0c11c;">{{ user_balance }}</span></div>
                 <div style="margin-left:-42px!important;">
                <span class="currency" style="color:#f0c11c;font-size: 30px;">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'style':'left:45px;width: 255px !important;','class':'insert input'~form_errors['funds-value']}) }}
                </div>
                <div class="notes cl">
                <div style="margin-left:-35px!important;">
                    <svg class="ico v-info" style="font-size: 25px;">
                        <use xlink:href="/w/svg/icon.svg#v-info"></use>
                    </svg>
                    <span class="txt"
                          data-txt='{{ language.translate("deposit_minimum") }} {{ symbol }} {{ site_config.feeLimit }}'>&nbsp;{{ language.translate("Minimum Deposit is ") }} {{ site_config.feeLimit }}</span>
                 </div>
                 <div style="margin-left:-25px!important;">
                    <span class="txt">{{ language.translate("deposit_ccyWarning") }}<br>{{ language.translate("deposit_statement") }}</span>
                 </div>
                </div>
                <br>
                <div class="div-balance"><strong class="purple charge" style="color:#f0c11c;">{{ language.translate("deposit_total") }}</strong> <span
                            class="value charge"></span><span class="value convert"></span></div>
                <div class="box-wallet overview">

                    <label class="label submit btn gray" style="cursor:pointer">
                        {{ language.translate("deposit_deposit_btn") }}
                    </label>
                </div>
            </div>
     {% else %}
    <div class="add-funds-block second" style="margin-top: -65px;">
        <h2 class="h3 yellow margin" style="font-family: Roboto,sans-serif;font-size: 20px;margin-bottom: 15px;">{{ language.translate("deposit_subhead")|upper}}</h2>
        <div class="div-balance" style="text-align: left;"><strong class="purple" style="color:#f0c11c;">{{ language.translate("deposit_balance") }}</strong> <span
                    class="value" style="color:#f0c11c;">{{ user_balance }}</span>
        </div>
        <div class="div-balance" style="text-align: left;">
        <span class="currency" style="color:#f0c11c;font-size: 25px;right: 0px;">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'style':'left:30px','class':'insert input'~form_errors['funds-value']}) }}
        </div>
        <div class="notes cl" style="text-align: left;">
            <svg class="ico v-info" style="font-size: 18px;">
                <use xlink:href="/w/svg/icon.svg#v-info"></use>
            </svg>
            <span class="txt"
                  data-txt='{{ language.translate("deposit_minimum") }} {{ symbol }} {{ site_config.feeLimit }}'>{{ language.translate("Minimum Deposit is ") }} {{ site_config.feeLimit }}</span>
            <span class="txt">{{ language.translate("deposit_ccyWarning") }}. {{ language.translate("deposit_statement") }}</span>
        </div>
        <br>
        <div class="div-balance" style="text-align: left;"><strong class="purple charge"  style="color:#f0c11c;">{{ language.translate("deposit_total") }}</strong> <span
                    class="value charge"></span><span class="value convert"></span></div>
        <div class="box-wallet overview">

            <label class="label submit btn gray" style="cursor:pointer">
                {{ language.translate("deposit_deposit_btn") }}
            </label>
        </div>
    </div>
    {% endif %}
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