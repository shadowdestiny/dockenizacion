{% extends "../../shared/views/main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel {display: none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_megamillions_draw_history') }}" />
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
        <div class="result-page--content--previous-result superenalotto-previous-results">
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
                                {{ language.translate("resultsSE_hist_h1") }}
                            {% else %}
                                {{ language.translate("resultsSE_hist_h1") }}
                            {% endif %}
                        </h1>
                        <div class="history-numbers-block">
                            <div class="pad">
                                <h2 class="h2 purple">{{ language.translate("resultsSE_hist_h2") }}</h2>
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
                                    <thead class="thead--superenalotto">
                                    <tr>
                                        <th class="td-date"><span class="ico-date"></span>
                                            {{ language.translate("resultsSE_hist_date") }}
                                        </th>
                                        <th class="td-ball-numbers--lottery superenalotto--lottery-balls">
                                            <span class="ico-ball">
                                            </span>
                                            {{ language.translate("resultsSE_hist_balls") }}
                                        </th>
                                        <th class="td-ball-numbers--lottery superenalotto--lottery-balls">
                                            <span class="ico-ball jolly"></span>
                                            Jolly
                                        </th>
                                    </tr>
                                    </thead>
                                {% endif %}
                                <tbody>
                                {% for draw in list_draws %}
                                    <tr class="more" style="cursor: pointer"
                                        onclick="document.location='/{{ language.translate('link_superenalotto_draw_history') }}/{{ draw.drawDateParam }}'">
                                        <td class="td-date">{{ draw.drawDate }}, {{ draw.drawDateTranslate }}</td>
                                        <td class="td-ball-numbers">{{ draw.resultNumbers }}</td>
                                        <td class="td-ball-numbers">
                                            <?php
                                                $jolly = explode(",",$draw->luckyNumber);
                                                if($jolly)
                                                    print $jolly[0];
                                            ?>
                                        </td>
                                    </tr>
                                {% endfor %}
                                </tbody>


                            </table>

                            <div id="show-more-results">
                                <div class="btn-theme--big">
                                <span class="resizme">
                                    {{ language.translate("resultsSE_hist_loadbtn") }}
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
