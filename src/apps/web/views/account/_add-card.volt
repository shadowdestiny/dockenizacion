<script type="text/javascript">
    window.onload = function() {
        $('#card-number').mask('9999 9999 9999 9999');
    };
</script>

{% if component.where == 'cart' %}
    <hr class="hr yellow">
{% endif %}


    {% if component.where == 'account' %}
        {% if msg %}
            <div class="box success">
                <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg>
                <span class="txt">{{ msg }}</span>
            </div>

            {%  if ga_code is defined %}
                <script src="/w/js/vendor/ganalytics.min.js"> </script>
                <script>
                    ga('set', 'page', '/account/addFunds-success');
                    ga('send', 'pageview');
                </script>
            {% endif %}

        {% endif %}
        {% if errors %}

            <div class="box error">
                <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg>
                <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
            </div>

            {%  if ga_code is defined %}
                <script src="/w/js/vendor/ganalytics.min.js"> </script>
                <script>
                    ga('set', 'page', '/account/addFunds-fail');
                    ga('send', 'pageview');
                </script>
            {% endif %}

        {% endif %}


    {% endif %}

<div class="wrap">
    {% if component.where == 'account' %}
    <div class="cols fix-margin">
        <div class="col6 first">
            {% endif %}
            <h2 class="h2 yellow">{{ language.translate("card_subhead") }}</h2>


            {% if component.where == 'cart' %}
            {% if msg %}
                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg>
                    <span class="txt">{{ msg }}</span>
                </div>
            {% endif %}
            {% if errors %}
                <div class="box error">
                    <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg>
                    <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
                </div>
            {% endif %}

            <div class="cols">
                <div class="col6">
                    {% endif %}


                    <label class="label" for="card-number">
                        {{ language.translate("card_number") }} <span class="asterisk">*</span>
                    </label>
                    {{ credit_card_form.render('card-number', {'class':'input'~form_errors['card-number']}) }}

                    <label class="label" for="card-holder">
                        {{ language.translate("card_name") }} <span class="asterisk">*</span>
                    </label>
                    {{ credit_card_form.render('card-holder', {'class':'input'~form_errors['card-holder']}) }}

                    <div class="cl card-detail">
                        <div class="left margin">
                            <label class="label block" for="expiry-date-month">
                                {{ language.translate("card_date") }} <span class="asterisk">*</span>
                            </label>
                            {{ credit_card_form.render('expiry-date-month', { 'class':'input date'~form_errors['expiry-date-month'], "maxlength":"2", 'style':'width:14%'}) }}
                            {{ credit_card_form.render('expiry-date-year', { 'class':'input date'~form_errors['expiry-date-year'], "maxlength":"2",'style':'width:14%'}) }}
                        </div>
                        <div class="left cvv">
                            <label class="label" for="card-cvv">
                                {{ language.translate("card_cvv") }} <span class="asterisk">*</span>
                                <span class="tooltip" data-type="span" data-place="top" data-event="click" data-message="{{ language.translate('The Card Code Verification is a 3 digit number (Mastercard and Visa) or 4 digit (American Express) that can be located on your card.') }}" data-class="v-question-mark ico" data-ico="v-question-mark"></span>
                            </label>
                            {{ credit_card_form.render('card-cvv', {'class':'input'~form_errors['card-cvv']}) }}
                        </div>
                        {{ credit_card_form.render('csrf', ['value': security.getSessionToken()]) }}

                        {% if emerchant_data is defined %}
                            <input type="hidden" name="thm_session_id" value="{{ emerchant_data['thm_session_id'] }}">
                            <p style="background:url(https://h.online-metrix.net/fp/clear.png?{{ emerchant_data['thm_params']}}&session2={{ emerchant_data['thm_guid'] }}&m=1)"></p>
                            <img src="https://h.online-metrix.net/fp/clear.png?{{ emerchant_data['thm_params'] }}&m=2">
                            <script src="https://h.online-metrix.net/fp/check.js?{{ emerchant_data['thm_params'] }}"></script>
                            <object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?{{ emerchant_data['thm_params'] }}" width="1" height="1" id="thm_fp"> 
                                <param name="movie" value="https://h.online-metrix.net/fp/fp.swf?{{ emerchant_data['thm_params'] }}" />
                            </object>
                            </p>
                        {% endif %}
                    </div>

                    {% if component.where == 'cart' %}
                </div>
                <input type="hidden" name="paywallet" id="paywallet" value=""/>
                <input type="hidden" name="funds" id="funds" value=""/>
                <div class="cl col6">
                    <input id="new-card" type="submit" class="hidden2">
                    <label class="btn submit big green right" for="new-card">
                        {{ language.translate("Pay {total_value}") }}
                    </label>
                </div>
            </div>
        </div>

        {% endif %}

        {% if component.where == 'account' %}
    </div>
    <div class="col6 second">
        <h2 class="h3 yellow margin">{{ language.translate("deposit_subhead") }}</h2>
        <div class="div-balance"><strong class="purple">{{ language.translate("Current Account balance:") }}</strong> <span class="value">{{ user_balance }}</span></div>
        <span class="currency">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'class':'insert input'~form_errors['funds-value']}) }}


        <div class="notes cl">
            <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
            <span class="txt" data-txt='{{ language.translate("Minimum Deposit is ") }}{{ symbol }} {{ site_config.feeLimit }}' >{{ language.translate("Minimum Deposit is ") }} {{ site_config.feeLimit }}</span>
            <span class="txt">{{ language.translate("Currencies are just informative, transactions are charged in Euros.")}}</span>
	<span class="txt">{{ language.translate("This transaction will appear as EuroMillions.com on your bank account statement.")}}</span>
        </div>
        <br>
        <div class="div-balance"><strong class="purple charge" >{{ language.translate("Total Charge:") }}</strong> <span class="value charge"></span><span class="value convert"></span></div>
        <div class="box-wallet overview">

            <label class="label submit btn gray" style="cursor:default">
                {{ language.translate("deposit_deposit_btn") }}
                <input type="submit" class="hidden">
            </label>
        </div>


    </div>
</div>
{% endif %}
<input id="id_payment" name="id_payment" value="{#{{ payment_method.id_payment }}#}" type="hidden"/>
</div>


<p>
<div class="images-payment col4 box-partner">
    <ul class="no-li inline"  style="float:right">
        <li><a href="http://www.visaeurope.com/"><svg class="v-visa vector"><use xlink:href="/w/svg/icon.svg#visa"/></svg></a></li>
        <li><a href="http://www.mastercard.com/eur/"><svg class="v-mastercard vector"><use xlink:href="/w/svg/icon.svg#mastercard"/></svg></a></li>
        <li><a href="https://ssl.comodo.com/"><img src="/w/svg/comodo.png"/> </a></li>
    </ul>
</div>
</p>