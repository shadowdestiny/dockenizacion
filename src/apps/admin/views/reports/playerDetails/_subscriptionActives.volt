<h3 class="h4">Subscriptions Actives</h3>
{% if my_subscription_actives is empty %}
    This player didn't have subscription actives
{% else %}
    <table class="table-program" width="100%">
        <thead>
        <tr class="special">
            <th width="200px">
                Draw date
            </th>
            <th width="150px">
                Lottery
            </th>
            <th>
                Numbers played
            </th>
        </tr>
        </thead>
        <tbody>
        {% for my_subscription_active in my_subscription_actives %}
            <tr>
                <td align="center">
                    {{ my_subscription_active['start_draw_date'] }} to {{ my_subscription_active['last_draw_date'] }}
                </td>
                <td align="center">
                    <strong>Euromillions</strong>
                </td>
                <td>
                    <span class="num">{{ my_subscription_active['line_regular_number_one'] }}</span>
                    <span class="num">{{ my_subscription_active['line_regular_number_two'] }}</span>
                    <span class="num">{{ my_subscription_active['line_regular_number_three'] }}</span>
                    <span class="num">{{ my_subscription_active['line_regular_number_four'] }}</span>
                    <span class="num">{{ my_subscription_active['line_regular_number_five'] }}</span>
                    <span class="num yellow">{{ my_subscription_active['line_lucky_number_one'] }}</span>
                    <span class="num yellow">{{ my_subscription_active['line_lucky_number_two'] }}</span>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endif %}