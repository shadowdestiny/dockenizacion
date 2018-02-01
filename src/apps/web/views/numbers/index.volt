{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]>
    <style>.laurel {
        display: none;
    }</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_results') }}"/>
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
    <script src="/w/js/mobileFix.js"></script>
{% endblock %}
{% block template_scripts_code %}
    function recalculateDrawDates() {
        $.ajax({
            type: "POST",
            url: '/ajax/date-results/getDrawDaysByDate/',
            data: {
                month: $('#month option:selected').val(),
                year: $('#year option:selected').val()
            },
            dataType:"json",
            success: function (response) {
                var $selectDay = $('#day');
                $('#day option').remove();
                response.forEach(function(element){
                    $selectDay.append('<option value="/' + element["url"] + '">' + element["day"] + ' ' + element["name"] + '</option>');
                });
            }
        });
    }

    $(function(){

    recalculateDrawDates();

    $('#year,#month').change(function(){
        recalculateDrawDates();
    });

    $('#show-results').click(function(){
        var date = new Date($('#day').val().substr(-10,10));
        var actualDate = new Date();
        if (actualDate < date) {
            alert ("We don't have results for this date");
        } else {
            location.href = $('#day').val();
        }
    });

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
    var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}
    <main id="content">

        <div class="result-page--content">

            <div class="banner"></div>
            <div class="wrapper">
                {#<h1 class="h1 title">{{ language.translate("results_tittle") }}</h1>#}

                <h2 class="h2 mobile--only">
                    {#TODO : Add real variables here#}

                    {#<span class="purple">#}
                    {#{{ language.translate("lastDraw_title") }}#}
                    {#</span> #}
                    {{ last_draw_date }}
                    {#Tuesday 28#}

                </h2>

                <h3 class="mobile--only">
                    {#TODO : Add real variables here#}
                    February 2017
                </h3>


                <div class="title-block">

                    <ul class="no-li inline numbers">
                        {% for index,regular_number in last_result["regular_numbers"] %}
                            {% if index == '0' %}
                                <li>
                                    <span class="num">{{ regular_number }}</span>
                                </li>
                            {% else %}
                                <li>
                                    <span class="num">{{ regular_number }}</span>
                                </li>
                            {% endif %}
                        {% endfor %}
                        {% for lucky_number in last_result["lucky_numbers"] %}
                            <li class="star">
                                <span class="num">{{ lucky_number }}</span>
                                {#<span class="txt">{{ language.translate("starBall") }}</span>#}
                            </li>
                        {% endfor %}
                    </ul>

                    <h2 class="h2 desktop--only">
                        {#TODO : Add real variables here#}

                        {#<span class="purple">#}
                        {#{{ language.translate("lastDraw_title") }}#}
                        {#</span> #}
                        {{ last_draw_date }}
                        {#Tuesday 28#}

                    </h2>

                    <h3 class="desktop--only">
                        {#TODO : Add real variables here#}
                        February 2017
                    </h3>


                    {#<span class="grey left">{{ language.translate("draw") }} {{ id_draw }}</span>#}


                    {#<div class="col4">#}
                    {#<div class="box-estimated">#}
                    {#<div class="laurel first">#}
                    {#<svg class="vector">#}
                    {#<use xlink:href="/w/svg/icon.svg#laurel"></use>#}
                    {#</svg>#}
                    {#</div>#}
                    {#<div class="laurel last">#}
                    {#<svg class="vector">#}
                    {#<use xlink:href="/w/svg/icon.svg#laurel"></use>#}
                    {#</svg>#}
                    {#</div>#}
                    {#<div class="bg">#}
                    {#<a href="/{{ language.translate("link_euromillions_play") }}" class="content">#}
                    {#<h1 class="h3">{{ language.translate("nextDraw_Estimate") }}</h1>#}

                    {#{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}#}
                    {#{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}#}

                    {#<div class="box-next-draw cl">#}
                    {#<div class="countdown">#}
                    {#<span class="next-draw">#}
                    {#<span class="txt-one">{{ language.translate("Next") }}</span>#}
                    {#<br class="br">{{ language.translate("Draw") }}#}
                    {#</span>#}
                    {#<div class="day unit">#}
                    {#<span class="val">%-d</span>#}
                    {#<span class="txt">{{ language.translate("nextDraw_day") }}%!d</span>#}
                    {#</div>#}
                    {#<div class="dots">:</div>#}
                    {#<div class="hour unit">#}
                    {#<span class="val">%-H</span>#}
                    {#<span class="txt">{{ language.translate("nextDraw_hr") }}</span>#}
                    {#</div>#}
                    {#<div class="dots">:</div>#}
                    {#<div class="minute unit">#}
                    {#<span class="val">%-M</span>#}
                    {#<span class="txt">{{ language.translate("nextDraw_min") }}</span>#}
                    {#</div>#}
                    {#<div class="dots">:</div>#}
                    {#<div class="seconds unit">#}
                    {#<span class="val">%-S</span>#}
                    {#<span class="txt">sec</span>#}
                    {#</div>#}
                    {#</div>#}
                    {#<span class="btn red big right">{{ language.translate("nextDraw_btn") }}</span>#}
                    {#</div>#}
                    {#</a>#}
                    {#</div>#}
                    {#</div>#}

                    {#</div>#}
                </div>

                <div class="content">

                    {% include "_elements/section-powerball.volt" %}

                    <div class="left-section result-section">

                        <div class="box-current-winners--new">
                            <h1 class="h2">
                                {#TODO : Add real variables here#}
                                {{ language.translate("prizePool_title") }}
                                {#Euromillions Results & price breakdown for Tuesday 02 November 2016#}
                            </h1>
                            <table id="current-winners" class="table ui-responsive" data-role="table"
                                   data-mode="reflow">
                                <thead>
                                <tr>
                                    <th class="td-ball">{{ language.translate("prizePool_ball") }}</th>
                                    <th class="td-star-ball">{{ language.translate("prizePool_star") }}</th>
                                    <th class="td-winners">{{ language.translate("prizePool_winners") }}</th>
                                    <th class="td-prize">{{ language.translate("prizePool_prize") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for name,categories in break_downs %}
                                    <tr>
                                        {% if break_downs[name] is defined %}
                                            <td class="td-ball">
                                                <span>
                                                {#{% for corrected_numbers in 1..break_downs[name]['numbers_corrected'] %}#}
                                                    {#<span class="ball"></span>#}
                                                    {#{% endfor %}#}

                                                    {#TODO : Add real variables here#}
                                                    5 Numbers + 2 Starts
                                                            </span>
                                            </td>
                                            <td class="td-star-ball">
                                                {% if break_downs[name]['stars_corrected'] > 0 %}
                                                    {% for corrected_stars in 1..break_downs[name]['stars_corrected'] %}
                                                        <span class="star-ball"></span>
                                                    {% endfor %}
                                                {% endif %}
                                            </td>
                                            <td class="td-winners">
                                                <span>
                                                {{ break_downs[name]['winners'] }}x

                                                    {#15x#}
                                                        </span>
                                            </td>
                                            <td class="td-prize">
                                                <span>
                                                {{ symbol }} {{ break_downs[name]['lottery_prize'] | number_format(2, '.', ',') }}

                                                    {#{{ symbol }}120,000,000#}
                                                        </span>
                                            </td>
                                        {% endif %}
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>

                            <div class="previous-results mobile--only">
                                <div class="btn-line">
                                    <a href="/euromillions/results/draw-history-page" class="btn-theme--big">
                                        Previous results
                                    </a>
                                </div>
                            </div>

                            {% include "_elements/previous-results-euromillions.volt" %}

                        </div>


                        {#<div class="col4">#}
                        {#<div class="box-history">#}
                        {#</div>#}
                        {#</div>#}

                        {#<div class="cols bottom">#}
                        {#<div class="col8">#}

                        {#<div class="box-basic">#}
                        {#<div class="pad">#}
                        {#<h1 class="h2 purple">{{ language.translate("pastNumbers_title") }}</h1>#}
                        {#</div>#}
                        {#<table id="history-numbers" class="ui-responsive table2" data-role="table"#}
                        {#data-mode="reflow">#}
                        {#<thead>#}
                        {#<tr>#}
                        {#<th class="td-date">{{ language.translate("pastNumbers_date") }}</th>#}
                        {#<th class="td-ball-numbers">{{ language.translate("pastNumbers_ball") }}#}
                        {#<span#}
                        {#class="ball"></span></th>#}
                        {#<th class="td-star-numbers">#}
                        {#{{ language.translate("pastNumbers_star") }}#}
                        {#<span class="star-ball"></span>#}
                        {#</th>#}
                        {#</tr>#}
                        {#</thead>#}
                        {#<tbody>#}
                        {#{% for i,draw in list_draws %}#}
                        {#<tr style="cursor: pointer"#}
                        {#onclick="document.location='/{{ language.translate('link_euromillions_draw_history') }}/{{ draw.drawDateParam }}'">#}
                        {#<td class="td-date">{{ draw.drawDate }}</td>#}
                        {#<td class="td-ball-numbers">{{ draw.regularNumbers }}</td>#}
                        {#<td class="td-star-numbers">{{ draw.luckyNumbers }}</td>#}
                        {#</tr>#}
                        {#{% endfor %}#}
                        {#</tbody>#}
                        {#</table>#}
                        {#<div class="box-action">#}
                        {#<a href="/{{ language.translate('link_euromillions_draw_history') }}"#}
                        {#class="btn  green big wide ui-link">{{ language.translate("morePastResults_btn") }}</a>#}
                        {#</div>#}
                        {#</div>#}
                        {#</div>#}
                        {#<div class="col4">#}{# nothing here #}{#</div>#}
                        {#</div>#}
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
