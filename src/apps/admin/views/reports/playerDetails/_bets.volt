<h3 class="h4">Bets</h3>
{% if userBets is empty %}
    This player doesn't have bets
{% else %}
    <table class="table-program" width="100%">
        <thead>
        <tr class="special">
            <th>
                Date
            </th>
            <th>
                Transaction
            </th>
            <th>
                Movement
            </th>
            <th>
                Balance
            </th>
        </tr>
        </thead>
        <tbody>
            {% for userBet in userBets %}
                <tr>
                    <td align="center">
                        {{ userBet['date'] }}
                    </td>
                    <td align="center">
                        {{ userBet['entity_type'] }}
                    </td>
                    <td align="center">
                        {{ "%.2f"|format(userBet['movement'] ) }} €
                    </td>
                    <td align="center">
                        {{ "%.2f"|format(userBet['balance'] / 100 ) }} €
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
    {#{{ paginator_view_inactives }}#}
{% endif %}