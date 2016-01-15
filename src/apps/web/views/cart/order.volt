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
        var currency_symbol = '<?php echo $currency_symbol?>';
        var price_below_fee = '<?php echo $fee_below ?>';
        var fee_charge = '<?php echo $fee_charge ?>';
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


                <div class="box-top cl">
                    <div class="balance">
                        <span class="txt">{{ language.translate("Your current wallet balance:") }}</span>
                        <span class="value"><span class="old"><?php echo $currency_symbol ?> <?php echo $wallet_balance ?>.00</span> <span class="new"><?php echo $currency_symbol ?> 14.05</span></span>
                    </div>

                    <h2 class="h4 sub-txt">{{ language.translate("Order Summary") }}</h2>
                </div>

                <div id="cart-order"></div>

                {#            <div class="box-order">
                                <div class="row cl">
                                    <div class="txt-fee">
                                        {{ language.translate("Fee for transactions below") }} &euro; 12,00
                                    </div>
                                    <div class="right tweak">
                                        <div class="summary val">&euro; 0,35</div>
                                        <div class="box-funds cl">
                                            <a class="add-funds" href="javascript:void(0)">{{ language.translate("Add Funds to avoid charges") }}</a><br>
                                            <div class="box-combo">
                                                <div class="combo currency">&euro;</div><input class="combo input" type="text" placeholder='{{ language.translate("Insert an ammount")}}' value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cl">
                                    <div class="summary val">&euro; -25,00</div>
                                    <div class="box-wallet cl">
                                        <label for="pay-wallet" class="txt">Pay with your Wallet balance</label>
                                        <input id="pay-wallet" type="checkbox" class="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                {#            <div class="box-total cl">
                                <div class="txt-currency desktop">
                                    {{ language.translate("Currencies are just informative, transactions are charged in Euros.") }}
                                </div>

                                <div class="total">
                                    <div class="txt">{{ language.translate("Total") }}</div><div class="val">&euro; 400,00</div>
                                </div>
                            </div>

                            <div class="box-bottom cl">
                                <a href="javascript:void(0)" class="btn blue big buy">{{ language.translate("Continue to Payment") }}</a>
                            </div>#}

            </div>
        </div>
    </main>
{% endblock %}