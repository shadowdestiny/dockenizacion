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
                <img src="/w/img/christmas/ticket.jpg" width="350px" />
                <br /><br />
                <input type="button" id="add_1" value="+" />
                {{ singleBetPrice }}
                {{ currencySymbol }}
                <input type="hidden" id="maxTickets_1" value="2" />
                <input type="hidden" id="numTickets_1" value="0" />
                <input type="button" id="remove_1" value="-" />
                <div class="box-bottom">
                    <div class="right">
                        <span class="total-price-description">Total price: </span>
                        <span class="description-before-price">x boletos x precio boleto</span>
                        <a href="javascript:void(0);" class="btn add-cart active ui-link">
                            <span class="value">
                                {{ currencySymbol }}
                                {{ singleBetPrice }}
                            </span>
                            <span class="gap"><span class="separator"></span></span>
                            <span>Next</span>
                        </a>
                        <a href="javascript:void(0);" class="btn add-cart">
                            <span class="value">
                                {{ currencySymbol }}
                                0.00
                            </span>
                            <span class="gap"><span class="separator"></span></span>
                            <span>Next</span>
                        </a>
                    </div>
                    <br /><br />
                    <input type="hidden" id="numTickets" value="0" />
                </div>
			</div>
		</div>
	</main>
{% endblock %}
