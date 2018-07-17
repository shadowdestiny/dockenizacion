{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
    <link rel="stylesheet" href="/w/css/main.css">
{% endblock %}
{% block template_scripts_code %}
    {# //vars to cart.jsx #}
    var config = '<?php echo $config; ?>';
    var play_list = '<?php echo $order->lines; ?>';
    var checked_wallet = '<?php echo empty($checked_wallet) ? false : true; ?>';
    var show_form_credit_card = '<?php echo $show_form_credit_card; ?>';
    var wallet_balance = '<?php echo $wallet_balance; ?>';
    var total_price ='<?php echo $total_price ?>';
    var single_bet_price = '<?php echo $order->singleBetPriceWithDiscountConverted->getAmount() / 100 ?>';
    var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
    var price_below_fee = '<?php echo number_format($fee_limit,2,".",","); ?>';
    var fee_charge = '<?php echo number_format($fee,2,".",","); ?>';
    var symbol_position = '<?php echo $symbol_position ?>';
    var draw_days = '<?php echo $order->drawDays; ?>';
    var show_order_cart = true;
    var total_price_in_credit_card_form = 0;
    var ratio = '<?php echo $ratio; ?>';
    var total_in_eur = 0;
    var discount = <?php echo $discount; ?>;
    var txt_summary = "{{ language.translate("summary") }}";
    var txt_draws = "{{ language.translate("draws") }}";
    var txt_on = "{{ language.translate("date") }}";
    var txt_currencyAlert = "{{ language.translate("currencyAlert") }}";
    var txt_total = "{{ language.translate("total") }}";
    var txt_payWithBalanceOption = "{{ language.translate("payWithBalanceOption") }}";
    var txt_gotopay_btn = "{{ language.translate("gotopay_btn") }}";
    var txt_buy_btn = "{{ language.translate("buy_btn") }}";
    var txt_depositBuy_btn = "{{ language.translate("depositBuy_btn") }}";
    var txt_checkout_fee = "{{ language.translate("checkout_fee") }}";
    var txt_edit = "{{ language.translate("edit_btn") }}";
    var txt_link_play = "{{ language.translate("link_euromillions_play") }}";
    var txt_link_powerball = "{{ language.translate("link_powerball_play") }}";
    var txt_line = '{{ language.translate('line_x') }}';
    var tuesday = '{{ language.translate('tuesday') }}';
    var friday = '{{ language.translate('friday') }}';
    var wednesday = '{{ language.translate('wednesday') }}';
    var saturday = '{{ language.translate('saturday') }}';
    var powerball = true;
    var powerplay = <?php echo $power_play; ?>;
    var powerplayprice = <?php echo $power_play_price; ?>;
    var txt_lottery = '<?php echo $lottery_name; ?>';
    var playingPP = '{{ language.translate('checkout_powerplay') }}';
    var txt_for = '{{ language.translate('subs_for') }}';
    var txt_since = '{{ language.translate('subs_since') }}';
    var txt_weeks = '{{ language.translate('subs_weeks') }}';



    $(document).on("totalPriceEvent",{total: 0, param2: 0},function(e, total, param2) {
    var total_text = '';
    if(currency_symbol !== '€') {
    var rest_total_from_funds = accounting.unformat(total.slice(1)) - accounting.unformat(param2);
    var total_eur = accounting.unformat(rest_total_from_funds)/accounting.unformat(ratio);
    var total_convert =  accounting.unformat(total_eur) + accounting.unformat(param2);//parseFloat(parseFloat(total_eur).toFixed(2) + parseFloat(param2).toFixed(2));
    var convert = accounting.toFixed(total_convert,2)
    total_text = '(€'+convert+')';
    }
    total_price_in_credit_card_form = 0;
    $('.submit.big.green').text('');
    $('.submit.big.green').text(txt_depositBuy_btn + ' ' + total);
    {#$('.submit.big.green').text('Pay ' + total + total_text);#}
    total_price_in_credit_card_form = total;
    }
    )


    $(function(){
    $('.buy').on('click',function(){
    if ($(this).text() == txt_buy_btn) {
    $(this).text('Please wait...');
    $(this).css('pointer-events', 'none');
    }
    var value = $(this).data('btn');
    if(value == 'no-wallet') {
    var total_text = '';
    if(currency_symbol !== '€'){
    var total_price = accounting.unformat(total_price_in_credit_card_form.slice(1));
    var total_convert =  accounting.unformat(total_price) / accounting.unformat(ratio);//parseFloat(parseFloat(total_eur).toFixed(2) + parseFloat(param2).toFixed(2));
    // var total =  parseFloat(total_price_in_credit_card_form.slice(1)).toFixed(2)/parseFloat(ratio).toFixed(2);
    total_text = '(€'+parseFloat(total_convert).toFixed(2)+')';
    }
    $('.submit.big.green').text(txt_depositBuy_btn + ' ' + total_price_in_credit_card_form);
    {#$('.submit.big.green').text('Pay ' + total_price_in_credit_card_form + total_text);#}
    $('.payment').show();
    $('.box-bottom').hide();
    var $root = $('html, body');
    $root.animate({
    scrollTop: $('#card-number').offset().top
    }, 500);
    $('#card-number').focus();
    } else {
    $('.payment').hide();
    }
    })
    if(show_form_credit_card) {
    $('.box-bottom').hide();
    $('.payment').show();
    $('#card-number').focus();
    }
    });

    $('.box-add-card').on('submit',function(){
    var disabled = $('label.submit').hasClass('gray');
    var cardNumber = $('#card-number');
    if(disabled) {
    return false;
    }
    $('label.submit').removeClass('green').addClass('gray');
    $('label.submit').text('Please wait...');
    cardNumber.val(cardNumber.val().replace(/ /g, ''));
    $('#paywallet').val($('#pay-wallet').is(':checked') ? true : false);
    $('#funds').val($('#charge').val());
    $.cookie('csid', $('#csid').val());
    $.cookie('url_gcp', window.location.protocol+'//'+window.location.host+'/euromillions/gcp');
    });

    $('#expiry-date-month').on('keyup',function(e){
    var charCount = $(this).val().length;
    if (charCount==2){
    $('#expiry-date-year').focus();
    }
    });

    $('#expiry-date-year').on('keyup',function(e){
    var charCount = $(this).val().length;
    if (charCount==2){
    $('#card-cvv').focus();
    }
    });

    $('#card-cvv,#card-number').on('keypress',function(e){
    var pattern = /^[0-9\.]+$/;
    if(e.target.id == 'card-cvv') {
    pattern = /^[0-9]+$/;
    }
    var codeFF = e.keyCode;
    var code = e.which
    var chr = String.fromCharCode(code);
    if(codeFF == 8 || codeFF == 37 || codeFF == 38 || codeFF == 39 || codeFF == 40 ) {
    return true;
    }
    if(!pattern.test(chr)){
    e.preventDefault();
    }
    });
{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/react/cart.js"></script>
    <script src="/w/js/react/tooltip.js"></script>
    <script type="text/javascript" src="/w/js/csid.js" charset="UTF-8"></script>
    {% if ga_code is defined %}
        <!--start PROD imports
        <script src="/w/js/dist/GASignUpOrder.min.js"></script>
        end PROD imports-->
        <!--start DEV imports-->
        <script src="/w/js/GASignUpOrder.js"></script>
        <!--end DEV imports-->

    {% endif %}
{% endblock %}

{% block bodyClass %}cart order minimal{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": "order"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block body %}
    {#  Hide this content until we have multiple numbers
        <span class="type">5 {{ language.app("numbers") }} + 3 {{ language.app("stars") }}</span>
    #}

    <main id="content" class="">
        <div class="wrapper">
            <div class="review_and_pay-section">
                <section class="section--review-and-pay">
                    <div class="top-row">
                        <h1 class="">{{ language.translate("checkout_head") }}</h1>
                        <p class="">{{ language.translate("terms") }}</a></p>
                    </div>
                </section>
                {#<div class="box-top cl">#}
                {#&#123;&#35;<div class="balance">&#35;&#125;#}
                {#&#123;&#35;<span class="txt">{{ language.app("Your current account balance:") }}</span>&#35;&#125;#}
                {#&#123;&#35;<span class="value"><span class="old"><?php echo $currency_symbol ?> <?php echo $wallet_balance ?>.00</span> <span class="new"><?php echo $currency_symbol ?> 14.05</span></span>&#35;&#125;#}
                {#&#123;&#35;</div>&#35;&#125;#}

                {#<h2 class="h4 sub-txt">{{ language.app("Order Summary") }}</h2>#}
                {#</div>#}
                <section class="section--numbers-played">
                    <div class="section--content">
                        <div id="cart-order"></div>
                    </div>
                </section>


                <div class="payment hidden">

                    <section class="section--card--details">

                        <div class="top-row">
                            <h1 class="h2">
                                {{ language.translate("card_subhead") }}
                            </h1>
                        </div>
                        <div class="section--content">
                            <form class="box-add-card form-currency {#{% if which_form != 'edit' and which_form%}hidden{% endif %}#}"
                                  method="post"
                                  action="/powerball/payment{#{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}#}">
                                {% set component='{"where": "cart"}'|json_decode %}
                                {% include "account/_add-card.volt" %}
                                <input type="hidden" id="csid" name="csid"/>
                            </form>
                        </div>
                    </section>
                </div>

            </div>

        </div>
    </main>
    {% include "_elements/footer.volt" %}
{% endblock %}
