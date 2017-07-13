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
	$('.submit.big.green').text('Pay ' + total);
    {#$('.submit.big.green').text('Pay ' + total + total_text);#}
	total_price_in_credit_card_form = total;
	}
	)


	$(function(){
	$('.buy').on('click',function(){
	if ($(this).text() == 'Buy now') {
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
	$('.submit.big.green').text('Pay ' + total_price_in_credit_card_form);
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
								<a href="/christmas/play" class="btn purple small ui-link">Edit</a>
							</div>
							<div class="total">
								<div class="txt">Total to be paid</div>
								<div class="val">{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</div>
								<div class="box-bottom cl">
									<a href="javascript:void(0);" data-btn="no-wallet" class="btn blue big buy ui-link">
										<span>Continue to payment</span>
										<span class="gap">|</span>
										<span>{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</span>
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
							<div class="summary val">{{ currency_symbol }} {{ single_bet_price }}</div>
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
							<div class="summary val disabled">{{ currency_symbol }} {{ wallet_balance | number_format (2,'.','') }}</div>
							<div class="box-wallet cl">
								<label class="txt">Pay with your Account balance</label>
								<div class=" ui-checkbox">
									<input id="pay-wallet" type="checkbox" class="checkbox" />
								</div>
							</div>
						</div>
						{% endif %}
						<div class="box-bottom cl">
							<a href="javascript:void(0);" data-btn="no-wallet" class="btn blue big buy ui-link">
								<span>Continue to payment</span>
								<span class="gap">|</span>
								<span>{{ currency_symbol }} {{ total_price | number_format (2,'.','') }}</span>
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
