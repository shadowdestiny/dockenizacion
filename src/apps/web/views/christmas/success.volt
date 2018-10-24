{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}
cart success minimal
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "success"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>if(window!=top){top.location.href=location.href;}</script>{% endblock %}

{% block body %}
    <div class="wrapper">
        <div class="thank-you-block">
            <div class="thank-you-block--top">
                <h1>{{ language.translate("confirmation_h1") }}</h1>

                <h2>
                    {{ language.translate("confirmation_head", ['value': lottery_name]) }}
                </h2>
                <p>
                    {{ language.translate("confirmation_subhead") }}
                </p>
                {#<div class="countdown">#}
                {##}
                {#</div>#}

                <div class="btn-row">
                    <a href="/{{ language.translate('link_christmas_play') }}" class="btn-theme--big">
                        <span class="resizeme">
                            {{ language.translate("tickets_play_again") }}
                        </span>
                    </a>
                </div>
            </div>


            <div class="thank-you-block--jackpot">
                <p>
                    {{ language.translate("confirmation_lines") }} {{ language.translate(draw_day) }}
                    , {{ draw_date_format }}
                </p>
                <h2>
                    {{ language.translate("tittle") }} {{ currency_symbol }}{{ jackpot_values }} {{ language.translate("billion") }}
                </h2>
            </div>
            <div class="thank-you-block--rows">
                <div class="thank-you-block--row">
                        <br>
                    <p>Christmas Tickets</p>
                    {%  for ticket in christmasTickets %}
                            <p style="font-size: 25px; margin-bottom: 10px;">{{ ticket.getNumber() }}</p>
                    {% endfor %}

                </div>
            </div>
            <div class="thank-you-block--bottom">
                <p>
                    {{ language.translate("paragraph1") }}
                </p>
                <h3>
                    {{ language.translate("win_subhead") }}
                </h3>
                <p>
                    {{ language.translate("win_text",['user_email':'support@euromillions.com ']) }}
                    {#{{ language.translate("win_text") }}#}
                </p>
            </div>

        </div>
    </div>

{% endblock %}
