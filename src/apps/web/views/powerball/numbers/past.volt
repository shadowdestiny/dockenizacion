{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel {display: none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_powerball_draw_history') }}" />
{% endblock %}
{% block bodyClass %}
    numbers
    {% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "numbers"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
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
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}
    HOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
    <main id="content">
        <div class="result-page--content--previous-result">
            <div class="banner"></div>
            <div class="wrapper">
                <br>
                <div class="content">
                    {% include "_elements/section-powerball.volt" %}

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
                                {{ language.translate("resultshist_pow_h1") }}
                            {% endif %}
                        </h1>
                        <div class="history-numbers-block">
                            <div class="pad">
                                <h2 class="h2 purple">{{ language.translate("resultshist_pow_h2") }}</h2>
                            </div>
                            <table id="history-numbers" class="ui-responsive table2" data-role="table"
                                   data-mode="reflow">
                                <thead class="thead--powerball">
                                <tr>
                                    <th class="td-date"><span class="ico-date"></span>{{ language.translate("pastNumbers_date") }}</th>
                                    <th class="td-ball-numbers--lottery"><span
                                                class="ico-ball"></span>{{ language.translate("resultshist_pow_numbers") }}</th>
                                    <th class="td-powerball">
                                        <span class="ico-ball"></span>
                                        {{ language.translate("resultshist_powerballnumber") }}
                                    </th>
                                    <th class="td-powerplay">
                                        <span class="ico-ball"></span>
                                        Powerplay
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for draw in list_draws %}
                                    <tr class="more" style="cursor: pointer"
                                        onclick="document.location='/{{ language.translate('link_powerball_draw_history') }}/{{ draw.drawDateParam }}'">
                                        <td class="td-date">{{ draw.drawDateParam }}</td>
                                        <td class="td-ball-numbers">{{ draw.resultNumbers }}</td>
                                        <td class="td-powerball">{{ draw.luckyNumber }}</td>
                                        <td class="td-powerplay">{{ draw.powerPlayNumber }}</td>
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
