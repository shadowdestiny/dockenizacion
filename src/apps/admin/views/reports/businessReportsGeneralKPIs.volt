{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>Reports Customer data - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <script language="javascript">
        $(function () {
            $("#dateFrom").datepicker();
            $("#dateTo").datepicker();

            $("#generalKPI").click(function(){
                if (!$('input[name=groupBy]:checked').val() || !$("#dateFrom").val() || !$("#dateTo").val()) {
                    alert('You must fill in dates and group by');
                } else {
                    $.ajax({
                        url: 'businessReportsGeneralKPIs',
                        type: 'POST',
                        data: {
                            dateFrom: $("#dateFrom").val(),
                            dateTo: $("#dateTo").val(),
                            countries: $("#countries").val(),
                            groupBy: $('input[name=groupBy]:checked').val(),
                        },
                        success: function(data) {
                            $('#resultsActivity').html(data);
                        },
                        error: function() {
                            alert('Something went wrong, please try again.');
                        },
                    });
                }
            });
        });

        $(document).ready(function(){
            $(".index").click(function(){
                $(".values").fadeToggle();
            });
        });

    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Business Reports - General KPIs</h1>
                    <form>
                        <table>
                            <tr>
                                <td>
                                    <b>Date:</b> From <input type="text" name="dateFrom" id="dateFrom" /> To <input type="text" name="dateTo" id="dateTo" />
                                </td>
                                <td>
                                    <b>Country</b>
                                    <select name="countries[]" id="countries" multiple size="5" style="width: 300px;">
                                        {% for key, country in countryList %}
                                            <option value="{{ key }}">{{ country }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <b>Group by: </b>
                                    <input type="radio" name="groupBy" value="day" /> Day &nbsp;
                                    <input type="radio" name="groupBy" value="month" /> Month &nbsp;
                                    <input type="radio" name="groupBy" value="year" /> Year
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="button" value="Show" class="btn btn-primary" id="generalKPI" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div id="resultsActivity">
                        {#{% if playersList is not empty %}#}
                        <table class="table-program" width="100%" id="tableExport">
                            <thead>
                            <tr class="special">
                                <th>Date</th>
                                <th>Registrations</th>
                                <th>New Depositors</th>
                                <th>Conversion</th>
                                <th>Actives</th>
                                <th>Number Of Bets</th>
                                <th>Total Bets</th>
                                <th>Number of Deposits</th>
                                <th>Deposit Amount</th>
                                <th>Number Withdrawals</th>
                                <th>Withdrawal Amount</th>
                                <th>Player Winnings</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for date in arrayDates %}
                                <tr>
                                    <td class="index">{{ date }}</td>
                                    {% for key, kpi in generalKPIs[date] %}
                                        <tr>
                                            <td class="values">{{ countryList[key] }}</td>
                                            <td class="values">{% if kpi['newRegistrations'] is defined %}{{  kpi['newRegistrations'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['newDepositors'] is defined %}{{  kpi['newDepositors'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['conversion'] is defined %}{{  kpi['conversion'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['actives'] is defined %}{{  kpi['actives'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['numberBets'] is defined %}{{  kpi['numberBets'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['totalBets'] is defined %}{{  kpi['totalBets'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['numberDeposits'] is defined %}{{  kpi['numberDeposits'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['depositAmount'] is defined %}{{  kpi['depositAmount'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['numberWithdrawals'] is defined %}{{  kpi['numberWithdrawals'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['withdrawalAmount'] is defined %}{{  kpi['withdrawalAmount'] }}{% else %}0{% endif %}</td>
                                            <td class="values">{% if kpi['playerWinnings'] is defined %}{{  kpi['playerWinnings'] }}{% else %}0{% endif %}</td>
                                        </tr>
                                    {% endfor %}
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}