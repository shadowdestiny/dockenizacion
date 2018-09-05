{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts_code %}
    {# //vars to cart.jsx #}
	var myLogged = '<?php echo $user_logged; ?>';
	var checked_wallet = '<?php echo empty($checked_wallet) ? false : true; ?>';
	var show_form_credit_card = '<?php echo $show_form_credit_card; ?>';
	var wallet_balance = '<?php echo $wallet_balance; ?>';
	var total_price ='<?php echo $total_price ?>';
	var single_bet_price = '<?php echo $single_bet_price ?>';
	var currency_symbol = '<?php echo empty($currency_symbol) ? $current_currency : $currency_symbol;?>';
	var symbol_position = '<?php echo $symbol_position ?>';
	var show_order_cart = true;
	var total_price_in_credit_card_form = 0;
	var ratio = '<?php echo $ratio; ?>';
	var total_in_eur = 0;
	var payTotalWallet = <?php echo $payTotalWithWallet; ?>;
	var priceWithWallet = <?php echo $priceWithWallet; ?>;
{% endblock %}
{% block template_scripts_after %}
	<script src="/w/js/christmasOrder.js"></script>
	<script src="/w/js/react/tooltip.js"></script>
	<script type="text/javascript" src="/w/js/csid.js" charset="UTF-8"></script>
    {%  if ga_code is defined %}
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
						<div id="cart-order">
							<div class="box-order">
								<div class="box-total-upper cl">
									<div class="txt-black-upper" >
											<h1>ORDER SUMMARY</h1>
										<span class="txt-bold">
											Christmas Lottery
										</span>
										<br>
										<span class="txt-bold">
											{{ dayDraw }}, {{ nextDrawDate }}
										</span>
									</div>



									<div class="txt-black-uppers cl" style="padding-left: 650px;">
										<a href="/{{ language.translate('link_christmas_play') }}" class="btn purple small ui-link">Edit</a>
									</div>
								</div>

							</div>
							<div class="box-order">
                                {% for christmasTicket in christmasTickets %}
									<div class="row cl">
										<span class="desc">Numbers </span>
										<span class="txt-bold" style="font-size: 25px;">
											{{ christmasTicket.getNumber() }}
										</span>
										<span class="txt-bold" style="font-size: 25px; padding-left: 600px;">
											{{ currency_symbol }} {{ single_bet_price | number_format (2,'.','') }}
										</span>
									</div>
                                {% endfor %}
							</div>
							<div class="box-total cl">
								<div class="txt-currency desktop">Currencies are just informative, transactions are charged in Euros. This transaction will appear as EuroMillions.com on your bank account statement. Payments and purchases are final and cannot be cancelled or refunded as they will be forwarded to our payment and ticket providers.</div>
								<div class="total">
									<div class="txt">Total to be paid</div>
									<div class="val">{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</div>
								</div>
								<br /><br /><br /><br /><br />
                                {% if wallet_balance != 0 %}
									<div class="row cl" style="color: black;">
										<div class="summary balance-price disabled" style="margin-left: 20px;">{{ currency_symbol }} 0.00</div>
										<div class="box-wallet cl">
											<label class="txt" for="pay-wallet">Pay with your Account balance</label>
											<div class=" ui-checkbox">
												<input id="pay-wallet" name="pay-wallet" type="checkbox" class="checkbox" />
											</div>
										</div>
									</div>
                                {% endif %}
								<div class="box-bottom cl">
									<a href="javascript:void(0);" data-btn="no-wallet" class="btn blue big buy ui-link">
										<span class="cont">Continue</span>
										<span class="money">{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</span>
									</a>
								</div>
							</div>
						</div>
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
								  action="/christmas/payment{#{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}#}">
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
