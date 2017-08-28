{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/play.css">{% endblock %}
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
            <header class="bg-top cl">
                <h1 class="h3 draw">{{ language.translate("Choose your favourite tickets") }}</h1>
                <span class="h1 jackpot">
                    {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                    {% include "_elements/christmasJackpot-value" with ['extraClass': extraClass] %}
                    - 22nd December 2017
			    </span>
            </header>
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
                                        <img src="/w/img/christmas/ticket.png" width="300px"/>
                                        <span class="christmasTicketTxt">{{ ticket['number'] }}</span>
                                    </div>
                                    <input type="button" id="add_{{ ticket['id'] }}" class="addTicket" value="+"/>
                                    {{ singleBetPrice }}
                                    {{ currencySymbol }}
                                    <input type="hidden" id="maxTickets_{{ ticket['id'] }}"
                                           value="{{ ticket['n_fractions'] }}"/>
                                    <input type="hidden" id="numTickets_{{ ticket['id'] }}"
                                           name="numTickets_{{ ticket['id'] }}" value="0"/>
                                    <input type="button" id="remove_{{ ticket['id'] }}" class="removeTicket" value="-"/>
                                    <br/>
                                    Available Tickets: {{ ticket['n_fractions'] }} - Your Tickets: <span
                                            id="showNumTickets_{{ ticket['id'] }}">0</span>
                                </td>
                                <td width="15">&nbsp;</td>
                                {% set cont = cont + 1 %}
                                {% if cont == device %}
                                {% set cont = 0 %}
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                {% endif %}
                                {% endfor %}
                            </tr>
                        </table>
                    {% else %}
                        <h3>We don't have Christmas Tickets.</h3>
                    {% endif %}

                    <div class="box-bottom">
                        <div class="right">
                            <span class="total-price-description">Total price: </span>
                            <span class="description-before-price"><span
                                        id="showTotalTickets">0</span> tickets x {{ singleBetPrice | number_format (2,'.','') }} {{ currencySymbol }}</span>

                            <a href="javascript:void(0);" id="nextButton" class="btn add-cart">
                                <span class="value">
                                    {{ currencySymbol }}
                                    <span id="totalPriceValue">0.00</span>
                                </span>
                                <span class="gap"><span class="separator"></span></span>
                                <span>Next</span>
                            </a>
                        </div>
                        <br/><br/>
                        <input type="hidden" id="totalTickets" value="0"/>
                        <input type="hidden" id="singleBetPrice"
                               value="{{ singleBetPrice | number_format (2,'.','') }}"/>
                    </div>

                    <div class="box-bottom">
                        <p align="justify">
                            <strong>The 2017 Christmas Lottery: the biggest lottery in the world.</strong><br/>
                            <br/>
                            El Gordo De Navidad is a Spanish Christmas lottery that is drawn every December 22nd in
                            Madrid and is considered the most generous lottery in the world for the biggest prize
                            amount. <strong>The estimated prize pool for this year’s draw is a massive €2.3
                                BILLION</strong>. Now on EuroMillions.com you can easily play and win the El Gordo
                            Christmas lottery for only €25 per ticket.<br/>
                            <strong>How to play the Christmas Lottery</strong><br/>
                            <br/>
                            The system under which the tickets of El Gordo Christmas lottery are sold is different from
                            the one you are used to on EuroMillions, but it's really easy:<br/>
                        <ol style="margin-left: 30px;">
                            <li>Choose you favourite 5-digit number from 00000 to 99999, from the tickets available.
                            </li>
                            <li>Choose how many tickets you want to play.</li>
                            <li>Once you have selected your tickets click on Next to pay them.</li>
                        </ol>
                        <br/>
                        <strong>What is the prize structure for the Christmas Lottery</strong><br/>
                        <ul style="margin-left: 30px;">
                            <li>1st prize - 1 prize of €400,000 for each ticket</li>
                            <li>2nd prize - 1 prize of €125,000 for each ticket</li>
                            <li>3rd prize - 1 prize of €50,000 for each ticket</li>
                            <li>4th prize - 2 prizes of €20,000 for each ticket</li>
                            <li>5th prize - 8 prizes of €6,000 for each ticket</li>
                            <li>1.794 series of 5 correct numbers each win €1,000</li>
                            <li>9.999 of matches of the last El Gordo digit each win €200 for the serie</li>
                        </ul>
                        <br/>
                        <strong>How many tickets there are on sale</strong><br/>
                        <br/>
                        The Christmas Lottery has a particular play mechanic that guarantees much more prizes for
                        everyone. You can buy the same tickets 2 times. See how many numbers are available:<br/>
                        <br/>
                        <ul style="margin-left: 30px;">
                            <li>There are available all numbers from 00000 to 99999</li>
                            <li>For each number there are available 170 series (groups)</li>
                            <li>Each serie is composed by 10 tickets (This is what you buy)</li>
                        </ul>
                        <br/>So, in summary, for each different number there are 1,700 tickets to be sold. <strong>That
                            means that up to 1,700 people would win the first prize!</strong>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>
{% endblock %}
