{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart order minimal{% endblock %}

{% block header %}
    {% include "_elements/minimal-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
{#  Hide this content until we have multiple numbers 
    <span class="type">5 {{ language.translate("numbers") }} + 3 {{ language.translate("stars") }}</span>
#}

<main id="content">
    <div class="wrapper">

        <div class="box-basic medium">
            <h1 class="h1 title yellow res">{{ language.translate("Shopping cart") }}</h1>
            <div class="terms">By purchasing you agree to <a href="#">Terms &amp; Conditions</a></div>

            <h2 class="h4 sub-txt">{{ language.translate("Order Summary") }}</h2>

            <div class="box-order">
                <div class="row cl">
                    <div class="desc">
                        {{ language.translate("Draws") }}
                    </div>
                    <div class="detail">
                        {{ language.translate("Since 29 May 2015 for 4 weeks") }}
                    </div>

                    {# EMTD It need to add DRAW or DRAWS word depending if plural or not #}
                    <div class="right">
                        <a class="change" href="javascript:void(0);">{{ language.translate("Change") }}</a>
                        <div class="val summary">8 {{ language.translate("draws") }}</div>
                    </div>
                </div>
                <div class="row cl">
                    <div class="desc">
                        {{ language.translate("Line") }} A
                    </div>
                    <div class="detail">
                        <ol class="no-li num">
                            <li>04</li>
                            <li>14</li>
                            <li>21</li>
                            <li>36</li>
                            <li>38</li>
                            <li class="yellow">07</li>
                            <li class="yellow">10</li>
                        </ol> 
                    </div>
                    <div class="summary val">&euro; 20,00</div>
                </div>
                <div class="row cl">
                    <div class="desc">
                        {{ language.translate("Line") }} B
                    </div>
                    <div class="detail">
                        <ol class="no-li num">
                            <li>05</li>
                            <li>17</li>
                            <li>19</li>
                            <li>31</li>
                            <li>45</li>
                            <li class="yellow">03</li>
                            <li class="yellow">04</li>
                            <li class="yellow">09</li>
                        </ol> 
                    </div>
                    <div class="summary val">&euro; 70,00</div>
                </div>
                <div class="row cl">
                    <div class="desc">
                        {{ language.translate("Line") }} C
                    </div>
                    <div class="detail">
                        <ol class="no-li num">
                            <li>02</li>
                            <li>12</li>
                            <li>17</li>
                            <li>19</li>
                            <li>27</li>
                            <li>36</li>
                            <li>42</li>
                            <li>44</li>
                            <li class="yellow">05</li>
                            <li class="yellow">11</li>
                        </ol> 
                    </div>
                    <div class="summary val">&euro; 140,00</div>
                </div>
                <div class="row cl">
                    <div class="txt-fee">
                        {{ language.translate("Fee for transactions below") }} &euro; 12,00
                    </div>
                    <div class="right tweak">
                        <div class="summary val">&euro; 0,35</div>
                        <div class="box-funds cl">
                            <a class="add-funds" href="javascript:void(0)">{{ language.translate("Add Funds to avoid charges") }}</a><br>
                            <span class="combo">&euro;</span><input class="input" type="text" placeholder='{{ language.translate("Insert an ammount")}}' value="">
                        </div>
                    </div>
                </div>
                <div class="row cl">
                    <div class="summary val">&euro; -25,00</div>
                    <div class="box-wallet cl">
                        <label for="pay-wallet" class="txt">Pay with your Wallet balance</label>
                        <input id="pay-wallet" type="checkbox" class="checkbox" checked>
                    </div>
                </div>
            </div>
            <div class="box-total cl">
                <div class="txt-currency desktop">
                    {{ language.translate("Currencies are just informative, transactions are charged in Euros.") }}
                </div>

                <div class="total">
                    <div class="txt">{{ language.translate("Total") }}</div><div class="val">&euro; 400,00</div>
                </div>
            </div>

            <div class="box-bottom cl">

                <a href="javascript:void(0)" class="btn blue big buy">{{ language.translate("Continue to Payment") }}</a>
            </div>

        </div>
    </div>
</main>
{% endblock %}