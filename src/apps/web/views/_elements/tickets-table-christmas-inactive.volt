<div class="tickets-table">
    <table>
        {% for my_christmas_active in my_christmas_actives %}
        <tr>
            <td class="lottery">
                Christmas
            </td>
            <td class="date-from">
                2018-12-22
            </td>
            <td class="date-to">

            </td>
            <td class="numbers">
                <div class="numbers--row">
                    <span>{{ my_christmas_active['line_regular_number_one'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_two'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_three'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_four'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_five'] }}</span>
                    {#<span class="star">{{ my_christmas_active['line_lucky_number_one'] }}</span>#}
                    {#<span class="star">{{ my_christmas_active['line_lucky_number_two'] }}</span>#}
                </div>
            </td>
        </tr>
        {% endfor %}
    </table>
</div>
