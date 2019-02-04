<div class="tickets-table">
    <table>

        <tr>
            <td class="lottery">{{ language.translate("tickets_past_lotto") }}</td>
            <td class="date-from">{{ language.translate("first_draw") }}</td>
            <td class="date-to">{{ language.translate("last_draw") }}</td>
            <td class="numbers">{{ language.translate("tickets_SubsPast_numbers") }}</td>
        </tr>

        {% for my_subscription_active in my_subscription_actives %}
            <tr>
                <td class="lottery">
                    {{ my_subscription_active['name'] }}
                </td>
                <td class="date-from">
                    {{ my_subscription_active['start_draw_date'].format(language.translate('dateformat')) }}
                </td>
                <td class="date-to">
                    {{ my_subscription_active['last_draw_date'].format(language.translate('dateformat')) }}
                </td>
                <td class="numbers">
                    {% for i in 1..my_subscription_active['lines'] %}
                        <div class="numbers--row">
                            {#{{ dump(my_subscription_active) }}#}
                            {% if (my_subscription_active[i-1]['powerplay']) %}PowerPlay{% endif %}
                            <span>{{ my_subscription_active[i-1]['line_regular_number_one'] }}</span>
                            <span>{{ my_subscription_active[i-1]['line_regular_number_two'] }}</span>
                            <span>{{ my_subscription_active[i-1]['line_regular_number_three'] }}</span>
                            <span>{{ my_subscription_active[i-1]['line_regular_number_four'] }}</span>
                            <span>{{ my_subscription_active[i-1]['line_regular_number_five'] }}</span>
                            {% if (my_subscription_active['name'] != 'PowerBall') %}
                                <span class="star">{{ my_subscription_active[i-1]['line_lucky_number_one'] }}</span>
                            {% endif %}
                            <span class="star">{{ my_subscription_active[i-1]['line_lucky_number_two'] }}</span>
                        </div>
                    {% endfor %}
                </td>
            </tr>
        {% endfor %}
    </table>
</div>
