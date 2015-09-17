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
            <h1 class="h1 title yellow">{{ language.translate("Place your Order") }}</h1>
            <a href="javascript:void(0)" class="btn blue big purchase">Purchase your ticket</a>

            <h2 class="h4 sub-txt">Order Summary</h2>

            <div class="box-order">
                <div class="row">
                    <div class="desc">
                        Draws
                    </div>
                    <div class="detail">
                        starting 29 May 2015 for 4 weeks 
                    </div>
                    <div class="extra">
                        <a class="change" href="javascript:void(0);">change</a>
                        <span class="summary">8 draws</span>
                    </div>
                </div>
                <div class="row">
                    <div class="desc">
                        Line A
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
                    <div class="extra">
                        <a class="delete small" href="javascript:void(0);">delete</a>
                        <span class="type">5 numbers + 2 stars</span>
                        <span class="summary">20,00 &euro;</span>
                    </div>
                </div>
                <div class="row">
                    <div class="desc">
                        Line B
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
                    <div class="extra">
                        <a class="delete small" href="javascript:void(0);">delete</a>
                        <span class="type">5 numbers + 3 stars</span>
                        <span class="summary">40,00 &euro;</span>
                    </div>
                </div>
                <div class="row">
                    <div class="desc">
                        Line C
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
                    <div class="extra">
                        <a class="delete small" href="javascript:void(0);">delete</a>
                        <span class="type">8 numbers + 2 stars</span>
                        <span class="summary">40,00 &euro;</span>
                    </div>
                </div>
            </div>
            <div class="box-total cl">
                <span class="txt">Order total:</span> 
                <span class="total">400,00&euro;</span>
            </div>

            <h3 class="h4 yellow">Current jackpot</h3>

            {% set extraClass='{"boxvalueClass": "","currencyClass":"","valueClass":""}'|json_decode %}
            {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}

            <a href="javascript:void(0)" class="btn blue big">Buy ticket</a>

        </div>
    </div>
</main>
{% endblock %}