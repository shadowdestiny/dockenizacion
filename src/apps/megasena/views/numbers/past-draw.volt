{% extends "../../shared/views/main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_draw_history') }}/{{ date_canonical }}" />
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
    function recalculateDrawDates() {
        $.ajax({
            type: "POST",
            url: '/ajax/date-results/getMegaSenaDrawDaysByDate/',
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

    $('#show-megasena-results').click(function(){
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

    });
{% endblock %}
{% block body %}
    <main id="content">
        <div class="common--result-page--content megasena--result-page--content">

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
                    {{ language.translate("lastDraw_title") }}
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
                    </ul>

                    <span class="desktop--only">
                        {{ language.translate("draw") }}
                    </span>

                    <h3 class="desktop--only">
                        {{ language.translate(draw_day) }}, {{ past_draw_date_format }}
                    </h3>
                </div>

                <div class="content">
                    <div class="right-section">
                        {{ jackpot_widget }}
                    </div>
                    <div class="left-section result-section">

                        <div class="box-current-winners--new">
                            <h1 class="winners--h1">
                                {% if mobile == 1 %}
                                    {{ language.translate("results_pow_mobile_h1") }}
                                {% else %}
                                    {{ language.translate("resultsMS_h1") }}
                                {% endif %}
                            </h1>
                            <h2 class="h2">
                                {{ language.translate("resultsMS_h2") }}
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

                                        {% if break_downs[name]['name'] is defined %}
                                            <td class="td-ball">
                                                <span>
                                                {#TODO : Add real variables here#}
                                                    {{ break_downs[name]['numbers_corrected'] }} {{ language.translate("pastNumbers_ball") }}
                                                </span>
                                            </td>
                                            <td class="td-winners">
                                                <span>
                                                {{ break_downs[name]['winners'] }}x
                                                </span>
                                            </td>
                                            <td class="td-prize">
                                                <span>
                                                {{ symbol }} {{ (break_downs[name]['lottery_prize']/100) | number_format(2, '.', ',') }}
                                                </span>
                                            </td>
                                        {% endif %}
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>

                            <div class="previous-results--common-row">
                                {% include "_elements/previous-results-megasena.volt" %}

                                <div class="previous-results--btn">

                                    <a href="/{{ language.translate("link_megasena_draw_history") }}" class="btn-theme--big">
                                        <span class="resizeme">
                                            {{ language.translate("morePastResults_btn") }}
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
                        {{ language.translate("resultsMS_title_par") }}
                    </h2>
                    <p>
                        {{ language.translate("resultsMS_par") }}
                    </p>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
