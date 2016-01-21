{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}
{% block template_scripts %}
    {% set play_configs = play_config_list %}
    <script>

        var play_list = '<?php echo $play_configs ?>';
        var wallet_balance = '<?php echo $wallet_balance ?>';
        var total_price = parseFloat('<?php echo $total?>');
        var single_bet_price = '<?php echo number_format($single_bet_price, 2,".",",") ?>';
        var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
        var price_below_fee = '<?php echo number_format($fee_below,2,".",","); ?>';
        var fee_charge = '<?php echo number_format($fee_charge,2,".",","); ?>';
        var symbol_position = '<?php echo $symbol_position ?>';

    </script>
    <script src="/w/js/react/cart.js"></script>
{% endblock %}
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
                <div class="payment">
                    {% set component='{"where": "cart"}'|json_decode %}
                    {% include "account/_add-card.volt" %}
                </div>

            </div>
        </div>
    </main>
{% endblock %}