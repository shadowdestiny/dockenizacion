{#{% if msg %}
    <div class="box success">
        <span class="ico- ico"></span>
        <span class="txt">{{ msg }}</span>
    </div>
{% endif %}
{% if which_form == 'edit' and errors %}
    <div class="box error">
        <span class="ico-warning ico"></span>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}#}

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
            <label class="label" for="add-card-number">
                {{ language.translate("Card Number") }} <span class="asterisk">*</span>
            </label>
            <input id="add-card-number" name="card-number" class="input" type="text" value="{#{{ payment_method.cardNumber }}#}">
        {% if component.where == 'cart' %}
                </div>
                <div class="col6">
        {% endif %}
        <label class="label" for="add-card-name">
            {{ language.translate("Full Name on Card") }} <span class="asterisk">*</span>
        </label>
        <input id="add-card-name" name="card-holder" class="input" type="text" value="{#{{ payment_method.cardHolderName }}#}">
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
            <select class="select month" name="month">
                <option>01</option>
                <option>02</option>
                <option>03</option>
                <option>04</option>
                <option>05</option>
                <option>06</option>
                <option>07</option>
                <option>08</option>
                <option>09</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
            </select>
            <select class="select year" name="year">
                <option>2014</option>
                <option>2015</option>
                <option>2016</option>
                <option>2017</option>
                <option>2018</option>
                <option>2019</option>
            </select>
        </div>
        <div class="left cvv">
            <label class="label block" for="cvv">
                {{ language.translate("CVV") }} <span class="asterisk">*</span>
                <svg class="ico v-question-mark"><use xlink:href="/w/svg/icon.svg#v-question-mark"></use></svg>
            </label>
            <input id="cvv" name="card-cvv" class="input " type="text">
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
                <div class="balance"><strong class="purple">Wallet balance:</strong> <span class="value">&euro; 20.00</span></div>
                <div class="box-wallet overview">
                    <span class="symbol">&euro;</span><input class="input" type="text" placeholder="Enter any amount">
                    <label class="label btn green">
                        {{ language.translate("Add funds to your wallet") }}
                        <input type="submit" class="hidden">
                    </label>
                </div>
                <div class="notes">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">Fee of &euro; 0.35 will be charged for transfers of small amount</span>
                </div>
            </div>
        </div>
    {% endif %}
</div>


<input id="id_payment" name="id_payment" value="{#{{ payment_method.id_payment }}#}" type="hidden"/>

