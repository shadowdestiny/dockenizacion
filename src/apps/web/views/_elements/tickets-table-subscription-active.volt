<div class="tickets-table">
    <table>
        {% for my_subscription_active in my_subscription_actives %}
        <tr>
            <td class="lottery">
                Euro Millions
            </td>
            <td class="date-from">
                {{ my_subscription_active['start_draw_date'] }}
            </td>
            <td class="date-to">
                {{ my_subscription_active['last_draw_date'] }}
            </td>
            <td class="numbers">
            {% for i in 1..my_subscription_active['lines'] %}
                <div class="numbers--row">
                    <span>{{ my_subscription_active[i-1]['line_regular_number_one'] }}</span>
                    <span>{{ my_subscription_active[i-1]['line_regular_number_two'] }}</span>
                    <span>{{ my_subscription_active[i-1]['line_regular_number_three'] }}</span>
                    <span>{{ my_subscription_active[i-1]['line_regular_number_four'] }}</span>
                    <span>{{ my_subscription_active[i-1]['line_regular_number_five'] }}</span>
                    <span class="star">{{ my_subscription_active[i-1]['line_lucky_number_one'] }}</span>
                    <span class="star">{{ my_subscription_active[i-1]['line_lucky_number_two'] }}</span>
                </div>
            {% endfor %}
            </td>
        </tr>
        {% endfor %}
    </table>
</div>