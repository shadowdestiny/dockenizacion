{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts_code %}
    {# //vars to cart.jsx #}
    var config = '<?php echo $config; ?>';
    var play_list = '<?php echo $order->lines; ?>';
    var checked_wallet = '<?php echo empty($checked_wallet) ? false : true; ?>';
    var show_form_credit_card = '<?php echo $show_form_credit_card; ?>';
    var wallet_balance = '<?php echo $wallet_balance; ?>';
    var total_price ='<?php echo $total_price ?>';
    var single_bet_price = '<?php echo $order->single_bet_price_converted->getAmount() / 100 ?>';
    var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
    var price_below_fee = '<?php echo number_format($fee_limit,2,".",","); ?>';
    var fee_charge = '<?php echo number_format($fee,2,".",","); ?>';
    var symbol_position = '<?php echo $symbol_position ?>';
    var draw_days = '<?php echo $order->drawDays; ?>';
    var show_order_cart = true;
    var total_price_in_credit_card_form = 0;
    var ratio = '<?php echo $ratio; ?>'
    var total_in_eur = 0;



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
            $('.submit.big.green').text('Pay ' + total + total_text);
                total_price_in_credit_card_form = total;
            }
    )


    $(function(){
        $('.buy').on('click',function(){
            var value = $(this).data('btn');
            if(value == 'no-wallet') {
                var total_text = '';
                if(currency_symbol !== '€'){
                    var total_price = accounting.unformat(total_price_in_credit_card_form.slice(1));
                    var total_convert =  accounting.unformat(total_price) / accounting.unformat(ratio);//parseFloat(parseFloat(total_eur).toFixed(2) + parseFloat(param2).toFixed(2));
                   // var total =  parseFloat(total_price_in_credit_card_form.slice(1)).toFixed(2)/parseFloat(ratio).toFixed(2);
                    total_text = '(€'+parseFloat(total_convert).toFixed(2)+')';
                }
                $('.submit.big.green').text('Pay ' + total_price_in_credit_card_form + total_text);
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
        $('#paywallet').val($('#pay-wallet').is(':checked') ? true : false);
        $('#funds').val($('#charge').val());
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
{% endblock %}

{% block bodyClass %}cart order minimal{% endblock %}
{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}

{% block body %}
    {#  Hide this content until we have multiple numbers
        <span class="type">5 {{ language.translate("numbers") }} + 3 {{ language.translate("stars") }}</span>
    #}

    <main id="content">
        <div class="wrapper">
            <div class="box-basic medium">
                <h1 class="h1 title yellow res">{{ language.translate("Review and Buy") }}</h1>
                <div class="terms">{{ language.translate("By purchasing you agree to") }} <a href="/legal/">{{ language.translate("Terms &amp; Conditions") }}</a></div>

                {#<div class="box-top cl">#}
                {#&#123;&#35;<div class="balance">&#35;&#125;#}
                {#&#123;&#35;<span class="txt">{{ language.translate("Your current account balance:") }}</span>&#35;&#125;#}
                {#&#123;&#35;<span class="value"><span class="old"><?php echo $currency_symbol ?> <?php echo $wallet_balance ?>.00</span> <span class="new"><?php echo $currency_symbol ?> 14.05</span></span>&#35;&#125;#}
                {#&#123;&#35;</div>&#35;&#125;#}

                {#<h2 class="h4 sub-txt">{{ language.translate("Order Summary") }}</h2>#}
                {#</div>#}

                <div id="cart-order"></div>
                <div class="payment hidden">
                    <form class="box-add-card form-currency {#{% if which_form != 'edit' and which_form%}hidden{% endif %}#}" method="post" action="/cart/payment{#{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}#}">
                        {% set component='{"where": "cart"}'|json_decode %}
                        {% include "account/_add-card.volt" %}
                    </form>
                </div>

            </div>
        </div>
    </main>
{% endblock %}
