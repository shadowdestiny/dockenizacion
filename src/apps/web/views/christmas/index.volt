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

            <div class="banner">
                <div class="top-banner--section">

                    <div class="top-banner--banner">
                        <div class="wrapper">
                            <h1 class="top-banner--head">
                                {% if mobile == 1 %}
                                    {{ language.translate("playchris_mobile_h1") }}
                                {% else %}
                                    {{ language.translate("playchris_h1") }}
                                {% endif %}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper">

                <div class="title-block"></div>

                <header>
                    <div class="left">
                        <div class="top">
                            <div class="resizeme">
                            {{ language.translate("playchris_top_left") }}
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="resizeme">
                            {{ language.translate("playchris_top_left2") }}
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="top">
                        <div class="resizeme">
                            {{ language.translate("playchris_top_right") }}
                        </div>
                        </div>
                        <div class="bottom">
                            <div class="resizeme">

                        </div>
                        </div>
                    </div>
                    <div class="jackpot">
                        <div class="resizeme">
                        {{ currencySymbol }}{{ awardBillionChristmas }} {{ language.translate("playchris_top_middle") }}
                        </div>

                        {#<span class="mobile">#}
                            {#{{ currencySymbol }}{{ awardBillionChristmas }} {{ language.translate("playchris_top_middle") }}#}
                        {#</span>#}

                    </div>
                </header>


                <div class="gameplay">
                    <form action="/christmas/order" method="post" id="christmasForm">
                        {% if christmasTickets is defined %}
                            <table class="tickets--table">
                                <tr class="row">
                                    {% set cont = 0 %}
                                    {% for ticket in christmasTickets %}
                                    <td class="td-ticket">
                                        <div class="td-ticket--inner">
                                            <div class="christmasTicketImg">
                                                <img src="/w/img/christmas-lottery/desktop/christmas-lottery.png" />
                                                <span class="christmasTicketTxt">{{ ticket['number'] }}</span>
                                            </div>
                                            <div class="td-ticket-buttons--row">
                                                <div class="td-ticket-buttons">
                                                    <input type="button" id="remove_{{ ticket['id'] }}" class="removeTicket"
                                                           value="-"/>
                                                    <span id="showNumTickets_{{ ticket['id'] }}">0</span>
                                                    {#{{ singleBetPrice }}#}
                                                    {#{{ currencySymbol }}#}
                                                    <input type="hidden" id="maxTickets_{{ ticket['id'] }}"
                                                           value="{{ ticket['n_fractions'] }}"/>
                                                    <input type="hidden" id="numTickets_{{ ticket['id'] }}"
                                                           name="numTickets_{{ ticket['id'] }}" value="0"/>

                                                    <input type="button" id="add_{{ ticket['id'] }}" class="addTicket" value="+"/>
                                                </div>
                                                <div class="tickets-available">
                                                    {{ ticket['n_fractions'] }} {{ language.translate("playchris_tck_av") }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
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
                                {{ language.translate("playchris_total") }}
                            </div>
                            <div class="right">
                                <span class="total-price-description">
                                    {#Total price#}
                                </span>
                                <span class="description-before-price">
                                    <span id="showTotalTickets">0</span>
                                </span>

                                <a href="javascript:void(0);" id="nextButton" class="btn add-cart">
                                <span class="value">
                                    <span class="resizeme">{{ currencySymbol }} <span id="totalPriceValue">0.00</span></span>
                                </span>

                                <span class="next-btn"><span class="resizeme">{{ language.translate('next_btn') }}</span></span>
                                </a>
                            </div>

                            <input type="hidden" id="totalTickets" value="0"/>
                            <input type="hidden" id="singleBetPrice"
                                   value="{{ singleBetPrice | number_format (2,'.','') }}"/>
                        </div>
                        {#{% include "_elements/play-bottom-block.volt" %}#}

                        <div class="box-bottom">
                            <p align="justify">
                            <h2 style="font-weight: bold; font-size: 26px;">{{ language.translate("playchris_txt_tit") }}</h2>
                            <br/>
                            {{ language.translate("playchris_txt_1") }}<br/>
                            <br/>
                            <h3 style="font-weight: bold; font-size: 20px;">{{ language.translate("playchris_txt_howto_tit") }}</h3>
                            <br/>
                            {{ language.translate("playchris_txt_howto_1") }}<br/>
                            <br/>
                            <h3 style="font-weight: bold; font-size: 20px;"> {{ language.translate("playchris_txt_what_tit") }}</h3>
                            <br/>
                            <ul style="margin-left: 30px;">
                                {{ language.translate("playchris_txt_what_1") }}
                            </ul>
                            <br/>
                            <h3 style="font-weight: bold; font-size: 20px;">{{ language.translate("playchris_txt_howmany_tit") }}</h3>
                            <br/>
                            {{ language.translate("playchris_txt_howmany_1") }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </main>
{% endblock %}
