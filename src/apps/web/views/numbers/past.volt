{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel {display: none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_draw_history') }}" />
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

                    <h1 class="h1 title">{{ language.translate("resultshist_title") }}</h1>
                        <div class="history-numbers-block">
                            <div class="pad">
                                <h2 class="h2 purple">{{ language.translate("historyNumbers_title") }}</h2>
                            </div>
                            <table id="history-numbers" class="ui-responsive table2" data-role="table"
                                   data-mode="reflow">
                                <thead>
                                <tr>
                                    <th class="td-date">{{ language.translate("pastNumbers_date") }}</th>
                                    <th class="td-ball-numbers">{{ language.translate("pastNumbers_ball") }} <span
                                                class="ball"></span></th>
                                    <th class="td-star-numbers">
                                        {{ language.translate("pastNumbers_star") }}
                                        <span class="star-ball"></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for i,draw in list_draws %}
                                    <tr style="cursor: pointer"
                                        onclick="document.location='/{{ language.translate('link_euromillions_draw_history') }}/{{ draw.drawDateParam }}'">
                                        <td class="td-date">{{ draw.drawDate }}, {{ draw.drawDateTranslate }}</td>
                                        <td class="td-ball-numbers">{{ draw.regularNumbers }}</td>
                                        <td class="td-star-numbers">{{ draw.luckyNumbers }}</td>
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
        </div>
    </main>
{% endblock %}
