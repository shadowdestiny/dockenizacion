{% block template_scripts %}
<script>

$(function(){
    $('#funds-value').on('keyup',function(e) {
        ///^\D+(\.\D\D?)?$/
        var regex = /^\d+(\.\d{0,2})?$/g;
        if (!regex.test(this.value)) {
            this.value = '';
        }
    });

    $('#funds-value').on('blur', function(e) {
        alert('alert');
        $(this).val($(this).val().toFixed(2));
    });
})

</script>

{% endblock %}


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

    {% if component.where == 'cart' %} 
        <div class="h1 res info">
            Total to be paid &euro; 12.10
        </div>
    {% endif %}

    <h2 class="h3 yellow">{{ language.translate("Enter your credit card details") }}</h2>

    <div class="card-info">
        {% if component.where == 'cart' %}
            <div class="cols">
                <div class="col6">
        {% endif %}
        <label class="label" for="add-card-number">
            {{ language.translate("Card Number") }} <span class="asterisk">*</span>
        </label>
        {{ credit_card_form.render('card-number', {'class':'input'~form_errors['card-number'], "placeholder":"0000000000000000"}) }}

        {% if component.where == 'cart' %}
                </div>
                <div class="col6">
        {% endif %}
        <label class="label" for="add-card-name">
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

    <div class="col6 cl card-detail">
        <div class="left margin">
            <label class="label block">
                {{ language.translate("Expiration date") }} <span class="asterisk">*</span>
            </label>

        {{ credit_card_form.render('expiry-date', {'class':''~form_errors['expiry-date']}) }}


        </div>
        <div class="left cvv">
            <label class="label block" for="cvv">
                {{ language.translate("CVV") }} <span class="asterisk">*</span>
                <svg class="ico v-question-mark"><use xlink:href="/w/svg/icon.svg#v-question-mark"></use></svg>
            </label>
            {{ credit_card_form.render('card-cvv', {'class':'input'~form_errors['card-cvv']}) }}
        </div>
    </div>

    {% if component.where == 'cart' %}
            <div class="cl col6">
                <label class="btn submit big green right" for="new-card">
                    {{ language.translate(" Pay &amp; Play your numbers") }}
                    <input id="new-card" type="submit" class="hidden2">
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
                    <span class="currency">&euro;</span>{{ credit_card_form.render('funds-value', {'class':'insert input'~form_errors['funds-value']}) }}
                    <label class="label btn green">
                        {{ language.translate("Add funds to your wallet") }}
                        <input type="submit" class="hidden">
                    </label>
                </div>
                <div class="notes">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">{{ language.translate("Fee of")}} {{  fee }} {{ language.translate("will be charged for transfers less than ") }} {{ fee_to_limit }}</span>
                </div>
            </div>
        </div>
    {% endif %}
</div>


<input id="id_payment" name="id_payment" value="{#{{ payment_method.id_payment }}#}" type="hidden"/>

