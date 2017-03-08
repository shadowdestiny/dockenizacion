<h3 class="h4">Deposits</h3>
{% if userDeposits is empty %}
    This player doesn't have deposits
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
            {% for userDeposit in userDeposits %}
                <tr>
                    <td align="center">
                        {{ userDeposit['date'] }}
                    </td>
                    <td align="center">
                        {{ userDeposit['entity_type'] }}
                    </td>
                    <td align="center">
                    {% if userDeposit['entity_type'] == 'subscription_purchase' %}
                        {{ "%.2f"|format(userDeposit['subsMovement'] / 100 ) }}
                    {% else %}
                        {{ "%.2f"|format(userDeposit['movement'] / 100 ) }}
                    {% endif %}
                    </td>
                    <td align="center">
                        {{ "%.2f"|format(userDeposit['balance'] / 100 ) }}
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
    {{ paginator_view_deposits }}
    <p align="right"><input type="button" onclick="downloadDeposits('{{ user.getId() }}')" value="Download" class="btn btn-primary" /></p>

{% endif %}