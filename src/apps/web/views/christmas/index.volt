{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/play.css">{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/christmasPlay.js"></script>
{% endblock %}

{% block bodyClass %}play{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "christmas"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
	<main id="content">
		<div class="wrapper">
			<div class="gameplay border-top-yellow">
                <form action="/christmas/order" method="post" id="christmasForm">
                    <img src="/w/img/christmas/ticket.jpg" width="350px" />
                    <br /><br />
                    <input type="button" id="add_1" value="+" />
                    {{ singleBetPrice }}
                    {{ currencySymbol }}
                    <input type="hidden" id="maxTickets_1" value="2" />
                    <input type="hidden" id="numTickets_1" name="numTickets_1" value="0" />
                    <input type="button" id="remove_1" value="-" />
                    <br />
                    Max Tickets: 2 - Tickets: 0
                    <!-- Total Price Info -->
                    <div class="box-bottom">
                        <div class="right">
                            <span class="total-price-description">Total price: </span>
                            <span class="description-before-price">x boletos x precio boleto</span>

                            <a href="javascript:void(0);" id="nextButton" class="btn add-cart">
                                <span class="value">
                                    {{ currencySymbol }}
                                    <span id="totalPriceValue">0.00</span>
                                </span>
                                <span class="gap"><span class="separator"></span></span>
                                <span>Next</span>
                            </a>
                        </div>
                        <br /><br />
                        <input type="hidden" id="totalTickets" name="totalTickets" value="0" />
                        <input type="hidden" id="singleBetPrice" value="{{ singleBetPrice | number_format (2,'.','') }}" />
                    </div>
                </form>
			</div>
		</div>
	</main>
{% endblock %}
