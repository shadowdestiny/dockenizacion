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

<div class="wrap">
    {% if component.where == 'account' %} 
        <div class="cols fix-margin">
            <div class="col6 first">
    {% endif %}
    <h2 class="h3 yellow">{{ language.translate("Enter your credit card details") }}</h2>

    <div class="card-info">
        {% if component.where == 'cart' %}
            <div class="cols">
                <div class="col6">
        {% endif %}
        <label class="label" for="card-number">
            {{ language.translate("Card Number") }} <span class="asterisk">*</span>
        </label>
        {{ credit_card_form.render('card-number', {'class':'input'~form_errors['card-number'], "placeholder":"0000000000000000"}) }}

        {% if component.where == 'cart' %}
                </div>
                <div class="col6">
        {% endif %}
        <label class="label" for="card-holder">
            {{ language.translate("Full Name on Card") }} <span class="asterisk">*</span>
        </label>
        {{ credit_card_form.render('card-holder', {'class':'input'~form_errors['card-holder'], "placeholder":"Antonio García Carrión"}) }}
        
        {% if component.where == 'cart' %}
                </div>
            </div>
        {% endif %}
    </div>

    {% if component.where == 'cart' %}
        <div class="cols">
    {% endif %}

    <div class="{% if component.where == 'cart' %}col6 {% endif %}cl card-detail">
        <div class="left margin">
            <label class="label block" for="expiry-date">
                {{ language.translate("Expiration date") }} <span class="asterisk">*</span>
            </label>
            {{ credit_card_form.render('expiry-date', { 'class':'input date'~form_errors['expiry-date'], "maxlength":"7"}) }}
        </div>
        <div class="left cvv">
            <label class="label" for="card-cvv">
                {{ language.translate("CVV") }} <span class="asterisk">*</span>
                <svg class="ico v-question-mark"><use xlink:href="/w/svg/icon.svg#v-question-mark"></use></svg>
            </label>
            {{ credit_card_form.render('card-cvv', {'class':'input'~form_errors['card-cvv'], "placeholder":"000"}) }}
        </div>
        {{ credit_card_form.render('csrf', ['value': security.getSessionToken()]) }}
    </div>

    {% if component.where == 'cart' %}
            <div class="cl col6">
                <input id="new-card" type="submit" class="hidden2">
                <label class="btn submit big green right" for="new-card">
                    {{ language.translate("Pay {total_value}") }}
                </label>
            </div>
        </div>
    {% endif %}

    {% if component.where == 'account' %}
            </div>
            <div class="col6 second">
                <h2 class="h3 yellow">{{ language.translate("Add funds to your wallet") }}</h2>
                <div class="div-balance"><strong class="purple">{{ language.translate("Current Wallet balance:") }}</strong> <span class="value">{{ user_balance }}</span></div>

                <div class="box-wallet overview">
                    <span class="currency">{{ symbol }}</span> {{ credit_card_form.render('funds-value', {'class':'insert input'~form_errors['funds-value']}) }}
                    <label class="label btn green">
                        {{ language.translate("Add funds to your wallet") }}
                        <input type="submit" class="hidden">
                    </label>
                </div>

                <div class="notes cl">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">{{ language.translate("Fee of")}} {{  fee }} {{ language.translate("will be charged for transfers less than ") }} {{ fee_to_limit }}</span>
                    <span class="txt">{{ language.translate("Currencies are just informative, transactions are charged in Euros.")}}</span>
                </div>

            </div>
        </div>
    {% endif %}
</div>


<input id="id_payment" name="id_payment" value="{#{{ payment_method.id_payment }}#}" type="hidden"/>

