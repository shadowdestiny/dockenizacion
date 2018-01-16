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


        <div class="christmas--page">

            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        Spanish christmas lottery
                    </h1>
                </div>


                <header>
                    <div class="left">
                        <div class="top">
                            spanish christmas
                        </div>
                        <div class="bottom">
                            lottery
                        </div>
                    </div>
                    <div class="right">
                        {#TODO : Add real variables here#}
                        {#{{ language.translate("playchris_top_right") }}#}
                        <div class="top">
                            in price
                        </div>
                        <div class="bottom">
                            22 dec 2017
                        </div>
                    </div>
                    <div class="jackpot">

                        {#TODO : Add real variables here#}
                        {#{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}#}
                        {#{% include "_elements/christmasJackpot-value" with ['extraClass': extraClass] %}#}

                        €2.3 billion

                        <span class="mobile">
                            in price, 22 Dec 2017
                        </span>

                    </div>
                </header>


                <div class="gameplay">
                    <form action="/christmas/order" method="post" id="christmasForm">
                        {% if christmasTickets is defined %}
                            <table class="tickets--table">

                                <tr class="row">
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#TODO : Add real variables here#}
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#TODO : Add real variables here#}

                                                    {{ singleBetPrice }}
                                                    {{ currencySymbol }}
                                                    {#25 €#}
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#TODO : Add real variables here#}
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}#}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-ticket">

                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png"/>
                                            <span class="christmasTicketTxt">
                                                {#{{ ticket['number'] }}#}
                                                00453
                                            </span>
                                            </div>

                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_id" class="removeTicket" value="-"/>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    25 €
                                                    <input type="hidden" id="maxTickets_id"
                                                           value="ticket-n_fractions"/>
                                                    <input type="hidden" id="numTickets_ticketid"
                                                           name="numTickets_ticketid" value="0"/>

                                                    <input type="button" id="add_id" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {#{{ language.translate("playchris_tck_av") }} {{ ticket['n_fractions'] }}  #}
                                                    7 Tickets Available
                                                </div>
                                                {#<div style="float:right; margin-right: 20px; margin-bottom: 10px;">#}
                                                {#{{ language.translate("playchris_tck_buy") }}#}
                                                {#Your tickets:#}
                                                {#<span id="showNumTickets_ticketid">0</span>#}
                                                {#</div>#}

                                            </div>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    {% set cont = 0 %}
                                    {% for ticket in christmasTickets %}
                                    <td width="15">&nbsp;</td>
                                    <td align="center" style="background-color: white;">
                                        <div class="christmasTicketImg">
                                            <img src="/w/img/christmas/ticket.png" width="300px"/>
                                            <span class="christmasTicketTxt">{{ ticket['number'] }}</span>
                                        </div>
                                        <input type="button" id="remove_{{ ticket['id'] }}" class="removeTicket"
                                               value="-"/>
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

                        <div class="box-bottom under-table-row">
                            <div class="left">
                                Buy Spanish Christmass Lottery <br class="br-desktop">
                                <span>
                                2.3 Billion in Price, 22 December
                                    </span>
                            </div>
                            <div class="right">
                                <span class="total-price-description">

                                    {#TODO : Add real variables here#}
                                    {#{{ language.translate("playchris_total") }}#}
                                    Total price
                                </span>
                                <span class="description-before-price"><span
                                            id="showTotalTickets">0</span>
                                    {#TODO : Add real variables here#}
                                    {#{{ language.translate("playchris_total_tck") }}#}
                                    tickets
                                    x {{ singleBetPrice | number_format (2,'.','') }} {{ currencySymbol }}
                                </span>

                                <a href="javascript:void(0);" id="nextButton" class="btn add-cart">
                                <span class="value">
                                    {{ currencySymbol }}
                                    <span id="totalPriceValue">0.00</span>
                                </span>
                                    {#<span class="gap"><span class="separator"></span></span>#}
                                <span>{{ language.translate('next_btn') }}</span>
                                </a>
                            </div>

                            <input type="hidden" id="totalTickets" value="0"/>
                            <input type="hidden" id="singleBetPrice"
                                   value="{{ singleBetPrice | number_format (2,'.','') }}"/>
                        </div>
                    #}
                        {#TODO : Add real variables here#}
                        {% include "_elements/play-bottom-block.volt" %}


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

        </div>

    </main>
{% endblock %}
