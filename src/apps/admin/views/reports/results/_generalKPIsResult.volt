<script language="javascript">
    $(document).ready(function () {
        $(".index").click(function () {
            $(".values").fadeToggle();
        });
    });
    $(function () {
        $("#export").click(function(){
            $("#tableExport").tableToCSV();
        });
    });
</script>
{#{% if playersList is not empty %}#}
<table class="table-program" width="100%" id="tableExport">
    &nbsp; <input type="button" value="Download" id="export" class="btn btn-primary" />
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
        <th>Gross Gaming</th>
        <th>ARPU</th>
    </tr>
    </thead>
    <tbody>
    {% for date in arrayDates %}
        <tr>
        <td class="index" width="190px;">{{ date }}</td>
        <td>{% if arrayTotals[date]['newRegistrations'] is defined %}{{ arrayTotals[date]['newRegistrations'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['newDepositors'] is defined %}{{ arrayTotals[date]['newDepositors'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['newDepositors'] is defined and arrayTotals[date]['newRegistrations'] is defined %}{{ (arrayTotals[date]['newDepositors'] / arrayTotals[date]['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
        <td>{% if arrayTotals[date]['actives'] is defined %}{{ arrayTotals[date]['actives'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['numberBets'] is defined %}{{ arrayTotals[date]['numberBets'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['totalBets'] is defined %}{{ arrayTotals[date]['totalBets'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['numberDeposits'] is defined %}{{ arrayTotals[date]['numberDeposits'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['depositAmount'] is defined %}{{ arrayTotals[date]['depositAmount'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['numberWithdrawals'] is defined %}{{ arrayTotals[date]['numberWithdrawals'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['withdrawalAmount'] is defined %}{{ arrayTotals[date]['withdrawalAmount'] /100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['playerWinnings'] is defined %}{{ arrayTotals[date]['playerWinnings'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['grossGaming'] is defined %}{{ arrayTotals[date]['grossGaming'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['grossGaming'] is defined and arrayTotals[date]['actives'] is defined %}{{ ((arrayTotals[date]['grossGaming']/100) / arrayTotals[date]['actives']) | number_format (2,',','') }}{% else %}0{% endif %}</td>
        {% for key, kpi in generalKPIs[date] %}
            <tr>
                <td class="values">{{ countryList[key] }}</td>
                <td class="values">{% if kpi['newRegistrations'] is defined %}{{ kpi['newRegistrations'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['newDepositors'] is defined %}{{ kpi['newDepositors'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['newDepositors'] is defined and kpi['newRegistrations'] is defined %}{{ (kpi['newDepositors'] / kpi['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
                <td class="values">{% if kpi['actives'] is defined %}{{ kpi['actives'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['numberBets'] is defined %}{{ kpi['numberBets'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['totalBets'] is defined %}{{ kpi['totalBets'] /100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['numberDeposits'] is defined %}{{ kpi['numberDeposits'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['depositAmount'] is defined %}{{ kpi['depositAmount'] / 100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['numberWithdrawals'] is defined %}{{ kpi['numberWithdrawals'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['withdrawalAmount'] is defined %}{{ kpi['withdrawalAmount'] / 100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['playerWinnings'] is defined %}{{ kpi['playerWinnings'] / 100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['grossGaming'] is defined %}{{ kpi['grossGaming'] / 100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['grossGaming'] is defined and kpi['actives'] is defined%}{{ ((kpi['grossGaming']/100) / kpi['actives']) | number_format (2,',','') }}{% else %}0{% endif %}</td>
            </tr>
        {% endfor %}
        </tr>
    {% endfor %}
    <tr>
        <td>TOTAL</td>
        <td>{% if total['newRegistrations'] is defined %}{{ total['newRegistrations'] }}{% else %}0{% endif %}</td>
        <td>{% if total['newDepositors'] is defined %}{{ total['newDepositors'] }}{% else %}0{% endif %}</td>
        <td>{% if total['newRegistrations'] is defined and total['newDepositors'] is defined%}{{ (total['newDepositors'] / total['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
        <td>{% if valueTotalManualAlternativeQueryForOneValue is defined %}{{ valueTotalManualAlternativeQueryForOneValue }}{% else %}0{% endif %}</td>
        <td>{% if total['numberBets'] is defined %}{{ total['numberBets'] }}{% else %}0{% endif %}</td>
        <td>{% if total['totalBets'] is defined %}{{ total['totalBets']  / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['numberDeposits'] is defined %}{{ total['numberDeposits'] }}{% else %}0{% endif %}</td>
        <td>{% if total['depositAmount'] is defined %}{{ total['depositAmount']  / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['numberWithdrawals'] is defined %}{{ total['numberWithdrawals'] }}{% else %}0{% endif %}</td>
        <td>{% if total['withdrawalAmount'] is defined %}{{ total['withdrawalAmount'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['playerWinnings'] is defined %}{{ total['playerWinnings'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['grossGaming'] is defined %}{{ total['grossGaming'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['grossGaming'] is defined and total['actives'] is defined%}{{ ((total['grossGaming']/100) / total['actives']) | number_format (2,',','') }}{% else %}0{% endif %}</td>


    </tr>
    </tbody>
</table>