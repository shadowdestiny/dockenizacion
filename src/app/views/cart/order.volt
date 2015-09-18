{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/cart.css">
{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart order minimal{% endblock %}

{% block header %}
    {% set activeSteps='{"myClass": "step3"}'|json_decode %}
    {% include "_elements/sign-in-header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">

        <div class="box-basic small">
            <div class="my-right">
                <h1 class="h3">A step away from a dream</h1>
                <p>
                    A wine cellar, a pool, a car, a bigger house,
                    <br class="br">a surprise gift for your loved one,
                    <br class="br">a vision of tropical blue waters and white sand.
                </p>
            </div>

            <h1 class="h1 title yellow">{{ language.translate("Place your Order") }}</h1>
            <a href="javascript:void(0)" class="btn blue big purchase">{{ language.translate("Purchase your ticket") }}</a>

            <h2 class="h4 sub-txt">{{ language.translate("Order Summary") }}</h2>

            <div class="box-order">
                <div class="row">
                    <div class="desc">
                        {{ language.translate("Draws") }}
                    </div>
                    <div class="detail">
                        Since 29th of May 2015 for 4 weeks 
                    </div>
                    <div class="extra cl">
                        {# EMTD It need to add DRAW or DRAWS word depending if plural or not #}
                        <span class="summary">8 {{ language.translate("draws") }}</span> 
                        <a class="change" href="javascript:void(0);">{{ language.translate("Change") }}</a>
                    </div>
                </div>
                <div class="row">
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
                    <div class="extra cl">
                        <div class="box-delete">
                            <a class="delete" href="javascript:void(0);">Delete</a>
                        </div>
                        <span class="type">5 {{ language.translate("numbers") }} + 2 {{ language.translate("stars") }}</span>
                        <span class="summary">&euro; 20,00</span>
                    </div>
                </div>
                <div class="row">
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
                    <div class="extra cl">
                        <div class="box-delete">
                            <a class="delete" href="javascript:void(0);">{{ language.translate("Delete") }}</a>
                        </div>
                        <span class="type">5 {{ language.translate("numbers") }} + 3 {{ language.translate("stars") }}</span>
                        <span class="summary">&euro; 70,00</span>
                    </div>
                </div>
                <div class="row">
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
                    <div class="extra cl">
                        <div class="box-delete">
                            <a class="delete" href="javascript:void(0);">{{ language.translate("Delete") }}</a>
                        </div>
                        <span class="type">8 {{ language.translate("numbers") }} + 2 {{ language.translate("stars") }}</span>
                        <span class="summary">&euro; 140,00</span>
                    </div>
                </div>
            </div>
            <div class="box-total cl">
                <span class="txt">{{ language.translate("Order total") }}</span> 
                <span class="total">&euro; 400,00</span>
            </div>

            <div class="box-bottom cl">
                <div class="box-jackpot">
                    <h3 class="h4 yellow current">{{ language.translate("Current jackpot") }}</h3>
                    {% set extraClass='{"boxvalueClass": "","currencyClass":"","valueClass":""}'|json_decode %}
                    {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
                </div>
                <a href="javascript:void(0)" class="btn blue big buy">{{ language.translate("Buy ticket") }}</a>
            </div>

        </div>
    </div>
</main>
{% endblock %}