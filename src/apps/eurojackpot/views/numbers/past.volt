{% extends "../../shared/views/main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel {display: none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_eurojackpot_draw_history') }}" />
{% endblock %}
{% block bodyClass %}
    numbers
    {% include "../../shared/views/_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "numbers"}'|json_decode %}
    {% include "../../shared/views/_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "../../shared/views/_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
    $(function(){
    var html_formatted_offset = [];
    $('.countdown .dots').eq(2).hide();
    $('.countdown .seconds').hide();
    var element = $('.countdown');
    var html_formatted = element.html();
    $('.countdown .dots').eq(2).show();
    $('.countdown .seconds').show();
    $('.countdown .day').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[0] = $('.countdown').html();
    $('.countdown .hour').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[1] = $('.countdown').html();
    $('.countdown .minute').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[2] = $('.countdown').html();
    var finish_action = function(){
    $('.box-next-draw .btn.red').remove();
    }
    var date = '{{ date_draw }}'; {# To test "2015/11/17 10:49:00"  #}
    var finish_text = "div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    });
{% endblock %}
{% block body %}
    <main id="content">
        <div class="result-page--content--previous-result">
            <div class="banner"></div>
            <div class="wrapper">
                <br>
                <div class="content">
                    <div class="right-section">
                        {{ jackpot_widget }}
                    </div>

                    <div class="left-section result-section">

                        {#<div class="jackpot--mobile">#}
                        {#<div class="jackpot--row">#}
                        {#Jackpot €73,000,000#}
                        {#</div>#}
                        {#<div class="next--row">#}
                        {#Next draw \ 21hr : 12min : 33sec#}
                        {#</div>#}
                        {#<div class="btn--row">#}
                        {#<a href="/{{ language.translate("link_euromillions_play") }}" class="btn-theme--big">#}
                        {#Play now#}
                        {#</a>#}
                        {#</div>#}
                        {#</div>#}
                        <h1 class="h1 title">
                            {% if mobile == 1 %}
                                {{ language.translate("resultshist_pow_mobile_h1") }}
                            {% else %}
                                {{ language.translate("EURO JACKPOT HISTORY: ALL PAST RESULTS") }}
                            {% endif %}
                        </h1>
                        <div class="history-numbers-block">
                            <div class="pad">
                                <h2 class="h2 purple">{{ language.translate("resultshist_pow_h2") }}</h2>
                            </div>
                            <table id="history-numbers" class="ui-responsive table2" data-role="table"
                                   data-mode="reflow" style="margin-top:-20px">
                                {% if mobile == 1 %}
                                    <thead class="thead--powerball" style="height:60px">
                                    <tr style="height:60px">
                                        <th class="td-powerball" style="width:40%">
                                        </th>
                                        <th class="td-powerball" style="width:40%">
                                                {{ language.translate("Lucky Numbers") }}
                                        </th>
                                    </tr>
                                    </thead>
                                 {% else %}
                                    <thead class="thead--powerball">
                                    <tr>
                                        <th class="td-date"><span class="ico-date"></span>{{ language.translate("pastNumbers_date") }}</th>
                                        <th class="td-ball-numbers--lottery megamillions--lottery-balls"><span
                                                    class="ico-ball"></span>{{ language.translate("resultshist_pow_numbers") }}</th>
                                        <th class="td-powerball megamillions--megaball">
                                            <span class="ico-ball"></span>
                                                {{ language.translate("Lucky Numbers") }}
                                        </th>
                                    </tr>
                                    </thead>
                                {% endif %}
                                <tbody>
                                {% for draw in list_draws %}
                                    <tr class="more" style="cursor: pointer"
                                        onclick="document.location='/eurojackpot/results/draw-history/{{ draw.drawDateParam }}'">
                                        <td class="td-date">{{ draw.drawDate }}, {{ draw.drawDateTranslate }}</td>
                                        <td class="td-ball-numbers">{{ draw.regularNumbers }}</td>
                                        <td class="td-powerball">{{ draw.luckyNumbers }}</td>
                                    </tr>
                                {% endfor %}
                                </tbody>


                            </table>

                            <div id="show-more-results">
                                <div class="btn-theme--big">
                                <span class="resizme">
                                    {{ language.translate("loadmore_btn") }}
                                </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
