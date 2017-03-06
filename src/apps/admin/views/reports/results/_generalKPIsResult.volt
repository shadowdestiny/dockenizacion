<script language="javascript">
    $(document).ready(function () {
        $(".index").click(function () {
            $(".values").fadeToggle();
        });
    });
</script>
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
        <td>{% if arrayTotals[date]['newRegistrations'] is defined %}{{ arrayTotals[date]['newRegistrations'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['newDepositors'] is defined %}{{ arrayTotals[date]['newDepositors'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['newDepositors'] is defined and arrayTotals[date]['newRegistrations'] is defined %}{{ (arrayTotals[date]['newDepositors'] / arrayTotals[date]['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
        <td>{% if arrayTotals[date]['actives'] is defined %}{{ arrayTotals[date]['actives'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['numberBets'] is defined %}{{ arrayTotals[date]['numberBets'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['totalBets'] is defined %}{{ arrayTotals[date]['totalBets'] / 100 }}€{% else %}0€{% endif %}</td>
        <td>{% if arrayTotals[date]['numberDeposits'] is defined %}{{ arrayTotals[date]['numberDeposits'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['depositAmount'] is defined %}{{ arrayTotals[date]['depositAmount'] / 100 }}€{% else %}0€{% endif %}</td>
        <td>{% if arrayTotals[date]['numberWithdrawals'] is defined %}{{ arrayTotals[date]['numberWithdrawals'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['withdrawalAmount'] is defined %}{{ arrayTotals[date]['withdrawalAmount'] /100 }}€{% else %}0€{% endif %}</td>
        <td>{% if arrayTotals[date]['playerWinnings'] is defined %}{{ arrayTotals[date]['playerWinnings'] / 100 }}€{% else %}0€{% endif %}</td>
        {% for key, kpi in generalKPIs[date] %}
            <tr>
                <td class="values">{{ countryList[key] }}</td>
                <td class="values">{% if kpi['newRegistrations'] is defined %}{{ kpi['newRegistrations'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['newDepositors'] is defined %}{{ kpi['newDepositors'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['newDepositors'] is defined and kpi['newRegistrations'] is defined %}{{ (kpi['newDepositors'] / kpi['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
                <td class="values">{% if kpi['actives'] is defined %}{{ kpi['actives'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['numberBets'] is defined %}{{ kpi['numberBets'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['totalBets'] is defined %}{{ kpi['totalBets'] /100 }}€{% else %}0€{% endif %}</td>
                <td class="values">{% if kpi['numberDeposits'] is defined %}{{ kpi['numberDeposits'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['depositAmount'] is defined %}{{ kpi['depositAmount'] / 100 }}€{% else %}0€{% endif %}</td>
                <td class="values">{% if kpi['numberWithdrawals'] is defined %}{{ kpi['numberWithdrawals'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['withdrawalAmount'] is defined %}{{ kpi['withdrawalAmount'] / 100 }}€{% else %}0€{% endif %}</td>
                <td class="values">{% if kpi['playerWinnings'] is defined %}{{ kpi['playerWinnings'] / 100 }}€{% else %}0€{% endif %}</td>
            </tr>
        {% endfor %}
        </tr>
    {% endfor %}
    </tbody>
</table>