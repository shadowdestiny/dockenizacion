{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/play.css">
    <link Rel="Canonical" href="{{ language.translate('canonical_christmas_play') }}" />
{% endblock %}
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
                <h1 class="h3 draw">{{ language.translate("playchris_top_right") }}</h1>
                <span class="h1 jackpot">
                    {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                    {% include "_elements/christmasJackpot-value" with ['extraClass': extraClass] %}
                    - 22nd December 2017
			    </span>
            </header>
            <div class="gameplay border-top-yellow">
                {#
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
                                    <input type="button" id="remove_{{ ticket['id'] }}" class="removeTicket" value="-"/>
                                    {{ singleBetPrice }}
                                    {{ currencySymbol }}
                                    <input type="hidden" id="maxTickets_{{ ticket['id'] }}"
                                           value="{{ ticket['n_fractions'] }}"/>
                                    <input type="hidden" id="numTickets_{{ ticket['id'] }}"
                                           name="numTickets_{{ ticket['id'] }}" value="0"/>

                                    <input type="button" id="add_{{ ticket['id'] }}" class="addTicket" value="+"/>
                                    <br/>
                                    <br/>
                                    <div style="float:left; margin-left: 20px; margin-bottom: 10px;">{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  </div>
                                    <div style="float:right; margin-right: 20px; margin-bottom: 10px;">{{ language.translate("playchris_tck_buy") }}
                                        <span id="showNumTickets_{{ ticket['id'] }}">0</span></div>
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
                            <span class="total-price-description">{{ language.translate("playchris_total") }} </span>
                            <span class="description-before-price"><span
                                        id="showTotalTickets">0</span> {{ language.translate("playchris_total_tck") }}
                                x {{ singleBetPrice | number_format (2,'.','') }} {{ currencySymbol }}</span>

                            <a href="javascript:void(0);" id="nextButton" class="btn add-cart">
                                <span class="value">
                                    {{ currencySymbol }}
                                    <span id="totalPriceValue">0.00</span>
                                </span>
                                <span class="gap"><span class="separator"></span></span>
                                <span>{{ language.translate('next_btn') }}</span>
                            </a>
                        </div>
                        <br/><br/>
                        <input type="hidden" id="totalTickets" value="0"/>
                        <input type="hidden" id="singleBetPrice"
                               value="{{ singleBetPrice | number_format (2,'.','') }}"/>
                    </div>
                    #}
                    <div class="box-bottom">
                        <p align="justify">
                        <h1 style="font-weight: bold; font-size: 26px;">{{ language.translate("playchris_txt_tit") }}</h1>
                        <br/>
                        {{ language.translate("playchris_txt_1") }}<br/>
                        <br/>
                        <h3 style="font-weight: bold; font-size: 20px;">{{ language.translate("playchris_txt_howto_tit") }}</h3>
                        <br/>
                        {{ language.translate("playchris_txt_howto_1") }}<br/>
                        <ol style="margin-left: 30px;">
                            {{ language.translate("playchris_top_right") }}
                        </ol>
                        <br/>
                        <h3 style="font-weight: bold; font-size: 20px;"> {{ language.translate("playchris_txt_what_tit") }}</h3>
                        <br/>
                        <ul style="margin-left: 30px;">
                            {{ language.translate("playchris_txt_what_1") }}
                        </ul>
                        <br/>
                        <h3 style="font-weight: bold; font-size: 20px;">{{ language.translate("playchris_txt_howmany_tit") }}</h3>
                        <br/>
                        {{ language.translate("playchris_txt_howmany_1") }}<br/>
                        <br/></strong>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>
{% endblock %}
