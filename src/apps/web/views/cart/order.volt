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
var single_bet_price = '<?php echo number_format($order->single_bet_price->getAmount() /100, 2,".",",") ?>';
var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
var price_below_fee = '<?php echo number_format($fee_limit,2,".",","); ?>';
var fee_charge = '<?php echo number_format($fee,2,".",","); ?>';
var symbol_position = '<?php echo $symbol_position ?>';
var draw_days = '<?php echo $order->drawDays; ?>';
var show_order_cart = true;
var total_price_in_credit_card_form = 0;

$(document).on("totalPriceEvent",{total: 0},
        function(e, total) {
            $('.submit.big.green').text('Pay ' + total);
            total_price_in_credit_card_form = total;
        }
)
$(function(){
    $('.buy').on('click',function(){
        var value = $(this).data('btn');
        if(value == 'no-wallet') {
            $('.submit.big.green').text('Pay ' + total_price_in_credit_card_form);
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
                <form class="box-add-card form-currency {#{% if which_form != 'edit' and which_form%}hidden{% endif %}#}" method="post" action="/cart/payment{#{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}#}">
                    {% set component='{"where": "cart"}'|json_decode %}
                    {% include "account/_add-card.volt" %}
                </form>
            </div>

        </div>
    </div>
</main>
{% endblock %}