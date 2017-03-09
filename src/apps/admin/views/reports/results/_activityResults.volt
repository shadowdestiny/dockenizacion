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
    </tr>
    </thead>
    <tbody>
    {% for date in arrayDates %}
        <tr>
        <td class="index" width="190px;">{{ date }}</td>
        <td>{% if arrayTotals[date]['newRegistrations'] is defined %}{{ arrayTotals[date]['newRegistrations'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['depositorsD0'] is defined %}{{ arrayTotals[date]['depositorsD0'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['depositorsD1'] is defined %}{{ arrayTotals[date]['depositorsD1'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['depositorsD2'] is defined %}{{ arrayTotals[date]['depositorsD2'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['newDepositors'] is defined and arrayTotals[date]['newRegistrations'] is defined %}{{ (arrayTotals[date]['newDepositors'] / arrayTotals[date]['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
        <td>{% if arrayTotals[date]['actives'] is defined %}{{ arrayTotals[date]['actives'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['justInactive'] is defined %}{{ arrayTotals[date]['justInactive'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['inactive'] is defined %}{{ arrayTotals[date]['inactive'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['dormant'] is defined %}{{ arrayTotals[date]['dormant'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['reactivatedJI'] is defined %}{{ arrayTotals[date]['reactivatedJI'] / 100 }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['reactivatedIN'] is defined %}{{ arrayTotals[date]['reactivatedIN'] }}{% else %}0{% endif %}</td>
        <td>{% if arrayTotals[date]['reactivatedDOR'] is defined %}{{ arrayTotals[date]['reactivatedDOR'] /100 }}{% else %}0{% endif %}</td>
        {% for key, kpi in generalKPIs[date] %}
            <tr>
                <td class="values">{{ countryList[key] }}</td>
                <td class="values">{% if kpi['newRegistrations'] is defined %}{{ kpi['newRegistrations'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['depositorsD0'] is defined %}{{ kpi['depositorsD0'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['depositorsD1'] is defined %}{{ kpi['depositorsD1'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['depositorsD2'] is defined %}{{ kpi['depositorsD2'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['newDepositors'] is defined and kpi['newRegistrations'] is defined %}{{ (kpi['newDepositors'] / kpi['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
                <td class="values">{% if kpi['actives'] is defined %}{{ kpi['actives'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['justInactive'] is defined %}{{ kpi['justInactive'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['inactive'] is defined %}{{ kpi['inactive'] /100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['dormant'] is defined %}{{ kpi['dormant'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['reactivatedJI'] is defined %}{{ kpi['reactivatedJI'] / 100 }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['reactivatedIN'] is defined %}{{ kpi['reactivatedIN'] }}{% else %}0{% endif %}</td>
                <td class="values">{% if kpi['reactivatedDOR'] is defined %}{{ kpi['reactivatedDOR'] / 100 }}{% else %}0{% endif %}</td>
            </tr>
        {% endfor %}
        </tr>
    {% endfor %}
    <tr>
        <td>TOTAL</td>
        <td>{% if total['newRegistrations'] is defined %}{{ total['newRegistrations'] }}{% else %}0{% endif %}</td>
        <td>{% if total['depositorsD0'] is defined %}{{ total['depositorsD0'] }}{% else %}0{% endif %}</td>
        <td>{% if total['depositorsD1'] is defined %}{{ total['depositorsD1'] }}{% else %}0{% endif %}</td>
        <td>{% if total['depositorsD2'] is defined %}{{ total['depositorsD2'] }}{% else %}0{% endif %}</td>
        <td>{% if total['newRegistrations'] is defined and total['newDepositors'] is defined%}{{ (total['newDepositors'] / total['newRegistrations'] * 100) | number_format (2,',','') }}%{% else %}0%{% endif %}</td>
        <td>{% if valueTotalManualAlternativeQueryForOneValue is defined %}{{ valueTotalManualAlternativeQueryForOneValue }}{% else %}0{% endif %}</td>
        <td>{% if total['justInactive'] is defined %}{{ total['justInactive'] }}{% else %}0{% endif %}</td>
        <td>{% if total['inactive'] is defined %}{{ total['inactive']  / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['dormant'] is defined %}{{ total['dormant'] }}{% else %}0{% endif %}</td>
        <td>{% if total['reactivatedJI'] is defined %}{{ total['reactivatedJI']  / 100 }}{% else %}0{% endif %}</td>
        <td>{% if total['reactivatedIN'] is defined %}{{ total['reactivatedIN'] }}{% else %}0{% endif %}</td>
        <td>{% if total['reactivatedDOR'] is defined %}{{ total['reactivatedDOR'] / 100 }}{% else %}0{% endif %}</td>

    </tr>
    </tbody>
</table>