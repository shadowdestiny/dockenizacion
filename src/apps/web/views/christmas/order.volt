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
{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}

{% block body %}
	<main id="content">
		<div class="wrapper">
			<div class="box-basic medium">
				<h1 class="h1 title yellow res">{{ language.translate("Review and Buy") }}</h1>
				<div class="terms">{{ language.translate("By purchasing you agree to") }} <a href="/legal/">{{ language.translate("Terms &amp; Conditions") }}</a></div>
				<div id="cart-order">
					<div class="box-order">
						<div class="box-total-upper cl">
							<div class="box-top cl">
								<h1>ORDER SUMMARY</h1>
							</div>
							<div class="txt-black-upper cl">
								<a href="/christmas-lottery/play" class="btn purple small ui-link">Edit</a>
							</div>
							<div class="total">
								<div class="txt">Total to be paid</div>
								<div class="val">{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</div>
								<div class="box-bottom cl">
									<a href="javascript:void(0);" data-btn="no-wallet" class="btn blue big buy ui-link">
										Continue to payment | {{ currency_symbol }} {{ total_price | number_format (2,'.','') }}
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="box-order">
						{% for christmasTicket in christmasTickets %}
						<div class="row cl">
							<span class="desc">Num: </span>
							<span class="detail">{{ christmasTicket.getNumber() }}</span>
							<div class="summary">{{ currency_symbol }} {{ single_bet_price | number_format (2,'.','') }}</div>
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
								Continue to payment | {{ currency_symbol }} {{ total_price | number_format (2,'.','') }}
							</a>
						</div>
					</div>
				</div>
				<div class="payment hidden">
					<form class="box-add-card form-currency" method="post" action="/christmas/payment">
                        {% set component='{"where": "cart"}'|json_decode %}
                        {% include "account/_add-card.volt" %}
						<input type="hidden" id="csid" name="csid"/>
					</form>
				</div>
				<p>
				<div class="images-payment-center col4 box-partner">
					<ul class="no-li inline"  style="float:right">
						<li><a href="http://www.visaeurope.com/"><svg class="v-visa vector"><use xlink:href="/w/svg/icon.svg#visa"/></svg></a></li>
						<li><a href="http://www.mastercard.com/eur/"><svg class="v-mastercard vector"><use xlink:href="/w/svg/icon.svg#mastercard"/></svg></a></li>
						<li><a href="https://ssl.comodo.com/"><img src="/w/svg/comodo.png"/> </a></li>
					</ul>
				</div>
			</div>
		</div>
	</main>
    {% include "_elements/minimal-footer.volt" %}
{% endblock %}
