<h2 class="h3 yellow">{{ language.translate("Your card") }}</h2>

{% if msg %}
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
{% endif %}

<div class="wrap">
    <div class="cols">
        <div class="col6 first">
            <div class="card-info">
                <label class="label" for="add-card-number">
                    {{ language.translate("Card Number") }} <span class="asterisk">*</span>
                </label>
                <input id="add-card-number" name="card-number" class="input" type="text" value="{{ payment_method.cardNumber }}">

                <label class="label" for="add-card-name">
                    {{ language.translate("Name on card") }} <span class="asterisk">*</span>
                </label>
                <input id="add-card-name" name="card-holder" class="input" type="text" value="{{ payment_method.cardHolderName }}">
            </div>
        </div>
        <div class="col6 second">
            <div class="info box">
                <i class="ico ico-info"></i>
                <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
            </div>
        </div>
    </div>
</div>

<div class="cl card-detail">
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
    <div class="left">
        <label class="label block" for="cvv">
            {{ language.translate("Security Code / CVV") }} <span class="asterisk">*</span>
        </label>
        <input id="cvv" name="card-cvv" class="input cvv" type="text">
    </div>
    <input id="id_payment" name="id_payment" value="{{ payment_method.id_payment }}" type="hidden"/>
    <label class="btn submit green right" for="new-card">
        {{ language.translate("Add a new card") }}
        <input id="new-card" type="submit" class="hidden">
    </label>
</div>