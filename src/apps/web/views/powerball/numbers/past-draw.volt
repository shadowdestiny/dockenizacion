{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_draw_history') }}/{{ date_canonical }}" />
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

            <div class="banner">
                <div class="top-banner--section">
                    <div class="top-banner--banner">
                        <div class="wrapper">

                            <h1 class="top-banner-play">
                                {% if mobile == 1 %}
                                    {{ language.translate("resultsdate_mobile_h1") }}
                                {% else %}
                                    {{ language.translate("resultsdate_title") }}
                                {% endif %}
                            </h1>

                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <h3 class="mobile--only">
                    <br />
                    {{ language.translate("draw") }}
                    {{ language.translate(draw_day) }}, {{ past_draw_date_format }}
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
                            </li>
                        {% endfor %}
                    </ul>

                    <span class="desktop--only">
                        {{ language.translate("draw") }}
                    </span>

                    <h3 class="desktop--only">
                        {{ language.translate(draw_day) }}, {{ past_draw_date_format }}
                    </h3>
                </div>

                <div class="content">

                    {% include "_elements/section-powerball.volt" %}

                    <div class="left-section result-section">

                        <div class="box-current-winners--new">
                            <h2 class="h2">
                                {{ language.translate("resultsdate_h2") }}
                                {#Euromillions Results & price breakdown for Tuesday 02 November 2016#}
                            </h2>
                            <table id="current-winners" class="table ui-responsive" data-role="table"
                                   data-mode="reflow">
                                <thead>
                                <th></th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="td-ball" style="font-weight: bold; font-size: 15px;">{{ language.translate("prizePool_matches") }}</td>
                                    <td class="td-winners" style="font-weight: bold; font-size: 15px;">{{ language.translate("prizePool_winners") }}</td>
                                    <td class="td-prize" style="font-weight: bold; font-size: 15px;">{{ language.translate("prizePool_prize") }}</td>
                                </tr>
                                {% for name,categories in break_downs %}
                                    <tr>
                                        {% if break_downs[name] is defined %}
                                            <td class="td-ball">
                                                <span>
                                                {#TODO : Add real variables here#}
                                                    {{ break_downs[name]['numbers_corrected'] }} {{ language.translate("prizePool_ball") }} + {{ break_downs[name]['stars_corrected'] }} {{ language.translate("prizePool_star") }}
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
                                                </span>
                                            </td>
                                            <td class="td-prize">
                                                <span>
                                                {{ symbol }} {{ break_downs[name]['lottery_prize'] | number_format(2, '.', ',') }}
                                                </span>
                                            </td>
                                        {% endif %}
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>

                            <div class="previous-results--common-row">
                                {% include "_elements/previous-results-euromillions.volt" %}

                                <div class="previous-results--btn">

                                    <a href="/{{ language.translate('link_euromillions_results') }}" class="btn-theme--big">
                                        <span class="resizeme">
                                            {{ language.translate("resultsdate_btn") }}
                                        </span>
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
