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
                    {% if christmasTickets is defined %}
                        <table style="margin-top: 15px; margin-bottom: 15px;">
                            <tr>
                            {% set cont = 0 %}
                            {% for ticket in christmasTickets %}
                                <td width="15">&nbsp;</td>
                                <td align="center" style="background-color: white;">
                                    <div class="christmasTicketImg">
                                        <img src="/w/img/christmas/ticket.png" width="300px" />
                                        <span class="christmasTicketTxt">{{ ticket['number'] }}</span>
                                    </div>
                                    <input type="button" id="add_{{ ticket['id'] }}" class="addTicket" value="+" />
                                    {{ singleBetPrice }}
                                    {{ currencySymbol }}
                                    <input type="hidden" id="maxTickets_{{ ticket['id'] }}" value="{{ ticket['n_fractions'] }}" />
                                    <input type="hidden" id="numTickets_{{ ticket['id'] }}" name="numTickets_{{ ticket['id'] }}" value="0" />
                                    <input type="button" id="remove_{{ ticket['id'] }}" class="removeTicket" value="-" />
                                    <br />
                                    Available Tickets: {{ ticket['n_fractions'] }} - Your Tickets: <span id="showNumTickets_{{ ticket['id'] }}">0</span>
                                </td>
                                <td width="15">&nbsp;</td>
                                {% set cont = cont + 1 %}
                                {% if cont == 3 %}
                                    {% set cont = 0 %}
                                    </tr><tr><td>&nbsp;</td></tr><tr>
                                {% endif %}
                            {% endfor %}
                            </tr>
                        </table>
                    {% else %}
                        <h3>We don't have Christmas Tickets.</h3>
                    {% endif %}
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
                        <input type="hidden" id="totalTickets" value="0" />
                        <input type="hidden" id="singleBetPrice" value="{{ singleBetPrice | number_format (2,'.','') }}" />
                    </div>
                </form>
			</div>
		</div>
	</main>
{% endblock %}
