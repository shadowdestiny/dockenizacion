<h3 class="h4">Subscriptions Finished</h3>
{% if my_subscription_inactives is empty %}
    This player didn't have subscription inactives
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
        {% for my_subscription_inactive in my_subscription_inactives %}
            <tr>
                <td align="center">
                    {{ my_subscription_inactive['start_draw_date'] }} to {{ my_subscription_inactive['last_draw_date'] }}
                </td>
                <td align="center">
                    <strong>Euromillions</strong>
                </td>
                <td>
                    <span class="num">{{ my_subscription_inactive['line_regular_number_one'] }}</span>
                    <span class="num">{{ my_subscription_inactive['line_regular_number_two'] }}</span>
                    <span class="num">{{ my_subscription_inactive['line_regular_number_three'] }}</span>
                    <span class="num">{{ my_subscription_inactive['line_regular_number_four'] }}</span>
                    <span class="num">{{ my_subscription_inactive['line_regular_number_five'] }}</span>
                    <span class="num yellow">{{ my_subscription_inactive['line_lucky_number_one'] }}</span>
                    <span class="num yellow">{{ my_subscription_inactive['line_lucky_number_two'] }}</span>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endif %}