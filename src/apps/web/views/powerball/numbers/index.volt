{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]>
    <style>.laurel {
        display: none;
    }</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_powerball_results') }}"/>
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
    url: '/ajax/date-results/getPowerballDrawDaysByDate/',
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
<script language="javascript">
    function showPowerPlay() {
        var checkBox = document.getElementById("powerPlayCheck");
        if (checkBox.checked == true) {
            $('.td-winners--powerplay').css('display', 'block');
            $('.td-prize--powerplay').css('display', 'block');
            $('.td-prize').css('display', 'none');
            $('.td-winners').css('display', 'none');
        } else {
            $('.td-winners--powerplay').css('display', 'none');
            $('.td-prize--powerplay').css('display', 'none');
            $('.td-prize').css('display', 'block');
            $('.td-winners').css('display', 'block');
        }

    }
</script>
    <main id="content">

        <div class="powerball--result-page--content">

            <div class="banner">

                <div class="top-banner--section">

                    <div class="top-banner--banner">
                        <div class="wrapper">
                            {#<h1 class="top-banner--head">#}
                                {#{% if mobile == 1 %}#}
                                    {#{{ language.translate("results_mobile_h1") }}#}
                                {#{% else %}#}
                                    {#{{ language.translate("results_tittle") }}#}
                                {#{% endif %}#}
                            {#</h1>#}
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <h3 class="h2 mobile--only">
                    {{ language.translate("lastDraw_title") }}
                </h3>

                <h3 class="mobile--only">
                    {{ last_draw_date }}
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
                            <li class="star">
                                <span class="num">{{ last_result["lucky_numbers"][1] }}</span>
                            </li>
                        <li class="star">
                            <span class="num">{{ last_result["power_play"] }}</span>
                        </li>
                    </ul>

                    <h3 class="h2 desktop--only">
                        {{ language.translate("lastDraw_title") }}
                    </h3>

                    <h3 class="desktop--only">
                        {{ last_draw_date }}
                    </h3>
                </div>

                <div class="content">

                    {% include "_elements/section-powerball.volt" %}

                    <div class="left-section result-section">

                        <div class="box-current-winners--new">

                            <h1 class="winners--h1">
                                {% if mobile == 1 %}
                                    {{ language.translate("results_pow_mobile_h1") }}
                                {% else %}
                                    {{ language.translate("results_pow_h1") }}
                                {% endif %}
                            </h1>

                            <h2 class="h2">
                                {{ language.translate("results_pow_h2") }}
                            </h2>
                            <table id="current-winners" class="table ui-responsive" data-role="table"
                                   data-mode="reflow">
                                <thead>
                                 <th>
                                 </th>
                                 </thead>
                                <tbody>
                                <tr>
                                    <td class="td-ball td-head">{{ language.translate("prizePool_matches") }}</td>
                                    <td class="td-winners td-head">{{ language.translate("prizePool_winners") }}</td>
                                    <td class="td-prize td-head">{{ language.translate("prizePool_prize") }}</td>
                                    <td class="td-winners--powerplay td-head">{{ language.translate("powerplay_winners") }}</td>
                                    <td class="td-prize--powerplay td-head">{{ language.translate("powerplay_prizes") }}</td>

                                </tr>
                                {% for i,line in break_downs %}
                                    <tr>
                                            <td class="td-ball">
                                                <span>
                                                    {{ line['name'] }} {{ language.translate("prizePool_ball") }} +  {{ language.translate("Powerball") }}
                                                </span>
                                            </td>
                                            {#<td class="td-star-ball">#}
                                            {#{% if break_downs[name]['stars_corrected'] > 0 %}#}
                                            {#{% for corrected_stars in 1..break_downs[name]['stars_corrected'] %}#}
                                            {#<span class="star-ball"></span>#}
                                            {#{% endfor %}#}
                                            {#{% endif %}#}
                                            {#</td>#}
                                            <td class="td-winners">
                                                <span>
                                                {{ line['winnersPowerBall'] }}x
                                                </span>
                                            </td>
                                            <td class="td-prize">
                                                <span>
                                                  {{ symbol }} {{ line['powerBallPrize'] | number_format(2, '.', ',') }}
                                                </span>
                                            </td>
                                            <td class="td-winners--powerplay">
                                            {% if i == 'lineOne' %}
                                                <span></span>
                                            {% else %}
                                                <span>
                                                {{ line['winnersPowerPlay'] }}x
                                                </span>
                                            {% endif %}
                                            </td>
                                            <td class="td-prize--powerplay">
                                            {% if i == 'lineOne' %}
                                                <span></span>
                                            {% else %}
                                                <span>
                                                    {{ symbol }} {{ line['powerPlayPrize'] | number_format(2, '.', ',') }}
                                                </span>
                                             {% endif %}
                                            </td>
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>


                            <div class="see-results-block">
                                <form action="">

                                    <label for="see">
                                        <input id="powerPlayCheck" type="checkbox" onClick="showPowerPlay();"/>

                                        {{ language.translate("powerplay_show") }}

                                    </label>
                                </form>
                            </div>


                            <div class="previous-results--common-row">
                                {% include "_elements/previous-results-powerball.volt" %}

                                <div class="previous-results--btn">

                                    <a href="/{{ language.translate("link_powerball_draw_history") }}" class="btn-theme--big">
                                        <span class="resizeme">
                                            {{ language.translate("powhistory_btn") }}
                                        </span>
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bottom--banner"></div>

                <div class="block--text--accordion">
                    <h2>
                        {{ language.translate("resultspow_h2") }}
                    </h2>
                    <p>
                        {{ language.translate("resultspow_text") }}
                    </p>
                </div>

            </div>
        </div>
    </main>
{% endblock %}
