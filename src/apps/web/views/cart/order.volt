{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts_code %}
        {# //vars to cart.jsx #}
        var play_list = '<?php echo $order->getLines(); ?>';
        var wallet_balance = '<?php echo $order->getWalletBalance(); ?>';
        var total_price = parseFloat('<?php echo $order->getTotal(); ?>');
        var single_bet_price = '<?php echo number_format($order->getSingleBetPrice(), 2,".",",") ?>';
        var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
        var price_below_fee = '<?php echo number_format($order->getFeeLimit(),2,".",","); ?>';
        var fee_charge = '<?php echo number_format($order->getFee(),2,".",","); ?>';
        var symbol_position = '<?php echo $symbol_position ?>';
        var draw_days = '<?php echo $order->getDrawDays(); ?>';
        var show_order_cart = true;
        var total_price_in_credit_card_form = 0;

        $(document).on("totalPriceEvent",{total: 0},
                function(e, total) {
                    total_price_in_credit_card_form = total;
                }
        )
        $(function(){
            $('.buy').on('click',function(){
                var value = $(this).data('btn');
                if(value == 'no-wallet') {
                    $('.submit.big.green').text('Pay ' + total_price_in_credit_card_form);
                    $('.payment').show();
                }
            })
            $('.submit.big.green').on('click', function() {

                var charge = 0;
                if(document.getElementById('charge') != null) {
                    charge = document.getElementById('charge').value;
                }
                var card_number = document.getElementById('card-number').value;
                var card_holder = document.getElementById('card-holder').value;
                var expiry_date = document.getElementById('expiry-date').value;
                var card_cvv = document.getElementById('card-cvv').value;
                //send token
                var params = 'charge='+charge+'&cardnumber='+card_number+'&cardholder='+card_holder+'&expirydate='+expiry_date+'&cardcvv='+card_cvv;
                $.ajax({
                    url: '/cart/payment',
                    data: params,
                    type: 'POST',
                    success: function(json) {
{# //                        if(json.result = 'OK') {
//                            location.href = json.url;
//                        }
#}
                    },
                    error: function (xhr, status, errorThrown) {
                        {# EMTD manage errrors #}
                    },
                });
            });
        });
{% endblock %}
{% block template_scripts_after %}<script src="/w/js/react/cart.js"></script>{% endblock %}

{% block bodyClass %}cart order minimal{% endblock %}
{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
    {#  Hide this content until we have multiple numbers
        <span class="type">5 {{ language.translate("numbers") }} + 3 {{ language.translate("stars") }}</span>
    #}

    <main id="content">
        <div class="wrapper">
            <div class="box-basic medium">
                <h1 class="h1 title yellow res">{{ language.translate("Shopping cart") }}</h1>
                <div class="terms">{{ language.translate("By purchasing you agree to") }} <a href="/legal/">{{ language.translate("Terms &amp; Conditions") }}</a></div>

                {#<div class="box-top cl">#}
                    {#&#123;&#35;<div class="balance">&#35;&#125;#}
                        {#&#123;&#35;<span class="txt">{{ language.translate("Your current wallet balance:") }}</span>&#35;&#125;#}
                        {#&#123;&#35;<span class="value"><span class="old"><?php echo $currency_symbol ?> <?php echo $wallet_balance ?>.00</span> <span class="new"><?php echo $currency_symbol ?> 14.05</span></span>&#35;&#125;#}
                    {#&#123;&#35;</div>&#35;&#125;#}

                    {#<h2 class="h4 sub-txt">{{ language.translate("Order Summary") }}</h2>#}
                {#</div>#}

                <div id="cart-order"></div>
                <div class="payment hidden">
                    {% set component='{"where": "cart"}'|json_decode %}
                    {% include "account/_add-card.volt" %}
                </div>

            </div>
        </div>
    </main>
{% endblock %}