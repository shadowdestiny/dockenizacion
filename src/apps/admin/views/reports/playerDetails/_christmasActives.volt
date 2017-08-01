<h3 class="h4">Christmas Tickets</h3>
{% if my_christmas_actives is empty %}
    This player didn't have christmas tickets
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
        {% for my_christmas_active in my_christmas_actives %}
            <tr>
                <td align="center">
                    {{ my_christmas_active['start_draw_date'] }}
                </td>
                <td align="center">
                    <strong>Christmas</strong>
                </td>
                <td>
                    <span class="num">{{ my_christmas_active['line_regular_number_one'] }}</span>
                    <span class="num">{{ my_christmas_active['line_regular_number_two'] }}</span>
                    <span class="num">{{ my_christmas_active['line_regular_number_three'] }}</span>
                    <span class="num">{{ my_christmas_active['line_regular_number_four'] }}</span>
                    <span class="num">{{ my_christmas_active['line_regular_number_five'] }}</span>
                    <!-- span class="num yellow">{{ my_christmas_active['line_lucky_number_one'] }}</span>
                    <span class="num yellow">{{ my_christmas_active['line_lucky_number_two'] }}</span -->
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endif %}