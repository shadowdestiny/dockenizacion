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

    });
{% endblock %}
{% block body %}
    <main id="content">

        <div class="result-page--content">

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
                        {% for lucky_number in last_result["lucky_numbers"] %}
                            <li class="star">
                                <span class="num">{{ lucky_number }}</span>
                            </li>
                        {% endfor %}
                    </ul>

                    <h3 class="h2 desktop--only">
                        {{ language.translate("lastDraw_title") }}
                    </h3>

                    <h3 class="desktop--only">
                        {{ last_draw_date }}
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
                                    {{ language.translate("results_mobile_h1") }}
                                {% else %}
                                    {{ language.translate("results_tittle") }}
                                {% endif %}
                            </h1>

                            <h2 class="h2">
                                {{ language.translate("prizePool_title") }}
                            </h2>
                            <table id="current-winners" class="table ui-responsive" data-role="table"
                                   data-mode="reflow">
                                <thead><th> </th></thead>
                                <tbody>
                                <tr>
                                    <td class="td-ball">{{ language.translate("prizePool_matches") }}</td>
                                    {#<th class="td-star-ball">{{ language.translate("prizePool_star") }} 2</th>#}
                                    <td class="td-winners">{{ language.translate("prizePool_winners") }}</td>
                                    <td class="td-prize">{{ language.translate("prizePool_prize") }}</td>
                                </tr>
                                {% for name,categories in break_downs %}
                                    <tr>
                                        {% if break_downs[name] is defined %}
                                            <td class="td-ball">
                                                <span>
                                                    {{ categories['numbers_corrected'] }} {{ language.translate("prizePool_ball") }} +
                                                    {{ categories['stars_corrected'] }} {{ language.translate("prizePool_star") }}
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

                                        <a href="/{{ language.translate("link_euromillions_draw_history") }}" class="btn-theme--big">
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
                        {{ language.translate("resultsem_h2") }}
                    </h2>
                    <p>
                        {{ language.translate("resultsem_text") }}
                    </p>
                </div>

            </div>
        </div>
    </main>
{% endblock %}
